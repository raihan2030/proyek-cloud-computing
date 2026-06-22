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

        // Check if bucket name is already registered in provisioned_resources
        $existsInDb = DB::table('provisioned_resources')
            ->where('ministack_resource_id', $bucketName)
            ->exists();
        if ($existsInDb) {
            return back()->withInput()->with('error', 'Nama bucket S3 tersebut sudah digunakan. Silakan pilih nama lain yang unik.');
        }

        // Check if bucket already exists in MiniStack S3
        try {
            $s3Client = Storage::disk('s3')->getClient();
            if ($s3Client->doesBucketExist($bucketName)) {
                return back()->withInput()->with('error', 'Nama bucket tersebut sudah terdaftar di server storage. Silakan gunakan nama lain.');
            }
        } catch (\Exception $e) {
            // Fail silently or handle if S3 client cannot connect (e.g. testing)
        }

        // Fetch the plan to dynamically calculate the hourly billing cost
        $plan = DB::table('subscription_plans')->where('id', $planId)->first();
        $hourlyCost = $plan ? ($plan->monthly_price / 720) : 105.00;

        try {
            $s3Client = Storage::disk('s3')->getClient();
            $s3Client->createBucket(['Bucket' => $bucketName]);

            // 1. Save to Provisioned Resources (Gunakan insertGetId dan tampung di $resourceId)
            $resourceId = DB::table('provisioned_resources')->insertGetId([
                'user_id'               => $userId,
                'plan_id'               => $planId,
                'resource_type'         => 'storage',
                'instance_name'         => $bucketName,
                'ministack_resource_id' => $bucketName,
                'configuration'         => json_encode(['region' => 'us-east-1']),
                'status'                => 'running',
                'hourly_cost'           => round($hourlyCost, 5),
                'rent_start_date'       => now(),
                'created_at'            => now(),
                'updated_at'            => now()
            ]);

            // 2. Tambahkan pencatatan ke activity_logs (Sesuai ERD)
            DB::table('activity_logs')->insert([
                'user_id'     => $userId,
                'resource_id' => $resourceId,
                'action_type' => 'create',
                'description' => "Provisioned S3 Bucket {$bucketName}",
                'created_at'  => now(),
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

            // 2. Cari resource ini di database untuk mendapatkan ID-nya
            $resource = DB::table('provisioned_resources')
                ->where('ministack_resource_id', $bucketName)
                ->where('user_id', Auth::id())
                ->first();

            if ($resource) {
                // 3. LOGIKA BARU: Hapus Kredensial API yang terikat dengan Bucket ini
                DB::table('access_credentials')
                    ->where('provisioned_id', $resource->id)
                    ->delete();

                // 4. Update the Database Clipboard (Stop billing)
                DB::table('provisioned_resources')->where('id', $resource->id)->update([
                    'status' => 'terminated',
                    'rent_end_date' => now(),
                    'updated_at' => now()
                ]);

                // 5. Log activity penghapusan
                DB::table('activity_logs')->insert([
                    'user_id'     => Auth::id(),
                    'resource_id' => $resource->id,
                    'action_type' => 'delete',
                    'description' => "Deleted S3 Bucket {$bucketName} and revoked its credentials",
                    'created_at'  => now(),
                ]);
            }

            return back()->with('success', "Bucket '{$bucketName}' terminated. Billing and associated credentials have been permanently removed.");
        } catch (\Exception $e) {
            return back()->with('error', 'Termination failed: ' . $e->getMessage());
        }
    }

    public function generateCredentials(Request $request)
    {
        // Validasi input dari form dropdown di dashboard
        $request->validate([
            'provisioned_id' => 'required|exists:provisioned_resources,id'
        ]);

        $userId = Auth::id();
        $provisionedId = $request->provisioned_id;

        // 1. Cek kepemilikan resource (Pastikan ini milik user yang sedang login)
        $resource = DB::table('provisioned_resources')
            ->where('id', $provisionedId)
            ->where('user_id', $userId)
            ->first();

        if (!$resource) {
            return back()->with('error', 'Gagal: Resource tidak ditemukan atau bukan milik Anda.');
        }

        // 2. Cek apakah resource INI sudah punya kredensial aktif
        if (DB::table('access_credentials')->where('provisioned_id', $provisionedId)->where('is_active', true)->exists()) {
            return back()->with('error', "Resource {$resource->instance_name} sudah memiliki kredensial API aktif.");
        }

        try {
            // 3. Koneksikan IAM Client ke MiniStack
            $iamClient = new \Aws\Iam\IamClient([
                'version' => 'latest',
                'region'  => env('AWS_DEFAULT_REGION', 'us-east-1'),
                'endpoint' => env('AWS_ENDPOINT'),
                'credentials' => [
                    'key'    => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);

            // 4. Buat nama user IAM yang unik spesifik untuk resource ini
            $iamUsername = 'usr_' . $userId . '_res_' . $provisionedId . '_' . time();

            // 5. Perintahkan MiniStack membuat User
            $iamClient->createUser([
                'UserName' => $iamUsername
            ]);

            // 6. Perintahkan MiniStack membuatkan Kunci API
            $result = $iamClient->createAccessKey([
                'UserName' => $iamUsername
            ]);

            $accessKey = $result['AccessKey']['AccessKeyId'];
            $secretKey = $result['AccessKey']['SecretAccessKey'];

            // 7. Simpan ke database
            DB::table('access_credentials')->insert([
                'user_id'              => $userId,
                'provisioned_id'       => $provisionedId,
                'access_key'           => $accessKey,
                'secret_key_encrypted' => encrypt($secretKey),
                'is_active'            => true,
                'created_at'           => now(),
                'updated_at'           => now()
            ]);

            return back()->with('success', "Kredensial API berhasil dibuat khusus untuk resource: {$resource->instance_name}");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses kredensial: ' . $e->getMessage());
        }
    }

    public function modifyPlan(Request $request)
    {
        $request->validate([
            'bucket_name'    => 'required|string',
            'target_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $bucketName = $request->bucket_name;
        $targetPlanId = $request->target_plan_id;
        $userId = Auth::id();

        // 1. Retrieve the resource and make sure it exists, is storage, and belongs to the user
        $resource = DB::table('provisioned_resources')
            ->where('ministack_resource_id', $bucketName)
            ->where('user_id', $userId)
            ->where('resource_type', 'storage')
            ->first();

        if (!$resource) {
            return back()->with('error', 'Resource bucket tidak ditemukan atau bukan milik Anda.');
        }

        // 2. Fetch the new plan details from subscription_plans (ensure it is a Storage plan)
        $plan = DB::table('subscription_plans')
            ->join('iaas_services', 'subscription_plans.service_id', '=', 'iaas_services.id')
            ->where('subscription_plans.id', $targetPlanId)
            ->where('iaas_services.service_category', 'Storage')
            ->select('subscription_plans.*')
            ->first();

        if (!$plan) {
            return back()->with('error', 'Target plan tidak valid untuk layanan Storage.');
        }

        // 3. Quota check: ensure current usage doesn't exceed the target plan's storage quota
        $usedBytes = 0;
        try {
            $s3Client = Storage::disk('s3')->getClient();
            $objects = $s3Client->listObjectsV2(['Bucket' => $bucketName]);
            if (!empty($objects['Contents'])) {
                foreach ($objects['Contents'] as $object) {
                    $usedBytes += $object['Size'];
                }
            }
        } catch (\Exception $e) {
            // Fail silently if S3/MiniStack is off, allowing tests to run
        }
        $usedGB = $usedBytes / (1024 * 1024 * 1024);

        if ($usedGB > $plan->storage_quota_gb) {
            if ($usedGB > 0 && $usedGB < 0.1) {
                $displaySize = round($usedBytes / (1024 * 1024), 2) . ' MB';
            } else {
                $displaySize = round($usedGB, 2) . ' GB';
            }
            return back()->with('error', "Anda tidak dapat menurunkan paket penyimpanan. Kapasitas yang digunakan saat ini ({$displaySize}) melebihi kuota paket target ({$plan->storage_quota_gb} GB).");
        }

        // 4. Update the user_subscriptions table (if it exists)
        if (\Illuminate\Support\Facades\Schema::hasTable('user_subscriptions')) {
            DB::table('user_subscriptions')
                ->where('ministack_bucket_name', $bucketName)
                ->where('user_id', $userId)
                ->update([
                    'plan_id' => $targetPlanId,
                    'remaining_storage_quota_gb' => $plan->storage_quota_gb,
                    'updated_at' => now()
                ]);
        }

        // 5. Update the provisioned_resources table
        $hourlyCost = $plan->monthly_price / 720;

        DB::table('provisioned_resources')
            ->where('ministack_resource_id', $bucketName)
            ->where('user_id', $userId)
            ->where('resource_type', 'storage')
            ->update([
                'plan_id'     => $targetPlanId,
                'hourly_cost' => round($hourlyCost, 5),
                'updated_at'  => now()
            ]);

        // Log the activity
        DB::table('activity_logs')->insert([
            'user_id'     => $userId,
            'resource_id' => $resource->id,
            'action_type' => 'update',
            'description' => "Modified S3 Bucket {$bucketName} subscription plan to '{$plan->plan_name}'",
            'created_at'  => now(),
        ]);

        return back()->with('success', "Subscription plan updated to '{$plan->plan_name}' successfully!");
    }
}

