<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Aws\S3\Exception\S3Exception;
use Aws\Iam\IamClient;

class S3Controller extends Controller
{
    // Feature 1: Create Bucket with Dynamic Plan Choice
    public function createBucket(Request $request)
    {
        $request->validate([
            'bucket_name' => 'required|string|min:3|max:63|regex:/^[a-z0-9.-]+$/',
            'plan_id' => 'required|exists:subscription_plans,id' // Validate the choice
        ]);

        $bucketName = $request->bucket_name;
        $planId = $request->plan_id;
        $userId = Auth::id();

        // Fetch the plan to dynamically calculate the hourly billing cost
        $plan = DB::table('subscription_plans')->where('id', $planId)->first();
        $hourlyCost = $plan ? ($plan->monthly_price / 720) : 0.007;

        try {
            $s3Client = Storage::disk('s3')->getClient();
            $s3Client->createBucket(['Bucket' => $bucketName]);

            // 1. Save to Provisioned Resources (The Infrastructure)
            DB::table('provisioned_resources')->insert([
                'user_id' => $userId,
                'plan_id' => $planId,
                'resource_type' => 'storage',
                'instance_name' => $bucketName,
                'ministack_resource_id' => $bucketName,
                'configuration' => json_encode(['region' => 'us-east-1']),
                'status' => 'running',
                'hourly_cost' => round($hourlyCost, 5),
                'rent_start_date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 2. NEW: Save to User Subscriptions (The Billing/Quota)
            DB::table('user_subscriptions')->insert([
                'user_id' => $userId,
                'plan_id' => $planId,
                'ministack_bucket_name' => $bucketName,
                'ministack_bucket_id' => $bucketName,
                'remaining_storage_quota_gb' => $plan->storage_quota_gb,
                'remaining_compute_quota' => $plan->compute_quota_vcpu ?? 0,
                'remaining_vpc_quota' => $plan->network_quota_vpc ?? 0,
                'subscription_status' => 'active',
                'start_date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return back()->with('success', "Bucket provisioned and Subscription Activated!");
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // Feature 2: Batch Upload with Dynamic Quota Checking
    public function uploadObject(Request $request)
    {
        $request->validate([
            'bucket_name' => 'required|string',
            'files' => 'required|array',
            'files.*' => 'required|file'
        ]);

        $bucketName = $request->bucket_name;
        $files = $request->file('files');

        $newFilesTotalSize = 0;
        foreach ($files as $file) {
            $newFilesTotalSize += $file->getSize();
        }

        try {
            // 1. Find out what plan this bucket belongs to
            $activeResource = DB::table('provisioned_resources')
                ->where('ministack_resource_id', $bucketName)
                ->where('status', 'running')
                ->first();

            $limitGB = 5; // Default
            if ($activeResource) {
                $plan = DB::table('subscription_plans')->where('id', $activeResource->plan_id)->first();
                if ($plan) $limitGB = $plan->storage_quota_gb;
            }

            $limitBytes = $limitGB * 1024 * 1024 * 1024;

            // 2. Weigh the existing bucket
            $s3Client = Storage::disk('s3')->getClient();
            $objects = $s3Client->listObjectsV2(['Bucket' => $bucketName]);
            $currentSize = 0;

            if (!empty($objects['Contents'])) {
                foreach ($objects['Contents'] as $object) {
                    $currentSize += $object['Size'];
                }
            }

            // 3. The Dynamic Limit Check
            if (($currentSize + $newFilesTotalSize) > $limitBytes) {
                return response()->json(['success' => false, 'message' => "Upload Blocked: Adding these files exceeds your {$limitGB}GB quota."], 422);
            }

            // 4. Batch Upload
            $uploadedCount = 0;
            foreach ($files as $file) {
                $fileName = $file->getClientOriginalName();
                $s3Client->putObject([
                    'Bucket' => $bucketName,
                    'Key' => $fileName,
                    'SourceFile' => $file->getPathname(),
                ]);
                $uploadedCount++;
            }

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => "{$uploadedCount} files uploaded successfully!"]);
            }
            return back()->with('success', "{$uploadedCount} files successfully uploaded!");
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    // Feature 3: View Files in a Bucket (Now AJAX-Ready)
    public function viewFiles(Request $request)
    {
        $request->validate(['bucket_name' => 'required|string']);
        $bucketName = $request->bucket_name;

        try {
            $s3Client = Storage::disk('s3')->getClient();
            $objects = $s3Client->listObjectsV2(['Bucket' => $bucketName]);
            $files = $objects['Contents'] ?? [];

            // If the request comes from our JavaScript, return JSON
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'files' => $files,
                    'current_bucket' => $bucketName
                ]);
            }

            // Fallback for standard page loads
            return back()->with('files', $files)->with('current_bucket', $bucketName);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
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
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'File deleted.']);
            }
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

            // Terminate the User Subscription
            DB::table('user_subscriptions')
                ->where('ministack_bucket_name', $bucketName)
                ->where('user_id', Auth::id())
                ->where('subscription_status', 'active')
                ->update([
                    'subscription_status' => 'cancelled',
                    'end_date' => now(),
                    'updated_at' => now()
                ]);

            return back()->with('success', "Bucket '{$bucketName}' terminated. Billing has been stopped.");
        } catch (\Exception $e) {
            return back()->with('error', 'Termination failed: ' . $e->getMessage());
        }
    }

    public function generateCredentials(Request $request)
    {
        $userId = Auth::id();

        // 1. Cek apakah user sudah punya kunci
        if (DB::table('access_credentials')->where('user_id', $userId)->where('is_active', true)->exists()) {
            return back()->with('error', 'Anda sudah memiliki kredensial API aktif.');
        }

        try {
            // 2. Koneksikan IAM Client ke MiniStack Anda
            $iamClient = new IamClient([
                'version' => 'latest',
                'region'  => env('AWS_DEFAULT_REGION', 'us-east-1'),
                'endpoint' => env('AWS_ENDPOINT'), // Akan mengambil port 4566 dari .env
                'credentials' => [
                    'key'    => env('AWS_ACCESS_KEY_ID'),     // 'test'
                    'secret' => env('AWS_SECRET_ACCESS_KEY'), // 'test'
                ],
            ]);

            // 3. Buat nama user yang unik
            $iamUsername = 'ministack_user_' . $userId;

            // 4. Perintahkan MiniStack membuat User (Pasti berhasil karena didukung!)
            $iamClient->createUser([
                'UserName' => $iamUsername
            ]);

            // 5. Perintahkan MiniStack membuatkan Kunci untuk User tersebut
            $result = $iamClient->createAccessKey([
                'UserName' => $iamUsername
            ]);

            // 6. Tangkap kuncinya
            $accessKey = $result['AccessKey']['AccessKeyId'];
            $secretKey = $result['AccessKey']['SecretAccessKey'];

            // 7. Simpan ke database Anda
            DB::table('access_credentials')->insert([
                'user_id' => $userId,
                'access_key' => $accessKey,
                'secret_key_encrypted' => encrypt($secretKey),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return back()->with('success', 'Akses Kredensial API Berhasil Dibuat Langsung via MiniStack!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses kredensial: ' . $e->getMessage());
        }
    }
}
