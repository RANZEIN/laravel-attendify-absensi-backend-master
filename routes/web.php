<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QrAbsenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TimeOffController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\HolidayController;

Route::get('/', function () {
    return view('pages.auth.auth-login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('home', [DashboardController::class, 'index'])->name('home');

    Route::resource('users', UserController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('qr_absens', QrAbsenController::class);
    Route::get('/qr-absens/{id}/download', [QrAbsenController::class, 'downloadPDF'])->name('qr_absens.download');
    Route::resource('time_offs', TimeOffController::class);
    Route::put('/time_offs/{id}/approve', [TimeOffController::class, 'approve'])->name('time_offs.approve');
    Route::put('/time_offs/{id}/reject', [TimeOffController::class, 'reject'])->name('time_offs.reject');

    Route::resource('broadcasts', BroadcastController::class);
    Route::post('broadcasts/{broadcast}/send', [BroadcastController::class, 'send'])->name('broadcasts.send');

    // Holiday management routes
    Route::get('/holidays', [HolidayController::class, 'index'])->name('holidays.index');
    Route::get('/holidays/calendar', [HolidayController::class, 'calendar'])->name('holidays.calendar');
    Route::get('/holidays/create', [HolidayController::class, 'create'])->name('holidays.create');
    Route::post('/holidays', [HolidayController::class, 'store'])->name('holidays.store');
    Route::get('/holidays/{holiday}/edit', [HolidayController::class, 'edit'])->name('holidays.edit');
    Route::put('/holidays/{holiday}', [HolidayController::class, 'update'])->name('holidays.update');
    Route::delete('/holidays/{holiday}', [HolidayController::class, 'destroy'])->name('holidays.destroy');

    // Special holiday management routes
    Route::post('/holidays/generate-weekends', [HolidayController::class, 'generateWeekendHolidays'])->name('holidays.generate-weekends');
    Route::post('/holidays/import-national', [HolidayController::class, 'importNationalHolidays'])->name('holidays.import-national');
    Route::post('/holidays/toggle', [HolidayController::class, 'toggleHoliday'])->name('holidays.toggle');
});

