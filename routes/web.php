<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\S3Controller; // Add this line
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

Route::get('/', function () {
    return view('welcome');
});

// INI ROUTE ADMIN MILIK TIM-MU YANG DIKEMBALIKAN:
Route::get('/admin', function () {
    return view('admin');
});

Route::get('/dashboard', function () {
    
    $userId = Auth::id();

    // 1. Gather Top Metrics Data
    $totalResources = DB::table('provisioned_resources')
        ->where('user_id', $userId)
        ->count();

    $activeServices = DB::table('user_subscriptions')
        ->where('user_id', $userId)
        ->where('subscription_status', 'active')
        ->count();

    // Calculate the current monthly bill based on ACTIVE services (Run Rate)
    $monthlyBill = DB::table('user_subscriptions')
        ->join('subscription_plans', 'user_subscriptions.plan_id', '=', 'subscription_plans.id')
        ->where('user_subscriptions.user_id', $userId)
        ->where('user_subscriptions.subscription_status', 'active')
        ->sum('subscription_plans.monthly_price');

    // 2. Fetch ALL active subscriptions for the user
    $activeSubscriptions = DB::table('user_subscriptions')
        ->where('user_id', Auth::id())
        ->where('subscription_status', 'active')
        ->whereNotNull('ministack_bucket_name')
        ->orderBy('start_date', 'desc')
        ->get();

    $bucketsData = [];

    // 3. Loop through every bucket and weigh its contents
    foreach ($activeSubscriptions as $sub) {
        $bucketName = $sub->ministack_bucket_name;
        $totalGB = $sub->remaining_storage_quota_gb;
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

    $storagePlans = DB::table('subscription_plans')->where('service_type', 'iaas')->get();

    // 4. Return view with all dynamic values bundled
    return view('dashboard', [
        'totalResources' => $totalResources,
        'activeServices' => $activeServices,
        'monthlyBill'    => $monthlyBill,
        'bucketsData'    => $bucketsData,
        'storagePlans'   => $storagePlans
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
});

require __DIR__.'/auth.php';