<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthControllerApi;
use App\Http\Controllers\Api\CompanyControllerApi;
use App\Http\Controllers\Api\AttendanceControllerApi;
use App\Http\Controllers\Api\PermissionControllerApi;
use App\Http\Controllers\Api\NoteControllerApi;
use App\Http\Controllers\Api\UserControllerApi;
use App\Http\Controllers\Api\QrAbsenControllerApi;
use App\Http\Controllers\Api\TimeOffControllerApi;
use App\Http\Controllers\Api\HolidayControllerApi;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Login
Route::post('/login', [AuthControllerApi::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Logout
    Route::post('/logout', [AuthControllerApi::class, 'logout']);

    // Company
    Route::get('/company', [CompanyControllerApi::class, 'show']);

    // Attendance
    Route::post('/checkin', [AttendanceControllerApi::class, 'checkin']);
    Route::post('/checkout', [AttendanceControllerApi::class, 'checkout']);
    Route::get('/is-checkin', [AttendanceControllerApi::class, 'isCheckedin']);
    Route::get('/api-attendances', [AttendanceControllerApi::class, 'index']);

    // User
    Route::post('/update-profile', [AuthControllerApi::class, 'updateProfile']);
    Route::post('/update-fcm-token', [AuthControllerApi::class, 'updateFcmToken']);
    Route::get('/api-user/{id}', [UserControllerApi::class, 'getUserId']);
    Route::post('/api-user/edit', [UserControllerApi::class, 'updateProfile']);

    // Permissions
    Route::apiResource('/api-permissions', PermissionControllerApi::class);

    // Notes
    Route::apiResource('/api-notes', NoteControllerApi::class);

    // QR
    Route::post('/check-qr', [QrAbsenControllerApi::class, 'checkQR']);

    // Time Off routes
    Route::get('/time-off', [TimeOffControllerApi::class, 'apiGetTimeOffs']);
    Route::post('/time-off', [TimeOffControllerApi::class, 'apiCreateTimeOff']);
    Route::get('/time-off/{id}', [TimeOffControllerApi::class, 'apiGetTimeOff']);
    Route::put('/time-off/{id}', [TimeOffControllerApi::class, 'apiUpdateTimeOff']);
    Route::delete('/time-off/{id}', [TimeOffControllerApi::class, 'apiDestroyTimeOff']);
    Route::get('/leave-balance', [TimeOffControllerApi::class, 'apiGetLeaveBalance']);

    // Optional: Keep the status filter route if needed
    Route::get('/time-off/status/{status}', [TimeOffControllerApi::class, 'getByStatus']);

    Route::middleware('auth:sanctum')->group(function () {
        // Holiday API routes
        Route::get('/holidays/monthly', [HolidayControllerApi::class, 'getMonthlyHolidays']);
        Route::get('/holidays/range', [HolidayControllerApi::class, 'getHolidaysInRange']);
        Route::get('/holidays/check', [HolidayControllerApi::class, 'checkHoliday']);
        Route::post('/holidays', [HolidayControllerApi::class, 'store']);
        Route::put('/holidays/{id}', [HolidayControllerApi::class, 'update']);
        Route::delete('/holidays/{id}', [HolidayControllerApi::class, 'destroy']);
        Route::post('/holidays/toggle', [HolidayControllerApi::class, 'toggleHoliday']);

        // Working days calculation
        Route::get('/working-days', [App\Http\Controllers\Api\AttendanceControllerApi::class, 'getWorkingDays']);
        Route::post('/time-off/calculate-days', [App\Http\Controllers\Api\TimeOffControllerApi::class, 'calculateWorkingDays']);
    });

});
