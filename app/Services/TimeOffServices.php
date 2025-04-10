<?php

namespace App\Services;

use App\Models\TimeOff;
use App\Models\Attendance;
use Carbon\Carbon;

class TimeOffService
{
    /**
     * Create attendance records for sick leave
     *
     * @param int $userId
     * @param string $startDate
     * @param string $endDate
     * @param string $reason
     * @return array
     */
    public static function createAttendanceForSickLeave($userId, $startDate, $endDate, $reason)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $days = $end->diffInDays($start) + 1;

        $createdRecords = [];
        $currentDate = $start->copy();

        for ($i = 0; $i < $days; $i++) {
            // Skip weekends if needed
            if ($currentDate->isWeekend()) {
                $currentDate->addDay();
                continue;
            }

            // Check if attendance record already exists for this date
            $existingAttendance = Attendance::where('user_id', $userId)
                ->where('date', $currentDate->format('Y-m-d'))
                ->first();

            if (!$existingAttendance) {
                // Create attendance record with sick leave status
                $attendance = new Attendance();
                $attendance->user_id = $userId;
                $attendance->date = $currentDate->format('Y-m-d');
                $attendance->time_in = '09:00:00'; // Default check-in time
                $attendance->time_out = '17:00:00'; // Default check-out time
                $attendance->latlon_in = '0,0'; // Default location
                $attendance->latlon_out = '0,0'; // Default location
                $attendance->status = 'sick_leave'; // Special status for sick leave
                $attendance->reason = $reason;
                $attendance->save();

                $createdRecords[] = $attendance;
            }

            $currentDate->addDay();
        }

        return $createdRecords;
    }
}
