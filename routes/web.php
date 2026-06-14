<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\S3Controller;
use App\Http\Controllers\Ec2Controller;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

Route::get('/', function () {
    $data = [];
    if (Auth::check()) {
        $userId = Auth::id();
        $data['activeResourcesCount'] = DB::table('provisioned_resources')
            ->where('user_id', $userId)
            ->where('status', 'running')
            ->count();
    }
    return view('welcome', $data);
});

// Admin Route Group protected by auth and admin middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    
    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{id}/balance', [AdminController::class, 'updateBalance'])->name('users.balance');
    Route::post('/users/{id}/role', [AdminController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    
    // Plans
    Route::get('/plans', [AdminController::class, 'plans'])->name('plans');
    Route::post('/plans', [AdminController::class, 'createPlan'])->name('plans.create');
    Route::post('/plans/{id}/toggle', [AdminController::class, 'togglePlan'])->name('plans.toggle');
    Route::delete('/plans/{id}', [AdminController::class, 'deletePlan'])->name('plans.delete');
    
    // Resources
    Route::get('/resources', [AdminController::class, 'resources'])->name('resources');
    
    // Payments
    Route::get('/payments', [AdminController::class, 'payments'])->name('payments');
    Route::post('/payments/{id}/status', [AdminController::class, 'updatePaymentStatus'])->name('payments.status');
    
    // Logs
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs');
});

Route::get('/dashboard', function () {
    
    $userId = Auth::id();

    // 1. Gather Top Metrics Data
    $totalResources = DB::table('provisioned_resources')
        ->where('user_id', $userId)
        ->count();

    // Active Services sekarang dihitung dari provisioned_resources yang statusnya running
    $activeServices = DB::table('provisioned_resources')
        ->where('user_id', $userId)
        ->where('status', 'running')
        ->count();

    // Calculate the current monthly bill based on ACTIVE services (Run Rate)
    $monthlyBill = DB::table('provisioned_resources')
        ->join('subscription_plans', 'provisioned_resources.plan_id', '=', 'subscription_plans.id')
        ->where('provisioned_resources.user_id', $userId)
        ->where('provisioned_resources.status', 'running')
        ->sum('subscription_plans.monthly_price');

    // 2. Fetch ALL active STORAGE subscriptions for the user
    $activeStorage = DB::table('provisioned_resources')
        ->join('subscription_plans', 'provisioned_resources.plan_id', '=', 'subscription_plans.id')
        ->where('provisioned_resources.user_id', $userId)
        ->where('provisioned_resources.resource_type', 'storage')
        ->where('provisioned_resources.status', 'running')
        ->select('provisioned_resources.*', 'subscription_plans.storage_quota_gb')
        ->orderBy('provisioned_resources.rent_start_date', 'desc')
        ->get();

    $bucketsData = [];

    // 3. Loop through every bucket and weigh its contents
    foreach ($activeStorage as $storage) {
        $bucketName = $storage->ministack_resource_id;
        $totalGB = $storage->storage_quota_gb;
        $usedBytes = 0;

        try {
            $s3Client = Storage::disk('s3')->getClient();
            $objects = $s3Client->listObjectsV2(['Bucket' => $bucketName]);
            if (!empty($objects['Contents'])) {
                foreach ($objects['Contents'] as $object) {
                    $usedBytes += $object['Size'];
                }
            }
        } catch (\Exception $e) {} // Fail silently if MiniStack is off

        $usedGB = $usedBytes / (1024 * 1024 * 1024);
        
        if ($usedGB > 0 && $usedGB < 0.1) {
            $displaySize = round($usedBytes / (1024 * 1024), 2) . ' MB';
        } else {
            $displaySize = round($usedGB, 2) . ' GB';
        }

        $percentage = ($totalGB > 0) ? ($usedGB / $totalGB) * 100 : 0;
        
        // Determine progress bar color
        $colorClass = 'bg-blue-600';
        if ($percentage > 90) $colorClass = 'bg-red-600';
        elseif ($percentage > 75) $colorClass = 'bg-yellow-500';

        // Add to the buffet tray
        $bucketsData[] = [
            'name' => $bucketName,
            'usedGB' => $usedGB,
            'totalGB' => $totalGB,
            'displaySize' => $displaySize,
            'percentage' => min($percentage, 100),
            'available' => round(max($totalGB - $usedGB, 0), 2) . ' GB',
            'colorClass' => $colorClass
        ];
    }

    // 4. Fetch Storage Plans (menggunakan join ke iaas_services)
    $storagePlans = DB::table('subscription_plans')
        ->join('iaas_services', 'subscription_plans.service_id', '=', 'iaas_services.id')
        ->where('iaas_services.service_category', 'Storage')
        ->select('subscription_plans.*')
        ->get();

    // Ambil SEMUA kredensial aktif beserta nama resource-nya
    $userCredentials = DB::table('access_credentials')
        ->join('provisioned_resources', 'access_credentials.provisioned_id', '=', 'provisioned_resources.id')
        ->where('access_credentials.user_id', Auth::id())
        ->where('access_credentials.is_active', true)
        ->select('access_credentials.*', 'provisioned_resources.instance_name', 'provisioned_resources.resource_type')
        ->get();

    // Cari resource aktif milik user yang BELUM memiliki kredensial aktif (untuk dropdown menu)
    $availableResourcesForKey = DB::table('provisioned_resources')
        ->where('user_id', Auth::id())
        ->where('status', 'running')
        ->whereNotIn('id', function($query) {
            $query->select('provisioned_id')->from('access_credentials')->where('is_active', true);
        })
        ->get();

    // 5. Fetch EC2 Compute Plans (menggunakan join ke iaas_services)
    $computePlans = DB::table('subscription_plans')
        ->join('iaas_services', 'subscription_plans.service_id', '=', 'iaas_services.id')
        ->where('iaas_services.service_category', 'Compute')
        ->select('subscription_plans.*')
        ->get();

    $activeInstances = DB::table('provisioned_resources')
        ->where('user_id', $userId)
        ->where('resource_type', 'compute')
        ->whereIn('status', ['running', 'stopped'])
        ->orderBy('created_at', 'desc')
        ->get();

    $instancesData = [];
    foreach ($activeInstances as $resource) {
        $config = json_decode($resource->configuration, true);
        $instancesData[] = [
            'instance_id'   => $resource->ministack_resource_id,
            'instance_name' => $resource->instance_name,
            'instance_type' => $config['instance_type'] ?? 'unknown',
            'status'        => $resource->status,
            'hourly_cost'   => $resource->hourly_cost,
            'launched_at'   => $resource->rent_start_date,
        ];
    }

    $recentActivities = DB::table('activity_logs')
        ->where('user_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

    return view('dashboard', [
        'totalResources' => $totalResources,
        'activeServices' => $activeServices,
        'monthlyBill'    => $monthlyBill,
        'bucketsData'    => $bucketsData,
        'storagePlans'   => $storagePlans,
        'computePlans'   => $computePlans,
        'instancesData'  => $instancesData, // Menghapus typo koma ganda yang ada sebelumnya
        'userCredentials' => $userCredentials, // Ubah dari userCredential menjadi ini
        'availableResourcesForKey' => $availableResourcesForKey, // Tambahkan ini
        'recentActivities' => $recentActivities
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // --- Firas's S3 Routes ---
    Route::post('/s3/create-bucket', [S3Controller::class, 'createBucket'])->name('s3.createBucket');
    Route::post('/s3/upload-object', [S3Controller::class, 'uploadObject'])->name('s3.uploadObject');
    Route::post('/s3/view-files', [S3Controller::class, 'viewFiles'])->name('s3.viewFiles');
    Route::get('/s3/download/{bucket}/{key}', [S3Controller::class, 'downloadFile'])->name('s3.downloadFile');
    Route::post('/s3/delete-file', [S3Controller::class, 'deleteFile'])->name('s3.deleteFile');
    Route::post('/s3/delete-bucket', [S3Controller::class, 'deleteBucket'])->name('s3.deleteBucket');

    // --- EC2 Routes ---
    Route::post('/ec2/launch', [Ec2Controller::class, 'launchInstance'])->name('ec2.launch');
    Route::post('/ec2/list', [Ec2Controller::class, 'listInstances'])->name('ec2.list');
    Route::post('/ec2/stop', [Ec2Controller::class, 'stopInstance'])->name('ec2.stop');
    Route::post('/ec2/start', [Ec2Controller::class, 'startInstance'])->name('ec2.start');
    Route::post('/ec2/terminate', [Ec2Controller::class, 'terminateInstance'])->name('ec2.terminate');

    Route::post('/s3/generate-credentials', [S3Controller::class, 'generateCredentials'])->name('s3.generateCredentials');
});

require __DIR__.'/auth.php';