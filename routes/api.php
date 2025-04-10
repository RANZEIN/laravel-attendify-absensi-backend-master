<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\QrAbsenController;
use App\Http\Controllers\TimeOffController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Login
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Company
    Route::get('/company', [CompanyController::class, 'show']);

    // Attendance
    Route::post('/checkin', [AttendanceController::class, 'checkin']);
    Route::post('/checkout', [AttendanceController::class, 'checkout']);
    Route::get('/is-checkin', [AttendanceController::class, 'isCheckedin']);
    Route::get('/api-attendances', [AttendanceController::class, 'index']);

    // User
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/update-fcm-token', [AuthController::class, 'updateFcmToken']);
    Route::get('/api-user/{id}', [UserController::class, 'getUserId']);
    Route::post('/api-user/edit', [UserController::class, 'updateProfile']);

    // Permissions
    Route::apiResource('/api-permissions', PermissionController::class);

    // Notes
    Route::apiResource('/api-notes', NoteController::class);

    // QR
    Route::post('/check-qr', [QrAbsenController::class, 'checkQR']);

    // Time Off routes
    Route::get('/time-off', [TimeOffController::class, 'apiGetTimeOffs']);
    Route::post('/time-off', [TimeOffController::class, 'apiCreateTimeOff']);
    Route::get('/time-off/{id}', [TimeOffController::class, 'apiGetTimeOff']);
    Route::put('/time-off/{id}', [TimeOffController::class, 'apiUpdateTimeOff']);
    Route::delete('/time-off/{id}', [TimeOffController::class, 'apiDestroyTimeOff']);
    Route::get('/leave-balance', [TimeOffController::class, 'apiGetLeaveBalance']);

    // Optional: Keep the status filter route if needed
    Route::get('/time-off/status/{status}', [TimeOffController::class, 'getByStatus']);
});
