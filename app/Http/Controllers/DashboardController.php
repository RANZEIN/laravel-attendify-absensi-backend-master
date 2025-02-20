<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Pastikan ada data untuk menghindari error
        $totalAttendances = Attendance::count();
        $onTimeAttendance = Attendance::whereTime('time_in', '<=', '07:45:00')->count();
        $lateAttendance = Attendance::whereTime('time_in', '>', '07:45:00')->count();
        $totalAbsence = User::count() - $totalAttendances;

        // Data untuk grafik
        $months = Attendance::selectRaw('MONTHNAME(date) as month')
            ->groupBy('month')
            ->pluck('month');

        $onTimeData = Attendance::whereTime('time_in', '<=', '07:45:00')
            ->selectRaw('MONTHNAME(date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total');

        $lateData = Attendance::whereTime('time_in', '>', '07:45:00')
            ->selectRaw('MONTHNAME(date) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total');

        $attendanceStatusData = [
            'on_time' => $onTimeAttendance,
            'late' => $lateAttendance,
            'absent' => $totalAbsence
        ];

        // Ambil data absensi terbaru
        $attendances = Attendance::with('user')->orderBy('date', 'desc')->take(10)->get();

        return view('pages.dashboard')
        ->with('totalAttendances', $totalAttendances)
        ->with('onTimeAttendance', $onTimeAttendance)
        ->with('lateAttendance', $lateAttendance)
        ->with('totalAbsence', $totalAbsence)
        ->with('months', $months)
        ->with('onTimeData', $onTimeData)
        ->with('lateData', $lateData)
        ->with('attendanceStatusData', $attendanceStatusData)
        ->with('attendances', $attendances);
    }
}
