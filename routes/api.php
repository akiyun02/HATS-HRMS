<?php

use App\Http\Controllers\Api\BiometricController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/biometric/attendance', [BiometricController::class, 'store']);
