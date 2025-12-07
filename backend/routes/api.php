<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\AsnProfileController;

Route::prefix('v1')->group(function () {
    // --- PUBLIC ROUTES (Bisa diakses tanpa login) ---
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']); // Opsional untuk testing

    // --- PROTECTED ROUTES (Harus Login / Punya Token) ---
    Route::middleware('auth:sanctum')->group(function () {

        // Auth User Info
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/user', [AuthController::class, 'me']);

        //profile
        Route::get('/asn', [AsnProfileController::class, 'index']);

        // doc
        Route::post('/documents/upload', [DocumentController::class, 'uploadAndVerify']);
    });
});
