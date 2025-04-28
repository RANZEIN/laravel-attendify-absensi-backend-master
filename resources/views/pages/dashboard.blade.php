@extends('layouts.app')

@section('title', 'General Dashboard')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.min.css') }}">
    <style>
        :root {
            --primary-color: #6366f1;
            --success-color: #22c55e;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --bg-light: #f8fafc;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        }

        body {
            background-color: var(--bg-light);
            color: var(--text-primary);
        }

        .section-header {
            border-bottom: none;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
        }

        .section-header h1 {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .stat-icon {
            font-size: 1.5rem;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 1.5rem;
        }

        .card-header h4 {
            font-weight: 600;
            font-size: 1.125rem;
            margin: 0;
            color: var(--text-primary);
        }

        .card-body {
            padding: 1.5rem;
        }

        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table td {
            vertical-align: middle;
            padding: 0.75rem 1rem;
            color: var(--text-primary);
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.01);
        }

        .badge {
            padding: 0.35em 0.65em;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .badge-success {
            background-color: rgba(34, 197, 94, 0.1);
            color: var(--success-color);
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .breadcrumb-item + .breadcrumb-item::before {
            content: "•";
            color: var(--text-secondary);
        }

        .breadcrumb-item a {
            color: var(--primary-color);
        }

        .breadcrumb-item.active {
            color: var(--text-secondary);
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Dashboard</h1>
                    <div class="section-header-breadcrumb mt-2">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item">Overview</div>
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-light rounded-pill px-3 shadow-sm dropdown-toggle" type="button" id="timeframeDropdown" data-toggle="dropdown">
                        <i class="fas fa-calendar-alt mr-2"></i> Last 30 days
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Today</a>
                        <a class="dropdown-item" href="#">Last 7 days</a>
                        <a class="dropdown-item" href="#">Last 30 days</a>
                        <a class="dropdown-item" href="#">This month</a>
                        <a class="dropdown-item" href="#">Custom range</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary text-white mr-3">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1 font-weight-normal">Total Attendances</h6>
                                    <h3 class="font-weight-bold mb-0">{{ $totalAttendances }}</h3>
                                </div>
                            </div>
                            <div class="mt-3 pt-1 border-top">
                                <small class="text-success">
                                    <i class="fas fa-arrow-up mr-1"></i> 12% increase
                                </small>
                                <small class="text-muted ml-1">from last month</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success text-white mr-3">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1 font-weight-normal">On Time</h6>
                                    <h3 class="font-weight-bold mb-0">{{ $onTimeAttendance }}</h3>
                                </div>
                            </div>
                            <div class="mt-3 pt-1 border-top">
                                <small class="text-success">
                                    <i class="fas fa-arrow-up mr-1"></i> 8% increase
                                </small>
                                <small class="text-muted ml-1">from last month</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-warning text-white mr-3">
                                    <i class="fas fa-user-clock"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1 font-weight-normal">Late Attendance</h6>
                                    <h3 class="font-weight-bold mb-0">{{ $lateAttendance }}</h3>
                                </div>
                            </div>
                            <div class="mt-3 pt-1 border-top">
                                <small class="text-danger">
                                    <i class="fas fa-arrow-up mr-1"></i> 3% increase
                                </small>
                                <small class="text-muted ml-1">from last month</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-danger text-white mr-3">
                                    <i class="fas fa-user-times"></i>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-1 font-weight-normal">Total Absence</h6>
                                    <h3 class="font-weight-bold mb-0">{{ $totalAbsence }}</h3>
                                </div>
                            </div>
                            <div class="mt-3 pt-1 border-top">
                                <small class="text-success">
                                    <i class="fas fa-arrow-down mr-1"></i> 5% decrease
                                </small>
                                <small class="text-muted ml-1">from last month</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Attendance Trends</h4>
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-light active">Week</button>
                                <button type="button" class="btn btn-light">Month</button>
                                <button type="button" class="btn btn-light">Year</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="attendanceChart" height="280"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Attendance Distribution</h4>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">
                                    This Month
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#">This Week</a>
                                    <a class="dropdown-item" href="#">This Month</a>
                                    <a class="dropdown-item" href="#">This Year</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="employerTracker" height="280"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>Recent Attendance</h4>
                            <div>
                                <button class="btn btn-sm btn-primary rounded-pill px-3">
                                    <i class="fas fa-download mr-1"></i> Export
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Check-in</th>
                                            <th>Check-out</th>
                                            <th>Location In</th>
                                            <th>Location Out</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attendances as $attendance)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm mr-2">
                                                            <img src="{{ asset('img/avatar/avatar-' . random_int(1, 5) . '.png') }}" alt="Avatar" class="rounded-circle">
                                                        </div>
                                                        <div>
                                                            {{ $attendance->user->name }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $attendance->date }}</td>
                                                <td>{{ $attendance->time_in }}</td>
                                                <td>{{ $attendance->time_out ?? 'Not Checked Out' }}</td>
                                                <td>
                                                    <span class="text-muted">
                                                        <i class="fas fa-map-marker-alt mr-1"></i> {{ $attendance->latlon_in }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-muted">
                                                        @if($attendance->latlon_out)
                                                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $attendance->latlon_out }}
                                                        @else
                                                            Not Available
                                                        @endif
                                                    </span>
                                                </td>
                                                {{-- <td>
                                                    @if($attendance->is_late)
                                                        <span class="badge badge-warning">Late</span>
                                                    @else
                                                        <span class="badge badge-success">On Time</span>
                                                    @endif
                                                </td> --}}
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="#" class="text-primary">View All Records</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Set Chart.js defaults
        Chart.defaults.font.family = "'Inter', 'Helvetica', 'Arial', sans-serif";
        Chart.defaults.font.size = 12;
        Chart.defaults.plugins.legend.position = 'top';
        Chart.defaults.plugins.legend.labels.usePointStyle = true;
        Chart.defaults.plugins.legend.labels.boxWidth = 6;
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(255, 255, 255, 0.9)';
        Chart.defaults.plugins.tooltip.titleColor = '#1e293b';
        Chart.defaults.plugins.tooltip.bodyColor = '#1e293b';
        Chart.defaults.plugins.tooltip.borderColor = 'rgba(0, 0, 0, 0.1)';
        Chart.defaults.plugins.tooltip.borderWidth = 1;
        Chart.defaults.plugins.tooltip.padding = 10;
        Chart.defaults.plugins.tooltip.boxPadding = 6;

        // Attendance Trends Chart
        var ctx = document.getElementById('attendanceChart').getContext('2d');
        var attendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($months),
                datasets: [{
                    label: 'On Time Attendance',
                    data: @json($onTimeData),
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#22c55e',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }, {
                    label: 'Late Attendance',
                    data: @json($lateData),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#f59e0b',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                    },
                    tooltip: {
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.raw;
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [2, 2],
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            callback: function(value) {
                                return value;
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Employer Tracker Pie Chart
        var ctx2 = document.getElementById('employerTracker').getContext('2d');
        var employerTracker = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['On Time', 'Late', 'Absent'],
                datasets: [{
                    data: [
                        {{ $attendanceStatusData['on_time'] }},
                        {{ $attendanceStatusData['late'] }},
                        {{ $attendanceStatusData['absent'] }}
                    ],
                    backgroundColor: ['#22c55e', '#f59e0b', '#94a3b8'],
                    borderWidth: 0,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            boxWidth: 12,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                const value = context.raw;
                                const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                const percentage = Math.round((value / total) * 100);
                                label += `${value} (${percentage}%)`;
                                return label;
                            }
                        }
                    }
                },
                cutout: '70%',
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    </script>
@endpush
