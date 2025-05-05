@extends('layouts.app')

@section('title', 'Dashboard')

@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.3/dist/apexcharts.min.css">
<style>


    /* Chart Cards */
    .chart-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .chart-card {
        background: white;
        border-radius: 1rem;
        box-shadow: var(--card-shadow);
        transition: all var(--transition-speed) ease;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .chart-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--grey-light);
    }

    .chart-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-main);
        display: flex;
        align-items: center;
    }

    .chart-title i {
        margin-right: 0.5rem;
        color: var(--primary);
    }

    .chart-actions {
        display: flex;
        gap: 0.5rem;
    }

    .chart-action {
        background: var(--light);
        border: 1px solid var(--grey);
        color: var(--text-secondary);
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.25rem 0.75rem;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all var(--transition-speed) ease;
    }

    .chart-action:hover, .chart-action.active {
        background: var(--primary);
        border-color: var(--primary);
        color: white;
    }

    .chart-body {
        padding: 1.5rem;
    }

    /* Table Card */
    .table-card {
        background: white;
        border-radius: 1rem;
        box-shadow: var(--card-shadow);
        transition: all var(--transition-speed) ease;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.03);
        margin-bottom: 1.5rem;
    }

    .table-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .table-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--grey-light);
    }

    .table-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-main);
        display: flex;
        align-items: center;
    }

    .table-title i {
        margin-right: 0.5rem;
        color: var(--primary);
    }

    .table-actions {
        display: flex;
        gap: 0.5rem;
    }

    .table-action {
        background: var(--primary);
        color: white;
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        cursor: pointer;
        transition: all var(--transition-speed) ease;
        border: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-action:hover {
        background: var(--primary-hover);
    }

    .table-body {
        overflow-x: auto;
    }

    .dashboard-table {
        width: 100%;
        border-collapse: collapse;
    }

    .dashboard-table th {
        background: var(--grey-light);
        color: var(--text-secondary);
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 0.75rem 1.5rem;
        text-align: left;
        border-bottom: 1px solid var(--grey);
    }

    .dashboard-table td {
        padding: 1rem 1.5rem;
        color: var(--text-main);
        font-size: 0.875rem;
        border-bottom: 1px solid var(--grey-light);
        vertical-align: middle;
    }

    .dashboard-table tr:last-child td {
        border-bottom: none;
    }

    .dashboard-table tr:hover td {
        background-color: rgba(99, 102, 241, 0.05);
    }

    .table-footer {
        padding: 1rem 1.5rem;
        text-align: center;
        border-top: 1px solid var(--grey-light);
    }

    .view-all {
        color: var(--primary);
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        transition: all var(--transition-speed) ease;
    }

    .view-all:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    /* User Avatar */
    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-name {
        font-weight: 500;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-badge.on-time {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .status-badge.late {
        background-color: rgba(245, 158, 11, 0.1);
        color: var(--warning);
    }

    .status-badge.absent {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .status-badge i {
        margin-right: 0.25rem;
        font-size: 0.75rem;
    }

    /* Location Display */
    .location-display {
        display: flex;
        align-items: center;
        color: var(--text-secondary);
        font-size: 0.75rem;
    }

    .location-display i {
        margin-right: 0.25rem;
        font-size: 0.75rem;
    }

    /* Time Display */
    .time-display {
        font-family: 'Courier New', monospace;
        font-weight: 600;
        color: var(--text-main);
    }

    /* Summary Cards */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .summary-card {
        background: white;
        border-radius: 1rem;
        box-shadow: var(--card-shadow);
        padding: 1.5rem;
        transition: all var(--transition-speed) ease;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .summary-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .summary-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-main);
        display: flex;
        align-items: center;
    }

    .summary-title i {
        margin-right: 0.5rem;
        color: var(--primary);
    }

    .summary-body {
        margin-bottom: 1rem;
    }

    .summary-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--grey-light);
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .summary-value {
        font-weight: 600;
        color: var(--text-main);
        font-size: 0.875rem;
    }

    /* Timeframe Selector */
    .timeframe-selector {
        background: white;
        border: 1px solid var(--grey);
        border-radius: 2rem;
        padding: 0.5rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--text-main);
        cursor: pointer;
        transition: all var(--transition-speed) ease;
    }

    .timeframe-selector:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .timeframe-selector i {
        font-size: 0.875rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 1200px) {
        .chart-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 1rem;
        }

        .dashboard-title {
            font-size: 1.5rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .chart-header, .table-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .chart-actions, .table-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .dashboard-table th, .dashboard-table td {
            padding: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .summary-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .timeframe-selector {
            width: 100%;
            justify-content: space-between;
        }
    }

    /* Animation Classes */
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up:nth-child(1) { animation-delay: 0.1s; }
    .fade-in-up:nth-child(2) { animation-delay: 0.2s; }
    .fade-in-up:nth-child(3) { animation-delay: 0.3s; }
    .fade-in-up:nth-child(4) { animation-delay: 0.4s; }
    .fade-in-up:nth-child(5) { animation-delay: 0.5s; }
</style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Dashboard</h1>
                    <p class="dashboard-subtitle">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
                    <div class="dropdown">
                        <button class="timeframe-selector dropdown-toggle" type="button" id="timeframeDropdown" data-toggle="dropdown">
                            <i class="fas fa-calendar-alt"></i> Last 30 days</i>
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

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card fade-in-up">
                <div class="stat-header">
                    <div class="stat-title">Total Attendances</div>
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalAttendances }}</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 12%
                    <span class="stat-change-text">from last month</span>
                </div>
            </div>

            <div class="stat-card success fade-in-up">
                <div class="stat-header">
                    <div class="stat-title">On Time</div>
                    <div class="stat-icon success">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $onTimeAttendance }}</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 8%
                    <span class="stat-change-text">from last month</span>
                </div>
            </div>

            <div class="stat-card warning fade-in-up">
                <div class="stat-header">
                    <div class="stat-title">Late Attendance</div>
                    <div class="stat-icon warning">
                        <i class="fas fa-user-clock"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $lateAttendance }}</div>
                <div class="stat-change negative">
                    <i class="fas fa-arrow-up"></i> 3%
                    <span class="stat-change-text">from last month</span>
                </div>
            </div>

            <div class="stat-card danger fade-in-up">
                <div class="stat-header">
                    <div class="stat-title">Total Absence</div>
                    <div class="stat-icon danger">
                        <i class="fas fa-user-times"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $totalAbsence }}</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-down"></i> 5%
                    <span class="stat-change-text">from last month</span>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="chart-grid">
            <div class="chart-card fade-in-up">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fas fa-chart-line"></i> Attendance Trends
                    </div>
                    <div class="chart-actions">
                        <button class="chart-action active" data-period="week">Week</button>
                        <button class="chart-action" data-period="month">Month</button>
                        <button class="chart-action" data-period="year">Year</button>
                    </div>
                </div>
                <div class="chart-body">
                    <div id="attendanceChart" style="height: 350px;"></div>
                </div>
            </div>

            <div class="chart-card fade-in-up">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fas fa-chart-pie"></i> Attendance Distribution
                    </div>
                    <div class="chart-actions">
                        <button class="chart-action active" data-period="month">Month</button>
                        <button class="chart-action" data-period="year">Year</button>
                    </div>
                </div>
                <div class="chart-body">
                    <div id="distributionChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="summary-grid">
            <!-- Time Off Summary -->
            <div class="summary-card fade-in-up">
                <div class="summary-header">
                    <div class="summary-title">
                        <i class="fas fa-calendar-alt"></i> Time Off Summary
                    </div>
                </div>
                <div class="summary-body">
                    <div class="summary-item">
                        <div class="summary-label">Pending Requests</div>
                        <div class="summary-value">{{ $timeOffSummary['pending'] ?? 5 }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Approved</div>
                        <div class="summary-value">{{ $timeOffSummary['approved'] ?? 12 }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Rejected</div>
                        <div class="summary-value">{{ $timeOffSummary['rejected'] ?? 3 }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Total Days Off</div>
                        <div class="summary-value">{{ $timeOffSummary['total_days'] ?? 24 }}</div>
                    </div>
                </div>
            </div>

            <!-- Holiday Summary -->
            <div class="summary-card fade-in-up">
                <div class="summary-header">
                    <div class="summary-title">
                        <i class="fas fa-calendar-day"></i> Holiday Summary
                    </div>
                </div>
                <div class="summary-body">
                    <div class="summary-item">
                        <div class="summary-label">National Holidays</div>
                        <div class="summary-value">{{ $holidaySummary['national'] ?? 12 }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Company Holidays</div>
                        <div class="summary-value">{{ $holidaySummary['company'] ?? 5 }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Weekend Holidays</div>
                        <div class="summary-value">{{ $holidaySummary['weekend'] ?? 104 }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Next Holiday</div>
                        <div class="summary-value">{{ $holidaySummary['next_holiday'] ?? 'Dec 25' }}</div>
                    </div>
                </div>
            </div>

            <!-- User Summary -->
            <div class="summary-card fade-in-up">
                <div class="summary-header">
                    <div class="summary-title">
                        <i class="fas fa-users"></i> User Summary
                    </div>
                </div>
                <div class="summary-body">
                    <div class="summary-item">
                        <div class="summary-label">Total Users</div>
                        <div class="summary-value">{{ $userSummary['total'] ?? 45 }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">Active Today</div>
                        <div class="summary-value">{{ $userSummary['active_today'] ?? 38 }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">On Leave</div>
                        <div class="summary-value">{{ $userSummary['on_leave'] ?? 3 }}</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-label">New This Month</div>
                        <div class="summary-value">{{ $userSummary['new_this_month'] ?? 2 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Attendance Table -->
        <div class="table-card fade-in-up">
            <div class="table-header">
                <div class="table-title">
                    <i class="fas fa-history"></i> Recent Attendance
                </div>
                <div class="table-actions">
                    <button class="table-action">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
            <div class="table-body">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Status</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <img src="{{ asset('img/avatar/avatar-' . random_int(1, 5) . '.png') }}" alt="Avatar" class="user-avatar">
                                        <span class="user-name">{{ $attendance->user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}</td>
                                <td class="time-display">{{ $attendance->time_in }}</td>
                                <td class="time-display">{{ $attendance->time_out ?? '—' }}</td>
                                <td>
                                    @if(isset($attendance->is_late) && $attendance->is_late)
                                        <span class="status-badge late">
                                            <i class="fas fa-exclamation-circle"></i> Late
                                        </span>
                                    @else
                                        <span class="status-badge on-time">
                                            <i class="fas fa-check-circle"></i> On Time
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="location-display">
                                        <i class="fas fa-map-marker-alt"></i> {{ substr($attendance->latlon_in, 0, 10) }}...
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="table-footer">
                <a href="{{ route('attendances.index') }}" class="view-all">View All Records</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.3/dist/apexcharts.min.js"></script>

<div id="attendanceChart"></div>
<div id="distributionChart"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attendance Chart
        const attendanceChartOptions = {
            series: [
                {
                    name: 'On Time',
                    data: {!! json_encode($onTimeData ?? [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10]) !!}
                },
                {
                    name: 'Late',
                    data: {!! json_encode($lateData ?? [35, 41, 62, 42, 13, 18, 29, 37, 36, 51, 32, 35]) !!}
                }
            ],
            chart: {
                height: 350,
                type: 'line',
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                fontFamily: "'Inter', 'Helvetica', 'Arial', sans-serif"
            },
            colors: ['#10b981', '#f59e0b'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: [3, 3],
                curve: 'smooth',
                dashArray: [0, 0]
            },
            grid: {
                borderColor: '#e2e8f0',
                row: {
                    colors: ['transparent', 'transparent'],
                    opacity: 0.5
                }
            },
            markers: {
                size: 5,
                hover: {
                    size: 7
                }
            },
            xaxis: {
                categories: {!! json_encode($months ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']) !!},
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontFamily: "'Inter', 'Helvetica', 'Arial', sans-serif",
                        fontWeight: 500
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#64748b',
                        fontSize: '12px',
                        fontFamily: "'Inter', 'Helvetica', 'Arial', sans-serif",
                        fontWeight: 500
                    }
                }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function (val) {
                        return val + " attendances";
                    }
                },
                x: {
                    show: true
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                floating: true,
                offsetY: -25,
                offsetX: -5,
                fontSize: '13px',
                fontFamily: "'Inter', 'Helvetica', 'Arial', sans-serif",
                fontWeight: 500,
                markers: {
                    width: 12,
                    height: 12,
                    strokeWidth: 0,
                    strokeColor: '#fff',
                    radius: 12
                }
            }
        };

        const attendanceChart = new ApexCharts(document.querySelector("#attendanceChart"), attendanceChartOptions);
        attendanceChart.render();

        // Distribution Chart
        const distributionChartOptions = {
            series: [
                @json($attendanceStatusData['on_time'] ?? 65),
                @json($attendanceStatusData['late'] ?? 25),
                @json($attendanceStatusData['absent'] ?? 10)
            ],
            chart: {
                type: 'donut',
                height: 350,
                fontFamily: "'Inter', 'Helvetica', 'Arial', sans-serif",
            },
            labels: ['On Time', 'Late', 'Absent'],
            colors: ['#10b981', '#f59e0b', '#94a3b8'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function (w) {
                                    return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                fontSize: '13px',
                fontFamily: "'Inter', 'Helvetica', 'Arial', sans-serif",
                fontWeight: 500,
                markers: {
                    width: 12,
                    height: 12,
                    strokeWidth: 0,
                    strokeColor: '#fff',
                    radius: 12
                }
            },
            stroke: {
                width: 2,
                colors: ['#fff']
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function (val, opts) {
                        const total = opts.globals.seriesTotals.reduce((a, b) => a + b, 0);
                        const percent = ((val / total) * 100).toFixed(1);
                        return val + " (" + percent + "%)";
                    }
                }
            }
        };

        const distributionChart = new ApexCharts(document.querySelector("#distributionChart"), distributionChartOptions);
        distributionChart.render();

        // Chart period buttons (if any)
        document.querySelectorAll('.chart-action').forEach(button => {
            button.addEventListener('click', function () {
                const parent = this.closest('.chart-actions');
                parent.querySelectorAll('.chart-action').forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');
                console.log('Selected period:', this.dataset.period);
            });
        });
    });
</script>
@endpush
