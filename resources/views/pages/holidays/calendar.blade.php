@extends('layouts.app')

@section('title', 'Holiday Calendar')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-style.css') }}">
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

