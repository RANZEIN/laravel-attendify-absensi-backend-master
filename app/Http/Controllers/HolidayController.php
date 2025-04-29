<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
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

        // Get years for dropdown (current year - 1 to current year + 2)
        $currentYear = Carbon::now()->year;
        $years = range($currentYear - 1, $currentYear + 2);

        return view('pages.holidays.index', compact('holidays', 'months', 'years', 'year', 'month'));
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

        // Get years for dropdown (current year - 1 to current year + 2)
        $currentYear = Carbon::now()->year;
        $years = range($currentYear - 1, $currentYear + 2);

        // Generate calendar data
        $calendar = $this->generateCalendarData($year, $month, $holidayDates);

        return view('pages.holidays.calendar', compact('calendar', 'months', 'years', 'year', 'month', 'holidayDates'));
    }

    /**
     * Generate calendar data for the view.
     */
    private function generateCalendarData($year, $month, $holidayDates)
    {
        $date = Carbon::createFromDate($year, $month, 1);
        $daysInMonth = $date->daysInMonth;
        $firstDayOfMonth = $date->copy()->firstOfMonth()->dayOfWeek;

        // Adjust for Sunday as first day of week (0)
        $firstDayOfMonth = $firstDayOfMonth === 0 ? 7 : $firstDayOfMonth;

        $calendar = [];
        $day = 1;

        // Create 6 weeks to ensure we have enough rows for all months
        for ($i = 0; $i < 6; $i++) {
            $week = [];

            // 7 days in a week
            for ($j = 1; $j <= 7; $j++) {
                if (($i === 0 && $j < $firstDayOfMonth) || $day > $daysInMonth) {
                    // Empty cell
                    $week[] = [
                        'day' => null,
                        'isHoliday' => false,
                        'holidayName' => null,
                        'holidayType' => null,
                        'date' => null
                    ];
                } else {
                    $currentDate = Carbon::createFromDate($year, $month, $day)->format('Y-m-d');
                    $isWeekend = in_array($j, [6, 7]); // Saturday (6) and Sunday (7)

                    $isHoliday = isset($holidayDates[$currentDate]) || $isWeekend;
                    $holidayName = isset($holidayDates[$currentDate]) ? $holidayDates[$currentDate]['name'] : ($isWeekend ? ($j === 6 ? 'Saturday' : 'Sunday') : null);
                    $holidayType = isset($holidayDates[$currentDate]) ? $holidayDates[$currentDate]['type'] : ($isWeekend ? 'weekend' : null);

                    $week[] = [
                        'day' => $day,
                        'isHoliday' => $isHoliday,
                        'holidayName' => $holidayName,
                        'holidayType' => $holidayType,
                        'date' => $currentDate
                    ];

                    $day++;
                }
            }

            $calendar[] = $week;

            // If we've used all days in the month, break
            if ($day > $daysInMonth) {
                break;
            }
        }

        return $calendar;
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
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        $year = $request->year;
        $startDate = Carbon::createFromDate($year, 1, 1);
        $endDate = Carbon::createFromDate($year, 12, 31);

        $count = 0;
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            // Check if it's a weekend (Saturday or Sunday)
            if ($currentDate->isWeekend()) {
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
            'year' => 'required|integer|min:2000|max:2100',
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
            $isWeekend = $carbonDate->isWeekend();

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
}
