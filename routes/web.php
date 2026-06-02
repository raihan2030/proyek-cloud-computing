<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\S3Controller; // Add this line
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// INI ROUTE ADMIN MILIK TIM-MU YANG DIKEMBALIKAN:
Route::get('/admin', function () {
    return view('admin');
});

Route::get('/dashboard', function () {
    // 1. Check for the NEWEST active subscription
    $activeSubscription = DB::table('user_subscriptions')
        ->where('user_id', Auth::id())
        ->where('subscription_status', 'active')
        ->whereNotNull('ministack_bucket_name')
        ->latest('start_date') // Ensures we grab the most recent one!
        ->first();

    $userBucket = $activeSubscription ? $activeSubscription->ministack_bucket_name : null;
    $totalGB = $activeSubscription ? $activeSubscription->remaining_storage_quota_gb : 5;
    $usedBytes = 0;

    // 2. Weigh the files
    if ($userBucket) {
        try {
            $s3Client = Storage::disk('s3')->getClient();
            $objects = $s3Client->listObjectsV2(['Bucket' => $userBucket]);
            if (!empty($objects['Contents'])) {
                foreach ($objects['Contents'] as $object) {
                    $usedBytes += $object['Size'];
                }
            }
        } catch (\Exception $e) {}
    }

    $usedGB = $usedBytes / (1024 * 1024 * 1024);
    
    // 3. User-Friendly Formatting (Show MB if less than 0.1 GB)
    if ($usedGB > 0 && $usedGB < 0.1) {
        $displaySize = round($usedBytes / (1024 * 1024), 2) . ' MB';
    } else {
        $displaySize = round($usedGB, 2) . ' GB';
    }

    $storagePlans = DB::table('subscription_plans')->where('service_type', 'iaas')->get();

    return view('dashboard', [
        'usedGB' => $usedGB,
        'displaySize' => $displaySize, // Pass the friendly string to the view
        'totalGB' => $totalGB,
        'percentage' => ($totalGB > 0) ? ($usedGB / $totalGB) * 100 : 0,
        'storagePlans' => $storagePlans
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