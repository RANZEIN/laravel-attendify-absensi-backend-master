@extends('layouts.app')

@section('title', 'Attendances')

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Attendances</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item">Attendances</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Attendances</h4>
                            <div class="card-header-form">
                                <form method="GET" action="{{ route('attendances.index') }}">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search" name="search" value="{{ request('search') }}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-primary"><i class="fas fa-search"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true">All</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="on-time-tab" data-toggle="tab" href="#on-time" role="tab" aria-controls="on-time" aria-selected="false">On Time</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="late-tab" data-toggle="tab" href="#late" role="tab" aria-controls="late" aria-selected="false">Late</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="absent-tab" data-toggle="tab" href="#absent" role="tab" aria-controls="absent" aria-selected="false">Absent</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <!-- All Tab -->
                                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                                    <div class="filters-container mt-3 mb-3">
                                        <div class="filter-item">
                                            <input type="date" id="date-filter" class="filter-dropdown" placeholder="Filter by date">
                                        </div>
                                        <div class="filter-item">
                                            <select id="department-filter" class="filter-dropdown">
                                                <option value="">All Departments</option>
                                                <option value="it">IT</option>
                                                <option value="hr">HR</option>
                                                <option value="finance">Finance</option>
                                                <option value="marketing">Marketing</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>EMPLOYEE</th>
                                                    <th>DATE</th>
                                                    <th>TIME IN</th>
                                                    <th>TIME OUT</th>
                                                    <th>STATUS</th>
                                                    <th class="text-center">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($attendances) && $attendances->count() > 0)
                                                    @foreach ($attendances as $attendance)
                                                        <tr>
                                                            <td>
                                                                <div class="user-info">
                                                                    <div class="avatar">
                                                                        {{ substr($attendance->user->name, 0, 1) }}
                                                                    </div>
                                                                    <div>
                                                                        <div class="font-weight-bold">{{ $attendance->user->name }}</div>
                                                                        <div class="text-muted small">{{ $attendance->user->position ?? 'No position' }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $timeIn = \Carbon\Carbon::parse($attendance->time_in);
                                                                    $isPM = $timeIn->format('A') === 'PM';
                                                                @endphp
                                                                <div class="status-badge {{ $isPM ? 'late' : 'on-time' }}">
                                                                    <i class="fas fa-sign-in-alt"></i>
                                                                    {{ $timeIn->format('h:i A') }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $timeOut = \Carbon\Carbon::parse($attendance->time_out);
                                                                    $isPM = $timeOut->format('A') === 'PM';
                                                                @endphp
                                                                <div class="status-badge">
                                                                    <i class="fas fa-sign-out-alt"></i>
                                                                    {{ $timeOut->format('h:i A') }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $timeIn = \Carbon\Carbon::parse($attendance->time_in);
                                                                    $startTime = \Carbon\Carbon::parse('08:00:00');
                                                                    $isLate = $timeIn->gt($startTime);
                                                                @endphp

                                                                @if($isLate)
                                                                    <span class="badge badge-warning">Late</span>
                                                                @else
                                                                    <span class="badge badge-success">On Time</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="action-buttons">
                                                                    <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="5">
                                                        <div class="empty-state">
                                                            <i class="fas fa-calendar-times"></i>
                                                            <p>No attendance records found</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- On Time Tab -->
                                <div class="tab-pane fade" id="on-time" role="tabpanel" aria-labelledby="on-time-tab">
                                    <div class="filters-container mt-3 mb-3">
                                        <div class="filter-item">
                                            <input type="date" id="on-time-date-filter" class="filter-dropdown" placeholder="Filter by date">
                                        </div>
                                        <div class="filter-item">
                                            <select id="on-time-department-filter" class="filter-dropdown">
                                                <option value="">All Departments</option>
                                                <option value="it">IT</option>
                                                <option value="hr">HR</option>
                                                <option value="finance">Finance</option>
                                                <option value="marketing">Marketing</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>EMPLOYEE</th>
                                                    <th>DATE</th>
                                                    <th>TIME IN</th>
                                                    <th>TIME OUT</th>
                                                    <th>STATUS</th>
                                                    <th class="text-center">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($attendances) && $attendances->count() > 0)
                                                    @foreach ($attendances as $attendance)
                                                        @php
                                                            $timeIn = \Carbon\Carbon::parse($attendance->time_in);
                                                            $startTime = \Carbon\Carbon::parse('08:00:00');
                                                            $isLate = $timeIn->gt($startTime);
                                                            if($isLate) continue;
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <div class="user-info">
                                                                    <div class="avatar">
                                                                        {{ substr($attendance->user->name, 0, 1) }}
                                                                    </div>
                                                                    <div>
                                                                        <div class="font-weight-bold">{{ $attendance->user->name }}</div>
                                                                        <div class="text-muted small">{{ $attendance->user->position ?? 'No position' }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                                            </td>
                                                            <td>
                                                                <div class="status-badge on-time">
                                                                    <i class="fas fa-sign-in-alt"></i>
                                                                    {{ $timeIn->format('h:i A') }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $timeOut = \Carbon\Carbon::parse($attendance->time_out);
                                                                @endphp
                                                                <div class="status-badge">
                                                                    <i class="fas fa-sign-out-alt"></i>
                                                                    {{ $timeOut->format('h:i A') }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-success">On Time</span>
                                                            </td>
                                                            <td>
                                                                <div class="action-buttons">
                                                                    <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6">
                                                            <div class="empty-state">
                                                                <i class="fas fa-check-circle"></i>
                                                                <p>No on-time attendance records found</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Late Tab -->
                                <div class="tab-pane fade" id="late" role="tabpanel" aria-labelledby="late-tab">
                                    <div class="filters-container mt-3 mb-3">
                                        <div class="filter-item">
                                            <input type="date" id="late-date-filter" class="filter-dropdown" placeholder="Filter by date">
                                        </div>
                                        <div class="filter-item">
                                            <select id="late-department-filter" class="filter-dropdown">
                                                <option value="">All Departments</option>
                                                <option value="it">IT</option>
                                                <option value="hr">HR</option>
                                                <option value="finance">Finance</option>
                                                <option value="marketing">Marketing</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>EMPLOYEE</th>
                                                    <th>DATE</th>
                                                    <th>TIME IN</th>
                                                    <th>TIME OUT</th>
                                                    <th>STATUS</th>
                                                    <th class="text-center">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($attendances) && $attendances->count() > 0)
                                                    @foreach ($attendances as $attendance)
                                                        @php
                                                            $timeIn = \Carbon\Carbon::parse($attendance->time_in);
                                                            $startTime = \Carbon\Carbon::parse('08:00:00');
                                                            $isLate = $timeIn->gt($startTime);
                                                            if(!$isLate) continue;
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <div class="user-info">
                                                                    <div class="avatar">
                                                                        {{ substr($attendance->user->name, 0, 1) }}
                                                                    </div>
                                                                    <div>
                                                                        <div class="font-weight-bold">{{ $attendance->user->name }}</div>
                                                                        <div class="text-muted small">{{ $attendance->user->position ?? 'No position' }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                {{ \Carbon\Carbon::parse($attendance->date)->format('d M Y') }}
                                                            </td>
                                                            <td>
                                                                <div class="status-badge late">
                                                                    <i class="fas fa-sign-in-alt"></i>
                                                                    {{ $timeIn->format('h:i A') }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $timeOut = \Carbon\Carbon::parse($attendance->time_out);
                                                                @endphp
                                                                <div class="status-badge ">
                                                                    <i class="fas fa-sign-out-alt"></i>
                                                                    {{ $timeOut->format('h:i A') }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <span class="badge badge-warning">Late</span>
                                                            </td>
                                                            <td>
                                                                <div class="action-buttons">
                                                                    <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-primary btn-sm">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                    <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6">
                                                            <div class="empty-state">
                                                                <i class="fas fa-clock"></i>
                                                                <p>No late attendance records found</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Absent Tab -->
                                <div class="tab-pane fade" id="absent" role="tabpanel" aria-labelledby="absent-tab">
                                    <div class="filters-container mt-3 mb-3">
                                        <div class="filter-item">
                                            <input type="date" id="absent-date-filter" class="filter-dropdown" placeholder="Filter by date">
                                        </div>
                                        <div class="filter-item">
                                            <select id="absent-department-filter" class="filter-dropdown">
                                                <option value="">All Departments</option>
                                                <option value="it">IT</option>
                                                <option value="hr">HR</option>
                                                <option value="finance">Finance</option>
                                                <option value="marketing">Marketing</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>EMPLOYEE</th>
                                                    <th>DATE</th>
                                                    <th>STATUS</th>
                                                    <th>REASON</th>
                                                    <th class="text-center">ACTIONS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- This section would be populated with absent data -->
                                                <tr>
                                                    <td colspan="5">
                                                        <div class="empty-state">
                                                            <i class="fas fa-user-times"></i>
                                                            <p>No absence records found</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            @if(isset($attendances) && $attendances->count() > 0)
                                <div class="float-right mt-3">
                                    {{ $attendances->withQueryString()->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Handle tab persistence
            const activeTab = localStorage.getItem('attendance_active_tab');
            if (activeTab) {
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }

            // Store the active tab in localStorage
            $('#myTab a').on('shown.bs.tab', function (e) {
                localStorage.setItem('attendance_active_tab', $(e.target).attr('href'));
            });

            // Date filter functionality for all tabs
            $('.filter-dropdown').on('change', function() {
                const tabId = $(this).closest('.tab-pane').attr('id');
                filterTable(tabId);
            });

            function filterTable(tabId) {
                const dateFilter = $('#' + tabId + '-date-filter').val();
                const departmentFilter = $('#' + tabId + '-department-filter').val();
                const rows = $('#' + tabId + ' tbody tr');

                rows.each(function() {
                    let showRow = true;

                    // Date filtering
                    if (dateFilter && showRow) {
                        const dateCell = $(this).find('td:nth-child(2)').text().trim();
                        const rowDate = new Date(dateCell);
                        const filterDate = new Date(dateFilter);

                        if (rowDate.toDateString() !== filterDate.toDateString()) {
                            showRow = false;
                        }
                    }

                    // Department filtering (simplified example - needs actual department data)
                    if (departmentFilter && showRow) {
                        // This would need to be implemented based on how department data is structured
                        // For now, it's a placeholder
                    }

                    $(this).toggle(showRow);
                });

                // Show empty state if no visible rows
                const visibleRows = $('#' + tabId + ' tbody tr:visible').length;
                if (visibleRows === 0) {
                    const emptyStateRow = `
                        <tr class="empty-filter-row">
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-filter"></i>
                                    <p>No records match your filter criteria</p>
                                    <button class="btn btn-light mt-3 reset-filter">
                                        <i class="fas fa-redo"></i> Reset Filters
                                    </button>
                                </div>
                            </td>
                        </tr>
                    `;

                    // Remove any existing empty filter rows
                    $('#' + tabId + ' .empty-filter-row').remove();

                    // Add empty state row if not already showing an empty state
                    if ($('#' + tabId + ' .empty-state').length === 0) {
                        $('#' + tabId + ' tbody').append(emptyStateRow);
                    }
                } else {
                    // Remove empty filter rows if we have results
                    $('#' + tabId + ' .empty-filter-row').remove();
                }
            }

            // Reset filters
            $(document).on('click', '.reset-filter', function() {
                const tabId = $(this).closest('.tab-pane').attr('id');
                $('#' + tabId + '-date-filter').val('');
                $('#' + tabId + '-department-filter').val('');
                filterTable(tabId);
            });

            // Confirm delete
            $('form').on('submit', function(e) {
                if ($(this).find('button[type="submit"]').hasClass('btn-danger')) {
                    if (!confirm('Are you sure you want to delete this attendance record?')) {
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
@endpush
