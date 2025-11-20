<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplaintController;

Route::get('/user', function (Request $request) {
})->middleware('auth:sanctum');
Route::post('/user_register',[AuthController::class,'user_register']);
Route::post('/user_login',[AuthController::class,'user_login']);
Route::post('/verifyOtp',[AuthController::class,'verifyOtp']);
Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::post('/user_logout', [AuthController::class, 'user_logout']);
    Route::delete('/delete-account', [AuthController::class, 'delete_account']);
    // for all users


});
Route::post('/storeComplaint',[ComplaintController::class, 'store'])->middleware('auth:sanctum');



// Route::post('/register',[TestController::class, 'register']);

// Route::get('/getByConcerned/{concerned}',[ComplaintController::class, 'getByConcerned']);

Route::middleware(['auth:sanctum', 'role:admin|employee'])->group(function () {

Route::get('/countAllComplaints',[ComplaintController::class, 'countAllComplaints']);
Route::get('/countPendingComplaints',[ComplaintController::class, 'countPendingComplaints']);
Route::get('/countNewComplaints',[ComplaintController::class, 'countNewComplaints']);
Route::post('/updateComplaint/{id}', [ComplaintController::class, 'updateComplaint']);

});
Route::middleware(['auth:sanctum', 'role:employee'])->group(function () {
Route::get('/complaintsForDepartment',[ComplaintController::class, 'complaintsForDepartment']);

    });
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
Route::get('/showAllComplaints',[ComplaintController::class, 'showAllComplaints']);

    });
