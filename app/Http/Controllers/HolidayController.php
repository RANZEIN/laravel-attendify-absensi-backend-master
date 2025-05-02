<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\WeekendSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HolidayController extends Controller
{
    /**
     * Display a listing of holidays.
     */
    public function index(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        // Get all holidays for the selected month
        $holidays = Holiday::getMonthlyHolidays($year, $month);

        // Get all months for the dropdown
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::createFromDate(null, $i, 1)->format('F');
        }

        // Get weekend settings
        $weekendSettings = WeekendSetting::first() ?: new WeekendSetting();

        return view('pages.holidays.index', compact('holidays', 'months', 'year', 'month', 'weekendSettings'));
    }

    /**
     * Show the calendar view.
     */
    public function calendar(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);
        $month = $request->input('month', Carbon::now()->month);

        // Get all holidays for the selected month
        $holidays = Holiday::getMonthlyHolidays($year, $month);

        // Convert holidays to a format suitable for the calendar
        $holidayDates = [];
        foreach ($holidays as $holiday) {
            $holidayDates[$holiday->date->format('Y-m-d')] = [
                'name' => $holiday->name,
                'type' => $holiday->type,
                'description' => $holiday->description
            ];
        }

        // Get all months for the dropdown
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = Carbon::createFromDate(null, $i, 1)->format('F');
        }

        // Get weekend settings
        $weekendSettings = WeekendSetting::first() ?: new WeekendSetting();

        return view('pages.holidays.calendar', compact('months', 'year', 'month', 'holidayDates', 'weekendSettings'));
    }

    /**
     * Show the form for creating a new holiday.
     */
    public function create()
    {
        return view('pages.holidays.create');
    }

    /**
     * Store a newly created holiday in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|in:national,company',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
        ]);

        Holiday::create([
            'name' => $request->name,
            'date' => $request->date,
            'type' => $request->type,
            'description' => $request->description,
            'is_recurring' => $request->has('is_recurring'),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday created successfully.');
    }

    /**
     * Generate weekend holidays for a specific year.
     */
    public function generateWeekendHolidays(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'weekend_days' => 'required|array',
            'weekend_days.*' => 'integer|between:0,6',
        ]);

        $year = $request->year;
        $weekendDays = $request->weekend_days;

        // Update weekend settings
        $weekendSettings = WeekendSetting::first();
        if (!$weekendSettings) {
            $weekendSettings = new WeekendSetting();
        }
        $weekendSettings->weekend_days = json_encode($weekendDays);
        $weekendSettings->save();

        $startDate = Carbon::createFromDate($year, 1, 1);
        $endDate = Carbon::createFromDate($year, 12, 31);

        $count = 0;
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            // Check if the current day is in the selected weekend days
            // Carbon: 0 (Sunday) to 6 (Saturday)
            if (in_array($currentDate->dayOfWeek, $weekendDays)) {
                // Check if a holiday already exists for this date
                $existingHoliday = Holiday::whereDate('date', $currentDate->format('Y-m-d'))->first();

                if (!$existingHoliday) {
                    Holiday::create([
                        'name' => $currentDate->format('l') . ' Weekend',
                        'date' => $currentDate->format('Y-m-d'),
                        'type' => 'weekend',
                        'description' => 'Automatically generated weekend holiday',
                        'is_recurring' => false,
                        'created_by' => Auth::id(),
                    ]);

                    $count++;
                }
            }

            $currentDate->addDay();
        }

        return redirect()->route('holidays.index')
            ->with('success', $count . ' weekend holidays generated successfully for ' . $year . '.');
    }

    /**
     * Import national holidays from a predefined list.
     */
    public function importNationalHolidays(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
        ]);

        $year = $request->year;

        // This is a simplified example. In a real application, you might:
        // 1. Use an API to fetch national holidays
        // 2. Import from a CSV/Excel file
        // 3. Have a predefined list in the database

        // Sample Indonesian national holidays for demonstration
        $nationalHolidays = [
            ['name' => 'Tahun Baru', 'month' => 1, 'day' => 1],
            ['name' => 'Hari Raya Nyepi', 'month' => 3, 'day' => 14], // Dates will vary by year
            ['name' => 'Wafat Isa Almasih', 'month' => 4, 'day' => 7], // Dates will vary by year
            ['name' => 'Hari Buruh Internasional', 'month' => 5, 'day' => 1],
            ['name' => 'Hari Raya Idul Fitri', 'month' => 5, 'day' => 13], // Dates will vary by year
            ['name' => 'Hari Raya Idul Fitri', 'month' => 5, 'day' => 14], // Dates will vary by year
            ['name' => 'Hari Lahir Pancasila', 'month' => 6, 'day' => 1],
            ['name' => 'Hari Kemerdekaan', 'month' => 8, 'day' => 17],
            ['name' => 'Hari Raya Natal', 'month' => 12, 'day' => 25],
        ];

        $count = 0;

        foreach ($nationalHolidays as $holiday) {
            $date = Carbon::createFromDate($year, $holiday['month'], $holiday['day']);

            // Check if a holiday already exists for this date
            $existingHoliday = Holiday::whereDate('date', $date->format('Y-m-d'))->first();

            if (!$existingHoliday) {
                Holiday::create([
                    'name' => $holiday['name'],
                    'date' => $date->format('Y-m-d'),
                    'type' => 'national',
                    'description' => 'National Holiday',
                    'is_recurring' => true,
                    'created_by' => Auth::id(),
                ]);

                $count++;
            }
        }

        return redirect()->route('holidays.index')
            ->with('success', $count . ' national holidays imported successfully for ' . $year . '.');
    }

    /**
     * Show the form for editing the specified holiday.
     */
    public function edit(Holiday $holiday)
    {
        return view('pages.holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified holiday in storage.
     */
    public function update(Request $request, Holiday $holiday)
    {
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
            'is_recurring' => $request->has('is_recurring'),
        ]);

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday updated successfully.');
    }

    /**
     * Remove the specified holiday from storage.
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday deleted successfully.');
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
        } else {
            // If it's a working day, create a holiday
            $carbonDate = Carbon::parse($date);

            // Get weekend settings
            $weekendSettings = WeekendSetting::first();
            $weekendDays = $weekendSettings ? json_decode($weekendSettings->weekend_days) : [0, 6]; // Default to Sunday and Saturday

            $isWeekend = in_array($carbonDate->dayOfWeek, $weekendDays);

            Holiday::create([
                'name' => $isWeekend ? $carbonDate->format('l') . ' Weekend' : 'Company Holiday',
                'date' => $date,
                'type' => $isWeekend ? 'weekend' : 'company',
                'description' => $isWeekend ? 'Weekend holiday' : 'Company holiday',
                'is_recurring' => false,
                'created_by' => Auth::id(),
            ]);

            $message = 'Date marked as holiday successfully.';
        }

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Update weekend settings.
     */
    public function updateWeekendSettings(Request $request)
    {
        $request->validate([
            'weekend_days' => 'required|array',
            'weekend_days.*' => 'integer|between:0,6',
        ]);

        $weekendSettings = WeekendSetting::first();
        if (!$weekendSettings) {
            $weekendSettings = new WeekendSetting();
        }

        $weekendSettings->weekend_days = json_encode($request->weekend_days);
        $weekendSettings->save();

        return redirect()->back()->with('success', 'Weekend settings updated successfully.');
    }
}
