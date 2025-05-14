<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceControllerApi extends Controller
{
    /**
     * Handle user check-in
     */
    public function checkin(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'timeZoneOffset' => 'nullable|integer',
        ]);

        $user = $request->user();
        $date = date('Y-m-d');
        $timeZoneOffset = (int)($request->timeZoneOffset ?? 0);
        $clientNow = Carbon::now()->addHours($timeZoneOffset);
        $clientTime = $clientNow->format('H:i:s');

        Log::info("User {$user->id} check-in: Time {$clientTime}, Offset: {$timeZoneOffset}");

        // Cek hari libur
        if (Holiday::isHoliday($date)) {
            return response([
                'message' => 'Hari ini libur, tidak perlu absen.',
                'is_holiday' => true
            ], 200);
        }

        // Cek apakah sudah pernah checkin
        $existing = Attendance::where('user_id', $user->id)->where('date', $date)->first();
        if ($existing) {
            return response([
                'message' => 'Sudah melakukan check-in hari ini.',
                'attendance' => $existing
            ], 200);
        }

        // Simpan data check-in
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'date' => $date,
            'time_in' => $clientTime,
            'latlon_in' => $request->latitude . ',' . $request->longitude,
        ]);

        return response([
            'message' => 'Check-in berhasil',
            'attendance' => $attendance,
            'debug_info' => [
                'client_time' => $clientTime,
                'server_time' => date('H:i:s'),
                'offset' => $timeZoneOffset,
            ]
        ], 200);
    }

    /**
     * Handle user check-out
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'timeZoneOffset' => 'nullable|integer',
        ]);

        $user = $request->user();
        $date = date('Y-m-d');
        $timeZoneOffset = (int)($request->timeZoneOffset ?? 0);
        $clientNow = Carbon::now()->addHours($timeZoneOffset);
        $clientTime = $clientNow->format('H:i:s');

        Log::info("User {$user->id} check-out: Time {$clientTime}, Offset: {$timeZoneOffset}");

        if (Holiday::isHoliday($date)) {
            return response([
                'message' => 'Hari ini libur, tidak perlu absen.',
                'is_holiday' => true
            ], 200);
        }

        $attendance = Attendance::where('user_id', $user->id)->where('date', $date)->first();

        if (!$attendance) {
            return response(['message' => 'Silakan check-in terlebih dahulu.'], 400);
        }

        $attendance->update([
            'time_out' => $clientTime,
            'latlon_out' => $request->latitude . ',' . $request->longitude,
        ]);

        return response([
            'message' => 'Check-out berhasil',
            'attendance' => $attendance,
            'debug_info' => [
                'client_time' => $clientTime,
                'server_time' => date('H:i:s'),
                'offset' => $timeZoneOffset,
            ]
        ], 200);
    }

    /**
     * Check attendance status
     */
    public function isCheckedin(Request $request)
    {
        $date = date('Y-m-d');
        $user = $request->user();

        if (Holiday::isHoliday($date)) {
            $holiday = Holiday::whereDate('date', $date)->first();
            return response([
                'checkedin' => false,
                'checkedout' => false,
                'is_holiday' => true,
                'holiday_name' => $holiday->name ?? 'Libur',
                'holiday_type' => $holiday->type ?? 'unknown',
            ], 200);
        }

        $attendance = Attendance::where('user_id', $user->id)->where('date', $date)->first();

        return response([
            'checkedin' => (bool) $attendance,
            'checkedout' => $attendance && $attendance->time_out ? true : false,
            'is_holiday' => false,
        ], 200);
    }

    /**
     * Attendance index with optional date filter
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $timeZoneOffset = (int)($request->input('timeZoneOffset', 0));
        $date = $request->input('date');

        $attendances = Attendance::where('user_id', $user->id)
            ->when($date, fn($query) => $query->where('date', $date))
            ->get()
            ->map(function ($item) use ($timeZoneOffset) {
                $data = $item->toArray();

                if ($item->time_in) {
                    $data['time_in'] = Carbon::createFromFormat('H:i:s', $item->time_in)->addHours($timeZoneOffset)->format('H:i:s');
                }

                if ($item->time_out) {
                    $data['time_out'] = Carbon::createFromFormat('H:i:s', $item->time_out)->addHours($timeZoneOffset)->format('H:i:s');
                }

                return $data;
            });

        return response([
            'message' => 'Data absensi berhasil diambil',
            'data' => $attendances,
            'debug_info' => [
                'timezone_offset' => $timeZoneOffset,
                'server_time' => date('H:i:s'),
            ]
        ], 200);
    }

    /**
     * Get working days (excluding holidays)
     */
    public function getWorkingDays(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = [];

        while ($start <= $end) {
            if (!Holiday::isHoliday($start->format('Y-m-d'))) {
                $days[] = $start->format('Y-m-d');
            }
            $start->addDay();
        }

        return response([
            'status' => 'Success',
            'message' => 'Hari kerja berhasil diambil',
            'working_days' => $days,
            'count' => count($days)
        ], 200);
    }
}
