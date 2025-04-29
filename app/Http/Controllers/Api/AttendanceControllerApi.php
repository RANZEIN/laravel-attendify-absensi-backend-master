<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Add this import

class AttendanceControllerApi extends Controller
{
    //checkin
    public function checkin(Request $request)
    {
        //validate lat and long
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'time' => 'nullable',
            'timeZoneOffset' => 'nullable',
        ]);

        // Determine the time to use
        if ($request->has('time') && $request->has('timeZoneOffset')) {
            // Use the client-provided time with time zone offset
            $clientTime = $request->time;
            $timeZoneOffset = (int)$request->timeZoneOffset;

            // Create a Carbon instance from the client time
            $timeFormat = strlen($clientTime) <= 5 ? 'H:i' : 'H:i:s';
            $now = Carbon::createFromFormat($timeFormat, $clientTime);

            // Set the date to today
            $now->setDate(date('Y'), date('m'), date('d'));

            // Convert to UTC for storage
            $now->subHours($timeZoneOffset);

            $date = $now->format('Y-m-d');
            $time = $now->format('H:i:s');

            // Log for debugging
            Log::info("Client check-in time: {$clientTime}, Offset: {$timeZoneOffset}, Stored time: {$time}");
        } else {
            // Fallback to server time
            $date = date('Y-m-d');
            $time = date('H:i:s');
        }

        //save new attendance
        $attendance = new Attendance;
        $attendance->user_id = $request->user()->id;
        $attendance->date = $date;
        $attendance->time_in = $time;
        $attendance->latlon_in = $request->latitude . ',' . $request->longitude;
        $attendance->save();

        return response([
            'message' => 'Checkin success',
            'attendance' => $attendance,
            'debug_info' => [
                'client_time' => $request->time ?? 'not provided',
                'timezone_offset' => $request->timeZoneOffset ?? 'not provided',
                'server_time' => date('H:i:s'),
                'stored_time' => $time,
            ]
        ], 200);
    }

    //checkout
    public function checkout(Request $request)
    {
        //validate lat and long
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'time' => 'nullable',
            'timeZoneOffset' => 'nullable',
        ]);

        // Determine the time to use
        if ($request->has('time') && $request->has('timeZoneOffset')) {
            // Use the client-provided time with time zone offset
            $clientTime = $request->time;
            $timeZoneOffset = (int)$request->timeZoneOffset;

            // Create a Carbon instance from the client time
            $timeFormat = strlen($clientTime) <= 5 ? 'H:i' : 'H:i:s';
            $now = Carbon::createFromFormat($timeFormat, $clientTime);

            // Set the date to today
            $now->setDate(date('Y'), date('m'), date('d'));

            // Convert to UTC for storage
            $now->subHours($timeZoneOffset);

            $date = $now->format('Y-m-d');
            $time = $now->format('H:i:s');

            // Log for debugging
            Log::info("Client check-out time: {$clientTime}, Offset: {$timeZoneOffset}, Stored time: {$time}");
        } else {
            // Fallback to server time
            $date = date('Y-m-d');
            $time = date('H:i:s');
        }

        //get today attendance
        $attendance = Attendance::where('user_id', $request->user()->id)
            ->where('date', $date)
            ->first();

        //check if attendance not found
        if (!$attendance) {
            return response(['message' => 'Checkin first'], 400);
        }

        //save checkout
        $attendance->time_out = $time;
        $attendance->latlon_out = $request->latitude . ',' . $request->longitude;
        $attendance->save();

        return response([
            'message' => 'Checkout success',
            'attendance' => $attendance,
            'debug_info' => [
                'client_time' => $request->time ?? 'not provided',
                'timezone_offset' => $request->timeZoneOffset ?? 'not provided',
                'server_time' => date('H:i:s'),
                'stored_time' => $time,
            ]
        ], 200);
    }

    //check is checkedin
    public function isCheckedin(Request $request)
    {
        //get today attendance
        $attendance = Attendance::where('user_id', $request->user()->id)
            ->where('date', date('Y-m-d'))
            ->first();

        $isCheckout = $attendance ? $attendance->time_out : false;

        return response([
            'checkedin' => $attendance ? true : false,
            'checkedout' => $isCheckout ? true : false,
        ], 200);
    }

    //index
    public function index(Request $request)
    {
        $date = $request->input('date');
        $timeZoneOffset = $request->input('timeZoneOffset', 0); // Default to 0 if not provided

        $currentUser = $request->user();

        $query = Attendance::where('user_id', $currentUser->id);

        if ($date) {
            $query->where('date', $date);
        }

        $attendance = $query->get();

        // Convert times to client's time zone for display
        $formattedAttendance = $attendance->map(function ($item) use ($timeZoneOffset) {
            // Clone the item to avoid modifying the original
            $result = $item->toArray();

            // Convert time_in to client's time zone if it exists
            if (!empty($item->time_in)) {
                $timeIn = Carbon::createFromFormat('H:i:s', $item->time_in);
                $timeIn->addHours((int)$timeZoneOffset);
                $result['time_in'] = $timeIn->format('H:i:s');
            }

            // Convert time_out to client's time zone if it exists
            if (!empty($item->time_out)) {
                $timeOut = Carbon::createFromFormat('H:i:s', $item->time_out);
                $timeOut->addHours((int)$timeZoneOffset);
                $result['time_out'] = $timeOut->format('H:i:s');
            }

            return $result;
        });

        return response([
            'message' => 'Success',
            'data' => $formattedAttendance,
            'debug_info' => [
                'timezone_offset' => $timeZoneOffset,
                'server_time' => date('H:i:s'),
            ]
        ], 200);
    }
}
