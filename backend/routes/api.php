<?php

use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\AsnProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    //profile
    Route::get('/asn', [AsnProfileController::class, 'index']);
    // doc
    Route::post('/documents/upload', [DocumentController::class, 'uploadAndVerify']);
});