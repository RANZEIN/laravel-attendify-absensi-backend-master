<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HolidayControllerApi extends Controller
{
    /**
     * Get holidays for a specific month.
     */
    public function getMonthlyHolidays(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $year = $request->year;
        $month = $request->month;

        $holidays = Holiday::getMonthlyHolidays($year, $month);

        return response()->json([
            'status' => 'Success',
            'message' => 'Holidays retrieved successfully',
            'data' => $holidays
        ]);
    }

    /**
     * Get holidays for a specific date range.
     */
    public function getHolidaysInRange(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $holidays = Holiday::inDateRange($startDate, $endDate)->get();

        return response()->json([
            'status' => 'Success',
            'message' => 'Holidays retrieved successfully',
            'data' => $holidays
        ]);
    }

    /**
     * Check if a specific date is a holiday.
     */
    public function checkHoliday(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->date;
        $holiday = Holiday::onDate($date)->first();

        return response()->json([
            'status' => 'Success',
            'message' => 'Holiday check completed',
            'is_holiday' => $holiday ? true : false,
            'holiday' => $holiday
        ]);
    }

    /**
     * Create a new holiday.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:national,company,weekend',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
        ]);

        $holiday = Holiday::create([
            'name' => $request->name,
            'date' => $request->date,
            'type' => $request->type,
            'description' => $request->description,
            'is_recurring' => $request->is_recurring ?? false,
            'created_by' => $request->user()->id,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Holiday created successfully',
            'data' => $holiday
        ], 201);
    }

    /**
     * Update an existing holiday.
     */
    public function update(Request $request, $id)
    {
        $holiday = Holiday::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:national,company,weekend',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
        ]);

        $holiday->update([
            'name' => $request->name,
            'date' => $request->date,
            'type' => $request->type,
            'description' => $request->description,
            'is_recurring' => $request->is_recurring ?? false,
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Holiday updated successfully',
            'data' => $holiday
        ]);
    }

    /**
     * Delete a holiday.
     */
    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return response()->json([
            'status' => 'Success',
            'message' => 'Holiday deleted successfully'
        ]);
    }

    /**
     * Toggle a date between holiday and working day.
     */
    public function toggleHoliday(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->date;
        $existingHoliday = Holiday::whereDate('date', $date)->first();

        if ($existingHoliday) {
            // If it's already a holiday, delete it to make it a working day
            $existingHoliday->delete();
            $message = 'Date marked as working day successfully.';
            $isHoliday = false;
        } else {
            // If it's a working day, create a holiday
            $carbonDate = Carbon::parse($date);
            $isWeekend = $carbonDate->isWeekend();

            $holiday = Holiday::create([
                'name' => $isWeekend ? $carbonDate->format('l') . ' Weekend' : 'Company Holiday',
                'date' => $date,
                'type' => $isWeekend ? 'weekend' : 'company',
                'description' => $isWeekend ? 'Weekend holiday' : 'Company holiday',
                'is_recurring' => false,
                'created_by' => $request->user()->id,
            ]);

            $message = 'Date marked as holiday successfully.';
            $isHoliday = true;
        }

        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'is_holiday' => $isHoliday
        ]);
    }
}
