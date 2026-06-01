<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Aws\S3\Exception\S3Exception;

class S3Controller extends Controller
{
    // Feature 1: Create an Isolated Bucket
    public function createBucket(Request $request)
    {
        $request->validate([
            'bucket_name' => 'required|string|min:3|max:63|regex:/^[a-z0-9.-]+$/'
        ]);
        
        $bucketName = $request->bucket_name;
        $userId = Auth::id(); 

        try {
            // 1. Build the physical locker in MiniStack
            $s3Client = Storage::disk('s3')->getClient();
            $s3Client->createBucket([
                'Bucket' => $bucketName
            ]);
            
            // 2. Write the record to the database clipboard (Raihan's Schema)
            DB::table('provisioned_resources')->insert([
                'user_id' => $userId,
                'plan_id' => 1, // Links to our new 'Basic Storage' plan
                'resource_type' => 'storage', // Must be 'storage'
                'instance_name' => $bucketName, // Required by schema
                'ministack_resource_id' => $bucketName,
                'configuration' => json_encode([
                    'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
                    'created_via' => 'web_dashboard'
                ]),
                'status' => 'running', // Must be 'running'
                'hourly_cost' => 0.007, // Approx $5 / 720 hours
                'rent_start_date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return back()->with('success', "Bucket '{$bucketName}' created in MiniStack and saved to Database!");
            
        } catch (S3Exception $e) {
            return back()->with('error', 'MiniStack Error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Database Error: ' . $e->getMessage());
        }
    }

    // Feature 2: Upload an Object to a specific Bucket
    public function uploadObject(Request $request)
    {
        $request->validate([
            'bucket_name' => 'required|string',
            'file' => 'required|file'
        ]);

        $bucketName = $request->bucket_name;
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName(); 
        $newFileSize = $file->getSize(); // Size of the incoming file in bytes

        try {
            $s3Client = Storage::disk('s3')->getClient();
            
            // 1. Calculate Current Luggage Weight (Existing Bucket Size)
            $objects = $s3Client->listObjectsV2(['Bucket' => $bucketName]);
            $currentSize = 0;
            
            if (!empty($objects['Contents'])) {
                foreach ($objects['Contents'] as $object) {
                    $currentSize += $object['Size'];
                }
            }

            // 2. The Limit Check (5GB in bytes = 5 * 1024 * 1024 * 1024)
            $limitBytes = 5368709120; 
            
            if (($currentSize + $newFileSize) > $limitBytes) {
                return back()->with('error', 'Upload Blocked: Storage Limit Exceeded! This file pushes you over your 5GB quota.');
            }
            
            // 3. If it passes the scale, put it on the conveyor belt
            $s3Client->putObject([
                'Bucket' => $bucketName,
                'Key' => $fileName,
                'SourceFile' => $file->getPathname(),
            ]);

            return back()->with('success', "File '{$fileName}' successfully uploaded! You have used " . number_format(($currentSize + $newFileSize) / 1048576, 2) . " MB of your quota.");
            
        } catch (S3Exception $e) {
            return back()->with('error', 'Failed to upload object: ' . $e->getMessage());
        }
    }

    // Feature 3: View Files in a Bucket
    public function viewFiles(Request $request)
    {
        $request->validate(['bucket_name' => 'required|string']);
        $bucketName = $request->bucket_name;

        try {
            $s3Client = Storage::disk('s3')->getClient();
            $objects = $s3Client->listObjectsV2(['Bucket' => $bucketName]);
            
            $files = $objects['Contents'] ?? [];
            return back()->with('files', $files)->with('current_bucket', $bucketName);
            
        } catch (S3Exception $e) {
            return back()->with('error', 'Failed to fetch files: ' . $e->getMessage());
        }
    }

    // Feature 4: Download a File
    public function downloadFile($bucket, $key)
    {
        try {
            $s3Client = Storage::disk('s3')->getClient();
            $object = $s3Client->getObject([
                'Bucket' => $bucket,
                'Key' => $key
            ]);
            
            return response((string) $object['Body'])
                ->header('Content-Type', $object['ContentType'])
                ->header('Content-Disposition', 'attachment; filename="' . $key . '"');
        } catch (\Exception $e) {
            return back()->with('error', 'Download failed: ' . $e->getMessage());
        }
    }

    // Feature 5: Delete a File
    public function deleteFile(Request $request)
    {
        try {
            $s3Client = Storage::disk('s3')->getClient();
            $s3Client->deleteObject([
                'Bucket' => $request->bucket_name,
                'Key' => $request->file_key
            ]);
            return back()->with('success', "File '{$request->file_key}' deleted successfully.");
        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    // Feature 6: Terminate Bucket & Stop Billing
    public function deleteBucket(Request $request)
    {
        $bucketName = $request->bucket_name;

        try {
            $s3Client = Storage::disk('s3')->getClient();
            
            // AWS requires a bucket to be completely empty before it can be deleted
            $objects = $s3Client->listObjectsV2(['Bucket' => $bucketName]);
            if (!empty($objects['Contents'])) {
                return back()->with('error', 'Bucket must be empty before termination. Please delete all files first.');
            }

            // 1. Demolish the locker in MiniStack
            $s3Client->deleteBucket(['Bucket' => $bucketName]);

            // 2. Update the Database Clipboard (Stop billing)
            DB::table('provisioned_resources')
                ->where('ministack_resource_id', $bucketName)
                ->where('user_id', Auth::id())
                ->update([
                    'status' => 'terminated',
                    'rent_end_date' => now(),
                    'updated_at' => now()
                ]);

            return back()->with('success', "Bucket '{$bucketName}' terminated. Billing has been stopped.");
            
        } catch (\Exception $e) {
            return back()->with('error', 'Termination failed: ' . $e->getMessage());
        }
    }
}