@extends('layouts.app')

@section('title', 'Holiday Calendar')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --success-color: #10b981;
            --info-color: #3b82f6;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --text-main: #1e293b;
            --text-secondary: #64748b;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --weekend-bg: rgba(16, 185, 129, 0.05);
            --national-holiday-bg: rgba(239, 68, 68, 0.05);
            --company-holiday-bg: rgba(59, 130, 246, 0.05);
        }

        .calendar-container {
            margin-top: 20px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .calendar-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-weight: 600;
            background-color: #f1f5f9;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .calendar-header-day {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-main);
        }

        .calendar-body {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }

        .calendar-day {
            min-height: 120px;
            border: 1px solid var(--border-color);
            padding: 10px;
            position: relative;
            transition: all 0.2s ease;
            background-color: white;
        }

        .calendar-day:hover {
            background-color: #f8fafc;
            cursor: pointer;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .calendar-day.empty {
            background-color: #f8fafc;
            cursor: default;
        }

        .calendar-day.empty:hover {
            transform: none;
            box-shadow: none;
        }

        .calendar-day.holiday {
            background-color: var(--national-holiday-bg);
        }

        .calendar-day.company-holiday {
            background-color: var(--company-holiday-bg);
        }

        .calendar-day.weekend {
            background-color: var(--weekend-bg);
        }

        .day-number {
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 5px;
            color: var(--text-main);
            display: inline-block;
            width: 30px;
            height: 30px;
            line-height: 30px;
            text-align: center;
            border-radius: 50%;
            transition: all 0.2s ease;
        }

        .calendar-day:hover .day-number {
            background-color: var(--primary-color);
            color: white;
        }

        .today .day-number {
            background-color: var(--primary-color);
            color: white;
        }

        .holiday-name {
            font-size: 0.8rem;
            margin-bottom: 5px;
            font-weight: 500;
            color: var(--text-main);
        }

        .holiday-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 0.15em 0.5em;
            font-size: 0.7rem;
            font-weight: 500;
            border-radius: 20px;
        }

        .holiday-badge.national {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .holiday-badge.company {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--info-color);
        }

        .holiday-badge.weekend {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .weekend-label {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--success-color);
            background-color: rgba(16, 185, 129, 0.1);
            padding: 2px 8px;
            border-radius: 20px;
        }

        .filters-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .filter-dropdown {
            height: 38px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            padding: 0 15px;
            background-color: #fff;
        }

        .calendar-legend {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
            padding: 15px;
            background-color: #f8fafc;
            border-radius: 8px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
        }

        .legend-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }

        .legend-color.national {
            background-color: var(--national-holiday-bg);
            border: 1px solid var(--danger-color);
        }

        .legend-color.company {
            background-color: var(--company-holiday-bg);
            border: 1px solid var(--info-color);
        }

        .legend-color.weekend {
            background-color: var(--weekend-bg);
            border: 1px solid var(--success-color);
        }

        .legend-color.working {
            background-color: #fff;
            border: 1px solid var(--border-color);
        }

        .month-navigation {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .month-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            flex-grow: 1;
        }

        .month-nav-btn {
            background-color: #fff;
            border: 1px solid var(--border-color);
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .month-nav-btn:hover {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .filter-btn {
            height: 38px;
            border-radius: 6px;
            padding: 0 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-btn:hover {
            background-color: var(--primary-hover);
        }

        .info-text {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-top: 15px;
        }

        .info-text i {
            color: var(--info-color);
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            border-color: var(--border-color);
            border-radius: 6px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
            padding-left: 15px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary-color);
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border-color: var(--border-color);
            border-radius: 4px;
        }

        .select2-dropdown {
            border-color: var(--border-color);
            border-radius: 6px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .calendar-day {
                min-height: 80px;
                padding: 5px;
            }

            .day-number {
                font-size: 0.9rem;
                width: 25px;
                height: 25px;
                line-height: 25px;
            }

            .holiday-name {
                font-size: 0.7rem;
            }

            .holiday-badge, .weekend-label {
                font-size: 0.6rem;
                top: 5px;
                right: 5px;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Holiday Calendar</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="{{ route('holidays.index') }}">Holidays</a></div>
                        <div class="breadcrumb-item">Calendar</div>
                    </div>
                </div>
                <div class="d-flex">
                    <a href="{{ route('holidays.index') }}" class="btn btn-light mr-2">
                        <i class="fas fa-list"></i> List View
                    </a>
                    <a href="{{ route('holidays.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Holiday
                    </a>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="month-navigation">
                                    <h4 class="month-title">{{ $months[$month] }} {{ $year }}</h4>

                                    <form method="GET" action="{{ route('holidays.calendar') }}" class="d-flex align-items-center gap-2">
                                        <select name="month" class="form-control select2">
                                            @foreach($months as $key => $monthName)
                                                <option value="{{ $key }}" {{ $key == $month ? 'selected' : '' }}>{{ $monthName }}</option>
                                            @endforeach
                                        </select>
                                        <select name="year" class="form-control select2">
                                            @php
                                                $currentYear = date('Y');
                                                $yearRange = range($currentYear - 10, $currentYear + 10);
                                            @endphp
                                            @foreach($yearRange as $y)
                                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="filter-btn">
                                            <i class="fas fa-filter mr-1"></i> Filter
                                        </button>
                                    </form>
                                </div>

                                <div class="calendar-container">
                                    <div class="calendar-header">
                                        <div class="calendar-header-day">Sunday</div>
                                        <div class="calendar-header-day">Monday</div>
                                        <div class="calendar-header-day">Tuesday</div>
                                        <div class="calendar-header-day">Wednesday</div>
                                        <div class="calendar-header-day">Thursday</div>
                                        <div class="calendar-header-day">Friday</div>
                                        <div class="calendar-header-day">Saturday</div>
                                    </div>
                                    <div class="calendar-body">
                                        @php
                                            // Get the first day of the month
                                            $firstDayOfMonth = Carbon\Carbon::createFromDate($year, $month, 1);

                                            // Get the day of week for the first day (0 = Sunday, 6 = Saturday)
                                            $firstDayOfWeek = $firstDayOfMonth->dayOfWeek;

                                            // Get the number of days in the month
                                            $daysInMonth = $firstDayOfMonth->daysInMonth;

                                            // Get today's date
                                            $today = Carbon\Carbon::now();
                                            $isCurrentMonth = ($today->year == $year && $today->month == $month);

                                            // Get weekend settings
                                            $weekendDays = isset($weekendSettings) && $weekendSettings->weekend_days
                                                ? json_decode($weekendSettings->weekend_days)
                                                : [0, 6]; // Default to Sunday and Saturday
                                        @endphp

                                        {{-- Empty cells before the first day of the month --}}
                                        @for ($i = 0; $i < $firstDayOfWeek; $i++)
                                            <div class="calendar-day empty"></div>
                                        @endfor

                                        {{-- Days of the month --}}
                                        @for ($day = 1; $day <= $daysInMonth; $day++)
                                            @php
                                                $currentDate = Carbon\Carbon::createFromDate($year, $month, $day);
                                                $dateString = $currentDate->format('Y-m-d');
                                                $isWeekend = in_array($currentDate->dayOfWeek, $weekendDays);
                                                $isToday = $isCurrentMonth && $today->day == $day;

                                                // Check if this date has a holiday
                                                $holiday = null;
                                                $holidayType = null;
                                                $holidayName = null;

                                                if (isset($holidayDates[$dateString])) {
                                                    $holiday = $holidayDates[$dateString];
                                                    $holidayType = $holiday['type'];
                                                    $holidayName = $holiday['name'];
                                                }
                                            @endphp

                                            <div class="calendar-day {{ $isToday ? 'today' : '' }} {{ $isWeekend ? 'weekend' : '' }} {{ $holidayType == 'national' ? 'holiday' : '' }} {{ $holidayType == 'company' ? 'company-holiday' : '' }}"
                                                 data-date="{{ $dateString }}">
                                                <div class="day-number">{{ $day }}</div>

                                                @if($isWeekend && !$holidayName)
                                                    <div class="weekend-label">Weekend</div>
                                                @endif

                                                @if($holidayName)
                                                    <div class="holiday-name">{{ $holidayName }}</div>
                                                    <div class="holiday-badge {{ $holidayType }}">
                                                        {{ ucfirst($holidayType) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endfor

                                        {{-- Empty cells after the last day of the month --}}
                                        @php
                                            $lastDayOfMonth = Carbon\Carbon::createFromDate($year, $month, $daysInMonth);
                                            $lastDayOfWeek = $lastDayOfMonth->dayOfWeek;
                                            $remainingCells = 6 - $lastDayOfWeek;
                                        @endphp

                                        @for ($i = 0; $i < $remainingCells; $i++)
                                            <div class="calendar-day empty"></div>
                                        @endfor
                                    </div>
                                </div>

                                <div class="calendar-legend">
                                    <div class="legend-item">
                                        <div class="legend-color working"></div>
                                        <span>Working Day</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-color national"></div>
                                        <span>National Holiday</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-color company"></div>
                                        <span>Company Holiday</span>
                                    </div>
                                    <div class="legend-item">
                                        <div class="legend-color weekend"></div>
                                        <span>Weekend</span>
                                    </div>
                                </div>

                                <div class="info-text">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Click on a day to toggle between holiday and working day.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();

            // Toggle holiday functionality
            document.querySelectorAll('.calendar-day:not(.empty)').forEach(day => {
                day.addEventListener('click', function() {
                    const date = this.dataset.date;

                    // Send AJAX request to toggle holiday status
                    fetch('{{ route('holidays.toggle') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ date: date })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload the page to show updated calendar
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while toggling holiday status.');
                    });
                });
            });
        });
    </script>
@endpush
