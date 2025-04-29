@extends('layouts.app')

@section('title', 'Attendances')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <style>
        .section-header {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
            background-color: #fff;
            border-radius: 3px;
            border: none;
            position: relative;
            margin-bottom: 30px;
            padding: 20px;
            display: flex;
            align-items: center;
        }

        .section-header h1 {
            margin-bottom: 0;
            font-weight: 700;
            display: inline-block;
            font-size: 24px;
            margin-top: 3px;
            color: #34395e;
        }

        .section-header-breadcrumb {
            margin-left: auto;
            display: flex;
            align-items: center;
        }

        .section-header-breadcrumb .breadcrumb-item {
            font-size: 12px;
        }

        .section-title {
            font-size: 18px;
            color: #191d21;
            font-weight: 600;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .section-lead {
            margin-top: -5px;
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
            background-color: #fff;
            border-radius: 3px;
            border: none;
            position: relative;
            margin-bottom: 30px;
        }

        .card-header {
            border-bottom-color: #f9f9f9;
            line-height: 30px;
            align-items: center;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
        }

        .card-header h4 {
            font-size: 16px;
            line-height: 28px;
            padding-right: 10px;
            margin-bottom: 0;
            font-weight: 600;
            color: #34395e;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            color: #212529;
            background-color: transparent;
            border-collapse: collapse;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .table th {
            padding: 12px 15px;
            border-top: none;
            font-weight: 600;
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }

        .table td {
            padding: 12px 15px;
            vertical-align: middle;
            border-top: 1px solid #e3eaef;
        }

        .input-group {
            position: relative;
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            width: 100%;
        }

        .input-group > .form-control {
            position: relative;
            flex: 1 1 auto;
            width: 1%;
            margin-bottom: 0;
            height: 42px;
            padding: 0 15px;
            border-radius: 3px 0 0 3px;
            font-size: 14px;
        }

        .input-group-append .btn {
            border-radius: 0 3px 3px 0;
            padding: 0 15px;
        }

        .btn-primary {
            background-color: #6777ef;
            border-color: #6777ef;
        }

        .btn-info {
            background-color: #3abaf4;
            border-color: #3abaf4;
            color: white;
        }

        .btn-danger {
            background-color: #fc544b;
            border-color: #fc544b;
        }

        .btn-sm {
            height: 30px;
            padding: 0 10px;
            font-size: 12px;
        }

        .filters {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-item {
            flex: 1;
            min-width: 200px;
        }

        .filter-item label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #666;
            margin-bottom: 5px;
        }

        .filter-item select, .filter-item input {
            width: 100%;
            padding: 8px 12px;
            border-radius: 3px;
            border: 1px solid #e3eaef;
            font-size: 13px;
        }

        .employee-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #6777ef;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 12px;
            margin-right: 10px;
        }

        .employee-name {
            display: flex;
            align-items: center;
        }

        .time-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            background-color: #eef2ff;
            color: #6777ef;
        }

        .time-badge.am {
            background-color: #e3f2fd;
            color: #2196f3;
        }

        .time-badge.pm {
            background-color: #fff8e1;
            color: #ffc107;
        }

        .table-action {
            display: flex;
            gap: 5px;
        }

        .table-action .btn {
            width: 30px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pagination {
            margin-top: 15px;
        }

        .page-header-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 700;
            color: #34395e;
            margin: 0;
        }

        .page-breadcrumb {
            display: flex;
            font-size: 12px;
            color: #868e96;
        }

        .page-breadcrumb a {
            color: #868e96;
        }

        .page-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            content: "/";
            padding: 0 5px;
        }
    </style>
@endpush

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
                                <div class="filters">
                                    <div class="filter-item">
                                        <label for="date-filter">Filter by Date</label>
                                        <input type="date" id="date-filter" class="form-control">
                                    </div>
                                    <div class="filter-item">
                                        <label for="status-filter">Filter by Status</label>
                                        <select id="status-filter" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="on-time">On Time</option>
                                            <option value="late">Late</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>NAME</th>
                                                <th>DATE</th>
                                                <th>TIME IN</th>
                                                <th>TIME OUT</th>
                                                {{-- <th>LATLONG IN</th>
                                                <th>LATLONG OUT</th> --}}
                                                <th>ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($attendances) && $attendances->count() > 0)
                                            @foreach ($attendances as $attendance)
                                                <tr>
                                                    <td>
                                                        <div class="employee-name">
                                                            <div class="employee-avatar">
                                                                {{ substr($attendance->user->name, 0, 1) }}
                                                            </div>
                                                            <div>{{ $attendance->user->name }}</div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($attendance->date)->format('M d, Y') }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $timeIn = \Carbon\Carbon::parse($attendance->time_in);
                                                            $isPM = $timeIn->format('A') === 'PM';
                                                        @endphp
                                                        <div class="time-badge {{ $isPM ? 'pm' : 'am' }}">
                                                            <i class="fas fa-sign-in-alt mr-1"></i>
                                                            {{ $timeIn->format('h:i A') }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $timeOut = \Carbon\Carbon::parse($attendance->time_out);
                                                            $isPM = $timeOut->format('A') === 'PM';
                                                        @endphp
                                                        <div class="time-badge {{ $isPM ? 'pm' : 'am' }}">
                                                            <i class="fas fa-sign-out-alt mr-1"></i>
                                                            {{ $timeOut->format('h:i A') }}
                                                        </div>
                                                    </td>
                                                    {{-- <td>
                                                        {{ $attendance->latlon_in }}
                                                    </td>
                                                    <td>
                                                        {{ $attendance->latlon_out }}
                                                    </td> --}}
                                                    <td>
                                                        <div class="table-action">
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
                                                <td colspan="7" class="text-center py-4">
                                                    <i class="fas fa-calendar-times mb-3" style="font-size: 40px; color: #cdd3d8;"></i>
                                                    <h5 style="font-size: 16px; color: #6c757d;">No Attendance Records Found</h5>
                                                    <p style="color: #6c757d; max-width: 400px; margin: 0 auto;">There are no attendance records matching your search criteria.</p>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>

                                @if(isset($attendances) && $attendances->count() > 0)
                                    <div class="float-right mt-4">
                                        {{ $attendances->withQueryString()->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Date filter functionality
            const dateFilter = document.getElementById('date-filter');
            dateFilter.addEventListener('change', function() {
                filterTable();
            });

            // Status filter functionality
            const statusFilter = document.getElementById('status-filter');
            statusFilter.addEventListener('change', function() {
                filterTable();
            });

            function filterTable() {
                const dateValue = dateFilter.value;
                const statusValue = statusFilter.value;
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    let showRow = true;

                    // Date filtering
                    if (dateValue) {
                        const dateCell = row.cells[1].textContent.trim();
                        const rowDate = new Date(dateCell);
                        const filterDate = new Date(dateValue);

                        if (rowDate.toDateString() !== filterDate.toDateString()) {
                            showRow = false;
                        }
                    }

                    // Status filtering (simplified example)
                    if (statusValue && showRow) {
                        const timeInCell = row.cells[2].textContent.trim();
                        const timeIn = timeInCell.replace(/[^0-9:]/g, '');

                        // Assuming 8:00 AM is the cutoff
                        const isLate = timeIn > '08:00';

                        if ((statusValue === 'late' && !isLate) || (statusValue === 'on-time' && isLate)) {
                            showRow = false;
                        }
                    }

                    row.style.display = showRow ? '' : 'none';
                });
            }
        });
    </script>
@endpush
