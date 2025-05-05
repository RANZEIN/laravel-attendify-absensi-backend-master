@extends('layouts.app')

@section('title', 'Holiday Management')

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
                    <h1>Holiday Management</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item">Holiday Management</div>
                    </div>
                </div>
                <div class="d-flex">
                    <a href="{{ route('holidays.calendar') }}" class="btn btn-primary mr-2">
                        <i class="fas fa-calendar-alt"></i> Calendar View
                    </a>
                    <a href="{{ route('holidays.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i> Add Holiday
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    @include('layouts.alert')
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <span class="stat-title">Total Holidays</span>
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ $holidays->count() }}</div>
                    <div class="stat-change positive">
                        <i class="fas fa-calendar"></i>
                        <span>This Month</span>
                    </div>
                </div>

                <div class="stat-card warning">
                    <div class="stat-header">
                        <span class="stat-title">National Holidays</span>
                        <div class="stat-icon warning">
                            <i class="fas fa-flag"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ $holidays->where('type', 'national')->count() }}</div>
                    <div class="stat-change">
                        <i class="fas fa-percentage"></i>
                        <span class="stat-change-text">{{ $holidays->count() > 0 ? round(($holidays->where('type', 'national')->count() / $holidays->count()) * 100) : 0 }}% of total</span>
                    </div>
                </div>

                <div class="stat-card success">
                    <div class="stat-header">
                        <span class="stat-title">Weekend Days</span>
                        <div class="stat-icon success">
                            <i class="fas fa-coffee"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ $holidays->where('type', 'weekend')->count() }}</div>
                    <div class="stat-change">
                        <i class="fas fa-calendar-week"></i>
                        <span class="stat-change-text">Weekend holidays</span>
                    </div>
                </div>

                <div class="stat-card info">
                    <div class="stat-header">
                        <span class="stat-title">Company Holidays</span>
                        <div class="stat-icon info">
                            <i class="fas fa-building"></i>
                        </div>
                    </div>
                    <div class="stat-value">{{ $holidays->where('type', 'company')->count() }}</div>
                    <div class="stat-change">
                        <i class="fas fa-briefcase"></i>
                        <span class="stat-change-text">Special company days</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-tools mr-2"></i> Holiday Tools</h4>
                        </div>
                        <div class="card-body">
                            <div class="tools-container">
                                <div class="tool-card">
                                    <h5><i class="fas fa-calendar-week"></i> Generate Weekend Holidays</h5>
                                    <p>Automatically mark selected days of the week as holidays for a specific year.</p>
                                    <form action="{{ route('holidays.generate-weekends') }}" method="POST" class="tool-form">
                                        @csrf
                                        <div class="form-group">
                                            <label for="year">Select Year</label>
                                            <input type="number" name="year" id="year" class="form-control" value="{{ date('Y') }}" min="1900" max="2100">
                                        </div>

                                        <div class="form-group">
                                            <label>Select Weekend Days</label>
                                            <div class="weekend-days-container">
                                                @php
                                                    $weekendDays = isset($weekendSettings) && $weekendSettings->weekend_days
                                                        ? json_decode($weekendSettings->weekend_days)
                                                        : [0, 6]; // Default to Sunday and Saturday
                                                @endphp

                                                <input type="checkbox" name="weekend_days[]" value="0" id="sunday" class="weekend-day-checkbox" {{ in_array(0, $weekendDays) ? 'checked' : '' }}>
                                                <label for="sunday" class="weekend-day-label">Sunday</label>

                                                <input type="checkbox" name="weekend_days[]" value="1" id="monday" class="weekend-day-checkbox" {{ in_array(1, $weekendDays) ? 'checked' : '' }}>
                                                <label for="monday" class="weekend-day-label">Monday</label>

                                                <input type="checkbox" name="weekend_days[]" value="2" id="tuesday" class="weekend-day-checkbox" {{ in_array(2, $weekendDays) ? 'checked' : '' }}>
                                                <label for="tuesday" class="weekend-day-label">Tuesday</label>

                                                <input type="checkbox" name="weekend_days[]" value="3" id="wednesday" class="weekend-day-checkbox" {{ in_array(3, $weekendDays) ? 'checked' : '' }}>
                                                <label for="wednesday" class="weekend-day-label">Wednesday</label>

                                                <input type="checkbox" name="weekend_days[]" value="4" id="thursday" class="weekend-day-checkbox" {{ in_array(4, $weekendDays) ? 'checked' : '' }}>
                                                <label for="thursday" class="weekend-day-label">Thursday</label>

                                                <input type="checkbox" name="weekend_days[]" value="5" id="friday" class="weekend-day-checkbox" {{ in_array(5, $weekendDays) ? 'checked' : '' }}>
                                                <label for="friday" class="weekend-day-label">Friday</label>

                                                <input type="checkbox" name="weekend_days[]" value="6" id="saturday" class="weekend-day-checkbox" {{ in_array(6, $weekendDays) ? 'checked' : '' }}>
                                                <label for="saturday" class="weekend-day-label">Saturday</label>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-magic mr-1"></i> Generate
                                        </button>
                                    </form>
                                </div>
                                <div class="tool-card">
                                    <h5><i class="fas fa-flag"></i> Import National Holidays</h5>
                                    <p>Import Indonesian national holidays for any year. This will add common holidays like Independence Day, New Year, etc.</p>
                                    <form action="{{ route('holidays.import-national') }}" method="POST" class="tool-form">
                                        @csrf
                                        <div class="form-group">
                                            <label for="import-year">Select Year</label>
                                            <input type="number" name="year" id="import-year" class="form-control" value="{{ date('Y') }}" min="1900" max="2100">
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-download mr-1"></i> Import
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>All Holidays</h4>
                            <div class="card-header-form">
                                <form method="GET" action="{{ route('holidays.index') }}" class="d-flex">
                                    <div class="input-group">
                                        <select name="month" class="form-control select2">
                                            @foreach($months as $key => $monthName)
                                                <option value="{{ $key }}" {{ $key == $month ? 'selected' : '' }}>{{ $monthName }}</option>
                                            @endforeach
                                        </select>
                                        <select name="year" class="form-control select2 ml-2">
                                            @php
                                                $currentYear = date('Y');
                                                $yearRange = range($currentYear - 10, $currentYear + 10);
                                            @endphp
                                            @foreach($yearRange as $y)
                                                <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-btn">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-filter"></i>
                                            </button>
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
                                    <a class="nav-link" id="national-tab" data-toggle="tab" href="#national" role="tab" aria-controls="national" aria-selected="false">National</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="company-tab" data-toggle="tab" href="#company" role="tab" aria-controls="company" aria-selected="false">Company</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="weekend-tab" data-toggle="tab" href="#weekend" role="tab" aria-controls="weekend" aria-selected="false">Weekend</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <!-- All Holidays Tab -->
                                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Name</th>
                                                    <th></th>
                                                    <th>Description</th>
                                                    <th>Recurring</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($holidays as $holiday)
                                                    <tr>
                                                        <td>
                                                            <div class="font-weight-bold">{{ $holiday->date->format('d M Y') }}</div>
                                                            <div class="text-muted small">{{ $holiday->date->format('l') }}</div>
                                                        </td>
                                                        <td>{{ $holiday->name }}</td>
                                                        <td>
                                                            @if($holiday->type == 'national')
                                                                <span class="holiday-badge national">National</span>
                                                            @elseif($holiday->type == 'company')
                                                                <span class="holiday-badge company">Company</span>
                                                            @elseif($holiday->type == 'weekend')
                                                                <span class="holiday-badge weekend">Weekend</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $holiday->description ?? '-' }}</td>
                                                        <td>
                                                            @if($holiday->is_recurring)
                                                                <span class="badge badge-success">Yes</span>
                                                            @else
                                                                <span class="badge badge-danger">No</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <a href="{{ route('holidays.edit', $holiday->id) }}" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this holiday?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5">
                                                            <div class="empty-state">
                                                                <i class="fas fa-calendar-times"></i>
                                                                <p>No holidays found for this period</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- National Holidays Tab -->
                                <div class="tab-pane fade" id="national" role="tabpanel" aria-labelledby="national-tab">
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Recurring</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $nationalHolidays = $holidays->where('type', 'national') @endphp
                                                @forelse ($nationalHolidays as $holiday)
                                                    <tr>
                                                        <td>
                                                            <div class="font-weight-bold">{{ $holiday->date->format('d M Y') }}</div>
                                                            <div class="text-muted small">{{ $holiday->date->format('l') }}</div>
                                                        </td>
                                                        <td>{{ $holiday->name }}</td>
                                                        <td>{{ $holiday->description ?? '-' }}</td>
                                                        <td>
                                                            @if($holiday->is_recurring)
                                                                <span class="badge badge-success">Yes</span>
                                                            @else
                                                                <span class="badge badge-danger">No</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <a href="{{ route('holidays.edit', $holiday->id) }}" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this holiday?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5">
                                                            <div class="empty-state">
                                                                <i class="fas fa-flag"></i>
                                                                <p>No national holidays found</p>
                                                                <button class="btn btn-primary mt-3" data-toggle="modal" data-target="#importModal">
                                                                    <i class="fas fa-download"></i> Import National Holidays
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Company Holidays Tab -->
                                <div class="tab-pane fade" id="company" role="tabpanel" aria-labelledby="company-tab">
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Recurring</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $companyHolidays = $holidays->where('type', 'company') @endphp
                                                @forelse ($companyHolidays as $holiday)
                                                    <tr>
                                                        <td>
                                                            <div class="font-weight-bold">{{ $holiday->date->format('d M Y') }}</div>
                                                            <div class="text-muted small">{{ $holiday->date->format('l') }}</div>
                                                        </td>
                                                        <td>{{ $holiday->name }}</td>
                                                        <td>{{ $holiday->description ?? '-' }}</td>
                                                        <td>
                                                            @if($holiday->is_recurring)
                                                                <span class="badge badge-success">Yes</span>
                                                            @else
                                                                <span class="badge badge-danger">No</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <a href="{{ route('holidays.edit', $holiday->id) }}" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this holiday?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5">
                                                            <div class="empty-state">
                                                                <i class="fas fa-building"></i>
                                                                <p>No company holidays found</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Weekend Holidays Tab -->
                                <div class="tab-pane fade" id="weekend" role="tabpanel" aria-labelledby="weekend-tab">
                                    <div class="table-container">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Recurring</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $weekendHolidays = $holidays->where('type', 'weekend') @endphp
                                                @forelse ($weekendHolidays as $holiday)
                                                    <tr>
                                                        <td>
                                                            <div class="font-weight-bold">{{ $holiday->date->format('d M Y') }}</div>
                                                            <div class="text-muted small">{{ $holiday->date->format('l') }}</div>
                                                        </td>
                                                        <td>{{ $holiday->name }}</td>
                                                        <td>{{ $holiday->description ?? '-' }}</td>
                                                        <td>
                                                            @if($holiday->is_recurring)
                                                                <span class="badge badge-success">Yes</span>
                                                            @else
                                                                <span class="badge badge-danger">No</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="action-buttons">
                                                                <a href="{{ route('holidays.edit', $holiday->id) }}" class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this holiday?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5">
                                                            <div class="empty-state">
                                                                <i class="fas fa-coffee"></i>
                                                                <p>No weekend holidays found</p>
                                                                <a href="#" class="btn btn-primary mt-3" onclick="document.getElementById('weekend-generator-form').scrollIntoView({ behavior: 'smooth' })">
                                                                    <i class="fas fa-calendar-week"></i> Generate Weekend Holidays
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
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

            // Handle tab persistence
            const activeTab = localStorage.getItem('holiday_active_tab');
            if (activeTab) {
                $('#myTab a[href="' + activeTab + '"]').tab('show');
            }

            // Store the active tab in localStorage
            $('#myTab a').on('shown.bs.tab', function (e) {
                localStorage.setItem('holiday_active_tab', $(e.target).attr('href'));
            });

            // Confirm delete
            $('form').on('submit', function(e) {
                if ($(this).find('button[type="submit"]').hasClass('btn-danger')) {
                    if (!confirm('Are you sure you want to delete this holiday?')) {
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
@endpush
