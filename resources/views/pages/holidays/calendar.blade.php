@extends('layouts.app')

@section('title', 'Holiday Calendar')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
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
        }

        .calendar-container {
            margin-top: 20px;
        }

        .calendar-header {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            text-align: center;
            font-weight: 600;
            background-color: #f8fafc;
            border-bottom: 1px solid var(--border-color);
            padding: 10px 0;
        }

        .calendar-body {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }

        .calendar-day {
            min-height: 100px;
            border: 1px solid var(--border-color);
            padding: 10px;
            position: relative;
        }

        .calendar-day.empty {
            background-color: #f8fafc;
        }

        .calendar-day.holiday {
            background-color: rgba(239, 68, 68, 0.05);
        }

        .calendar-day.weekend {
            background-color: rgba(16, 185, 129, 0.05);
        }

        .day-number {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .holiday-name {
            font-size: 0.8rem;
            margin-bottom: 5px;
            font-weight: 500;
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
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
        }

        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 3px;
        }

        .legend-color.national {
            background-color: rgba(239, 68, 68, 0.1);
        }

        .legend-color.company {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .legend-color.weekend {
            background-color: rgba(16, 185, 129, 0.1);
        }

        .legend-color.working {
            background-color: #fff;
            border: 1px solid var(--border-color);
        }

        @media (max-width: 768px) {
            .calendar-day {
                min-height: 80px;
                padding: 5px;
            }

            .day-number {
                font-size: 0.9rem;
            }

            .holiday-name {
                font-size: 0.7rem;
            }

            .holiday-badge {
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>{{ $months[$month] }} {{ $year }}</h4>

                                <div class="filters-container">
                                    <form method="GET" action="{{ route('holidays.calendar') }}" class="d-flex gap-2">
                                        <select name="month" class="filter-dropdown">
                                            @foreach($months as $key => $monthName)
                                                <option value="{{ $key }}" {{ $key == $month ? 'selected' : '' }}>{{ $monthName }}</option>
                                            @endforeach
                                        </select>
                                        <select name="year" class="filter-dropdown">
                                            @foreach($years as $y)
                                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </form>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="calendar-container">
                                    <div class="calendar-header">
                                        <div>Sunday</div>
                                        <div>Monday</div>
                                        <div>Tuesday</div>
                                        <div>Wednesday</div>
                                        <div>Thursday</div>
                                        <div>Friday</div>
                                        <div>Saturday</div>
                                    </div>
                                    <div class="calendar-body">
                                        @foreach($calendar as $week)
                                            @foreach($week as $day)
                                                @if($day['day'] === null)
                                                    <div class="calendar-day empty"></div>
                                                @else
                                                    <div class="calendar-day {{ $day['isHoliday'] ? ($day['holidayType'] === 'weekend' ? 'weekend' : 'holiday') : '' }}"
                                                         data-date="{{ $day['date'] }}">
                                                        <div class="day-number">{{ $day['day'] }}</div>
                                                        @if($day['holidayName'])
                                                            <div class="holiday-name">{{ $day['holidayName'] }}</div>
                                                            <div class="holiday-badge {{ $day['holidayType'] }}">
                                                                {{ ucfirst($day['holidayType']) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endforeach
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

                                <div class="mt-4">
                                    <p class="text-muted small">
                                        <i class="fas fa-info-circle"></i> Click on a day to toggle between holiday and working day.
                                    </p>
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
    <script>
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
    </script>
@endpush
