<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Middleware\AuthenticateApiKey;

Route::middleware([AuthenticateApiKey::class])->group(function () {
    // S3 Storage API routes
    Route::get('/s3/files', [ApiController::class, 's3ListFiles']);
    Route::post('/s3/upload', [ApiController::class, 's3UploadFile']);
    Route::get('/s3/download', [ApiController::class, 's3DownloadFile']);
    Route::delete('/s3/delete', [ApiController::class, 's3DeleteFile']);

    // EC2 Compute API routes
    Route::get('/ec2/status', [ApiController::class, 'ec2Status']);
    Route::post('/ec2/start', [ApiController::class, 'ec2Start']);
    Route::post('/ec2/stop', [ApiController::class, 'ec2Stop']);
});
