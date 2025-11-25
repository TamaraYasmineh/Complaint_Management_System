<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ControlController;
use App\Http\Controllers\ComplaintController;

Route::get('/user', function (Request $request) {})->middleware('auth:sanctum');
Route::post('/user_register', [AuthController::class, 'user_register']);
//Route::post('/citizen_login',[AuthController::class,'user_login']);
Route::post('/citizen_login', [AuthController::class, 'citizen_login']);
Route::post('/admin_login', [AuthController::class, 'admin_login']);
Route::post('/employee_login', [AuthController::class, 'employee_login']);
Route::post('/verifyOtp', [AuthController::class, 'verifyOtp']);
Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::post('/user_logout', [AuthController::class, 'user_logout']);
    Route::delete('/delete-account', [AuthController::class, 'delete_account']);
    // for all users

});
Route::post('/storeComplaint', [ComplaintController::class, 'store'])->middleware('auth:sanctum');



// Route::get('/getByConcerned/{concerned}',[ComplaintController::class, 'getByConcerned']);

Route::middleware(['auth:sanctum', 'role:admin|employee'])->group(function () {

    Route::get('/countAllComplaints', [ComplaintController::class, 'countAllComplaints']);
    Route::get('/countPendingComplaints', [ComplaintController::class, 'countPendingComplaints']);
    Route::get('/countNewComplaints', [ComplaintController::class, 'countNewComplaints']);
    Route::post('/updateComplaint/{id}', [ComplaintController::class, 'updateComplaint']);

});
Route::middleware(['auth:sanctum', 'role:employee'])->group(function () {
    Route::get('/complaintsForDepartment', [ComplaintController::class, 'complaintsForDepartment']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/showAllComplaints', [ComplaintController::class, 'showAllComplaints']);
    Route::get('/showUsers', [ControlController::class, 'showUsers']);
    Route::get('/upgradeUser/{id}', [ControlController::class, 'upgradeUser']);
    Route::get('/downgradeUser/{id}', [ControlController::class, 'downgradeUser']);
});
Route::middleware(['auth:sanctum', 'role:citizen'])->group(function () {
});
