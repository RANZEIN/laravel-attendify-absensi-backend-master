<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

        $holidays = Holiday::whereYear('date', $request->year)
            ->whereMonth('date', $request->month)
            ->get();

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

        $holidays = Holiday::whereBetween('date', [$request->start_date, $request->end_date])
            ->get();

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

        $holiday = Holiday::whereDate('date', $request->date)->first();

        return response()->json([
            'status' => 'Success',
            'message' => 'Holiday check completed',
            'is_holiday' => $holiday ? true : false,
            'holiday' => $holiday
        ]);
    }

    /**
     * Store a new holiday.
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
            'created_by' => $request->user()->id ?? null, // optional auth
        ]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Holiday created successfully',
            'data' => $holiday
        ], 201);
    }

    /**
     * Update existing holiday.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:national,company,weekend',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
        ]);

        $holiday = Holiday::findOrFail($id);

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
     * Toggle holiday/working day.
     */
    public function toggleHoliday(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->date;
        $existingHoliday = Holiday::whereDate('date', $date)->first();

        if ($existingHoliday) {
            $existingHoliday->delete();
            return response()->json([
                'status' => 'Success',
                'message' => 'Date marked as working day successfully.',
                'is_holiday' => false
            ]);
        } else {
            $carbonDate = Carbon::parse($date);
            $isWeekend = $carbonDate->isWeekend();

            $holiday = Holiday::create([
                'name' => $isWeekend ? $carbonDate->format('l') . ' Weekend' : 'Company Holiday',
                'date' => $date,
                'type' => $isWeekend ? 'weekend' : 'company',
                'description' => $isWeekend ? 'Weekend holiday' : 'Company holiday',
                'is_recurring' => false,
                'created_by' => $request->user()->id ?? null,
            ]);

            return response()->json([
                'status' => 'Success',
                'message' => 'Date marked as holiday successfully.',
                'is_holiday' => true,
                'holiday' => $holiday
            ]);
        }
    }
}
