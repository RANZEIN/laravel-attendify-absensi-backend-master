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
use App\Http\Controllers\Api\BroadcastControllerApi;

// Auth Routes
Route::post('auth/login', [AuthControllerApi::class, 'login']);

// Authenticated Routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('auth/logout', [AuthControllerApi::class, 'logout']);
    Route::post('auth/update-profile', [AuthControllerApi::class, 'updateProfile']);
    Route::post('auth/update-fcm-token', [AuthControllerApi::class, 'updateFcmToken']);

    // Company
    Route::get('auth/company', [CompanyControllerApi::class, 'show']);

    // Attendance
    Route::post('auth/attendance/checkin', [AttendanceControllerApi::class, 'checkin']);
    Route::post('auth/attendance/checkout', [AttendanceControllerApi::class, 'checkout']);
    Route::get('auth/attendance/status', [AttendanceControllerApi::class, 'isCheckedin']);
    Route::get('auth/attendance', [AttendanceControllerApi::class, 'index']);
    Route::post('auth/attendance/working-days', [AttendanceControllerApi::class, 'getWorkingDays']);

    // User
    Route::get('auth/users', [UserControllerApi::class, 'index']);
    Route::get('auth/users/{id}', [UserControllerApi::class, 'show']);
    Route::post('auth/users', [UserControllerApi::class, 'store']);
    Route::put('auth/users/{id}', [UserControllerApi::class, 'update']);
    Route::delete('auth/users/{id}', [UserControllerApi::class, 'destroy']);
    Route::post('auth/users/update-profile', [UserControllerApi::class, 'updateProfile']);

    // Permissions
    Route::post('auth/permissions', [PermissionControllerApi::class, 'store']);

    // Notes
    Route::get('auth/notes', [NoteControllerApi::class, 'index']);
    Route::post('auth/notes', [NoteControllerApi::class, 'store']);

    // QR
    Route::post('auth/qr-check', [QrAbsenControllerApi::class, 'checkQR']);

    // Time Off
    Route::get('auth/time-offs', [TimeOffControllerApi::class, 'index']);
    Route::post('auth/time-offs', [TimeOffControllerApi::class, 'store']);
    Route::put('auth/time-offs/{id}', [TimeOffControllerApi::class, 'update']);
    Route::get('auth/time-offs/{id}', [TimeOffControllerApi::class, 'show']);
    Route::delete('auth/time-offs/{id}', [TimeOffControllerApi::class, 'destroy']);
    Route::get('auth/user/{userId}/time-offs', [TimeOffControllerApi::class, 'getUserTimeOffs']);
    Route::post('auth/calculate-working-days', [TimeOffControllerApi::class, 'calculateWorkingDays']);
    Route::get('auth/time-offs/status/{status}', [TimeOffControllerApi::class, 'getByStatus']);

    // Holidays
    Route::get('auth/holidays/monthly', [HolidayControllerApi::class, 'getMonthlyHolidays']);
    Route::get('auth/holidays/range', [HolidayControllerApi::class, 'getHolidaysInRange']);
    Route::get('auth/holidays/check', [HolidayControllerApi::class, 'checkHoliday']);
    Route::post('auth/holidays/store', [HolidayControllerApi::class, 'store']);
    Route::put('auth/holidays/update/{id}', [HolidayControllerApi::class, 'update']);
    Route::delete('auth/holidays/destroy/{id}', [HolidayControllerApi::class, 'destroy']);
    Route::post('auth/holidays/toggle', [HolidayControllerApi::class, 'toggleHoliday']);

    // Broadcast
    Route::get('auth/broadcasts', [BroadcastControllerApi::class, 'getBroadcasts']);
    Route::get('auth/broadcasts/{id}', [BroadcastControllerApi::class, 'getBroadcastDetail']);
    Route::post('auth/broadcasts/mark-as-read', [BroadcastControllerApi::class, 'markAsRead']);
    Route::post('auth/broadcasts/register-device-token', [BroadcastControllerApi::class, 'registerDeviceToken']);
    Route::get('auth/broadcasts/unread-count', [BroadcastControllerApi::class, 'getUnreadCount']);
    Route::get('auth/broadcasts/download/{id}', [BroadcastControllerApi::class, 'downloadFile']);
});
