<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
Route::get('/user', function (Request $request) {
})->middleware('auth:sanctum');
Route::post('/user_register',[AuthController::class,'user_register']);
Route::post('/user_login',[AuthController::class,'user_login']);
Route::post('/verifyOtp',[AuthController::class,'verifyOtp']);
Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::post('/user_logout', [AuthController::class, 'user_logout']);
    Route::delete('/delete-account', [AuthController::class, 'delete_account']);
});