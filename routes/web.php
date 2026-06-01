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
    // 1. Find if the user currently has an active bucket rented
    $userBucket = DB::table('provisioned_resources')
        ->where('user_id', Auth::id())
        ->where('resource_type', 'storage')
        ->where('status', 'running')
        ->value('ministack_resource_id');

    $usedGB = 0;
    $totalGB = 5; // Default quota based on your database design

    // 2. If they have a bucket, weigh the files inside it
    if ($userBucket) {
        try {
            $s3Client = Storage::disk('s3')->getClient();
            $objects = $s3Client->listObjectsV2(['Bucket' => $userBucket]);
            
            $currentBytes = 0;
            if (!empty($objects['Contents'])) {
                foreach ($objects['Contents'] as $object) {
                    $currentBytes += $object['Size'];
                }
            }
            
            // Convert raw bytes into Gigabytes
            $usedGB = $currentBytes / (1024 * 1024 * 1024);
        } catch (\Exception $e) {
            // Fails silently if MiniStack is offline, defaulting to 0 GB
        }
    }

    // 3. Deliver the prepared data tray to the Blade view
    return view('dashboard', [
        'usedGB' => round($usedGB, 4), // Rounded for cleaner display
        'totalGB' => $totalGB,
        'percentage' => ($usedGB / $totalGB) * 100
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