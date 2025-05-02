@extends('layouts.app')

@section('title', 'Holiday Management')

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
        }

        .holiday-badge {
            padding: 0.25em 0.6em;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 20px;
            display: inline-block;
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

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 0.35rem 0.75rem;
            font-size: 0.75rem;
            border-radius: 4px;
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

        .tools-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }

        .tool-card {
            flex: 1;
            min-width: 300px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 20px;
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .tool-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .tool-card h5 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tool-card h5 i {
            font-size: 1.2rem;
        }

        .tool-card p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .tool-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .tool-form .form-group {
            margin-bottom: 0;
        }

        .tool-form label {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: var(--text-main);
        }

        .tool-form select,
        .tool-form input {
            height: 38px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            padding: 0 15px;
            width: 100%;
        }

        .tool-form .btn {
            height: 38px;
            border-radius: 6px;
            font-weight: 500;
        }

        .weekend-days-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
        }

        .weekend-day-checkbox {
            display: none;
        }

        .weekend-day-label {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .weekend-day-checkbox:checked + .weekend-day-label {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .empty-state {
            text-align: center;
            padding: 40px 0;
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
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

        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8fafc;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background-color: rgba(99, 102, 241, 0.05);
        }
    </style>
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
                    <a href="{{ route('holidays.calendar') }}" class="btn btn-light mr-2">
                        <i class="fas fa-calendar-alt"></i> Calendar View
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4><i class="fas fa-list mr-2"></i> Holidays List</h4>

                                <div class="filters-container">
                                    <form method="GET" action="{{ route('holidays.index') }}" class="d-flex gap-2">
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
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-filter mr-1"></i> Filter
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Name</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Recurring</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($holidays as $holiday)
                                                <tr>
                                                    <td>{{ $holiday->date->format('d M Y') }} ({{ $holiday->date->format('l') }})</td>
                                                    <td>{{ $holiday->name }}</td>
                                                    <td>
                                                        <span class="holiday-badge {{ $holiday->type }}">
                                                            {{ ucfirst($holiday->type) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $holiday->description ?? '-' }}</td>
                                                    <td>{{ $holiday->is_recurring ? 'Yes' : 'No' }}</td>
                                                    <td>
                                                        <div class="action-buttons">
                                                            <a href="{{ route('holidays.edit', $holiday->id) }}" class="btn btn-info action-btn">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-danger action-btn confirm-delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6">
                                                        <div class="empty-state">
                                                            <i class="fas fa-calendar-times"></i>
                                                            <p>No holidays found for this month</p>
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
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        // Initialize Select2
        $(document).ready(function() {
            $('.select2').select2();

            // Confirm delete functionality
            $('.confirm-delete').click(function(e) {
                if (!confirm('Are you sure you want to delete this holiday?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endpush
