<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    /**
     * S3: List all files in the bucket.
     */
    public function s3ListFiles(Request $request)
    {
        $resource = $request->attributes->get('api_resource');
        if ($resource->resource_type !== 'storage') {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: This API key is not authorized for S3 Storage operations.'
            ], 403);
        }

        $bucketName = $resource->ministack_resource_id;

        try {
            $s3Client = Storage::disk('s3')->getClient();
            $objects = $s3Client->listObjectsV2(['Bucket' => $bucketName]);
            $contents = $objects['Contents'] ?? [];

            $files = array_map(function ($file) {
                return [
                    'key' => $file['Key'],
                    'size' => $file['Size'],
                    'last_modified' => $file['LastModified']->__toString(),
                ];
            }, $contents);

            return response()->json([
                'success' => true,
                'bucket' => $bucketName,
                'files' => $files
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to list files: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * S3: Upload a file to the bucket.
     */
    public function s3UploadFile(Request $request)
    {
        $resource = $request->attributes->get('api_resource');
        if ($resource->resource_type !== 'storage') {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: This API key is not authorized for S3 Storage operations.'
            ], 403);
        }

        $bucketName = $resource->ministack_resource_id;

        $request->validate([
            'file' => 'required|file'
        ]);

        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $fileSize = $file->getSize();

        try {
            // Check storage limit
            $plan = DB::table('subscription_plans')->where('id', $resource->plan_id)->first();
            $limitGB = $plan ? $plan->storage_quota_gb : 5;
            $limitBytes = $limitGB * 1024 * 1024 * 1024;

            $s3Client = Storage::disk('s3')->getClient();
            $objects = $s3Client->listObjectsV2(['Bucket' => $bucketName]);
            $currentSize = 0;
            if (!empty($objects['Contents'])) {
                foreach ($objects['Contents'] as $object) {
                    $currentSize += $object['Size'];
                }
            }

            if (($currentSize + $fileSize) > $limitBytes) {
                return response()->json([
                    'success' => false,
                    'message' => "Upload Blocked: Adding this file exceeds your {$limitGB}GB quota."
                ], 422);
            }

            $s3Client->putObject([
                'Bucket' => $bucketName,
                'Key' => $fileName,
                'SourceFile' => $file->getPathname(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "File '{$fileName}' uploaded successfully to bucket '{$bucketName}'."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * S3: Download a file from the bucket.
     */
    public function s3DownloadFile(Request $request)
    {
        $resource = $request->attributes->get('api_resource');
        if ($resource->resource_type !== 'storage') {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: This API key is not authorized for S3 Storage operations.'
            ], 403);
        }

        $bucketName = $resource->ministack_resource_id;

        $request->validate([
            'key' => 'required|string'
        ]);

        $key = $request->key;

        try {
            $s3Client = Storage::disk('s3')->getClient();
            $result = $s3Client->getObject([
                'Bucket' => $bucketName,
                'Key' => $key
            ]);

            return response($result['Body']->getContents(), 200, [
                'Content-Type' => $result['ContentType'] ?? 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . basename($key) . '"'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Download failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * S3: Delete a file from the bucket.
     */
    public function s3DeleteFile(Request $request)
    {
        $resource = $request->attributes->get('api_resource');
        if ($resource->resource_type !== 'storage') {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: This API key is not authorized for S3 Storage operations.'
            ], 403);
        }

        $bucketName = $resource->ministack_resource_id;

        $request->validate([
            'key' => 'required|string'
        ]);

        $key = $request->key;

        try {
            $s3Client = Storage::disk('s3')->getClient();
            $s3Client->deleteObject([
                'Bucket' => $bucketName,
                'Key' => $key
            ]);

            return response()->json([
                'success' => true,
                'message' => "File '{$key}' deleted successfully from bucket '{$bucketName}'."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * EC2: Get status and configuration of compute resource.
     */
    public function ec2Status(Request $request)
    {
        $resource = $request->attributes->get('api_resource');
        if ($resource->resource_type !== 'compute') {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: This API key is not authorized for EC2 Compute operations.'
            ], 403);
        }

        $config = json_decode($resource->configuration, true);

        return response()->json([
            'success' => true,
            'instance_id' => $resource->ministack_resource_id,
            'instance_name' => $resource->instance_name,
            'status' => $resource->status,
            'hourly_cost' => (float) $resource->hourly_cost,
            'launched_at' => $resource->rent_start_date,
            'configuration' => $config
        ]);
    }

    /**
     * EC2: Start the compute instance.
     */
    public function ec2Start(Request $request)
    {
        $resource = $request->attributes->get('api_resource');
        if ($resource->resource_type !== 'compute') {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: This API key is not authorized for EC2 Compute operations.'
            ], 403);
        }

        if ($resource->status === 'running') {
            return response()->json([
                'success' => true,
                'message' => 'Instance is already running.'
            ]);
        }

        $instanceId = $resource->ministack_resource_id;

        try {
            $ec2Client = $this->getEc2Client();

            $ec2Client->startInstances(['InstanceIds' => [$instanceId]]);

            DB::table('provisioned_resources')
                ->where('id', $resource->id)
                ->update([
                    'status' => 'running',
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => "Instance '{$instanceId}' started successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Start failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * EC2: Stop the compute instance.
     */
    public function ec2Stop(Request $request)
    {
        $resource = $request->attributes->get('api_resource');
        if ($resource->resource_type !== 'compute') {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: This API key is not authorized for EC2 Compute operations.'
            ], 403);
        }

        if ($resource->status === 'stopped') {
            return response()->json([
                'success' => true,
                'message' => 'Instance is already stopped.'
            ]);
        }

        $instanceId = $resource->ministack_resource_id;

        try {
            $ec2Client = $this->getEc2Client();

            $ec2Client->stopInstances(['InstanceIds' => [$instanceId]]);

            DB::table('provisioned_resources')
                ->where('id', $resource->id)
                ->update([
                    'status' => 'stopped',
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => "Instance '{$instanceId}' stopped successfully."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Stop failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper to resolve EC2 client (for testing/mocking)
     */
    protected function getEc2Client()
    {
        if (app()->has(\Aws\Ec2\Ec2Client::class)) {
            return app(\Aws\Ec2\Ec2Client::class);
        }

        return new \Aws\Ec2\Ec2Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'endpoint' => env('AWS_ENDPOINT'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }
}
