@extends('layouts.app')

@section('title', 'Holiday Management')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
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
            gap: 10px;
            margin-bottom: 20px;
        }

        .tool-card {
            flex: 1;
            min-width: 250px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            padding: 15px;
        }

        .tool-card h5 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .tool-card p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 15px;
        }

        .tool-form {
            display: flex;
            gap: 10px;
        }

        .tool-form select,
        .tool-form input {
            flex: 1;
            height: 38px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
            padding: 0 15px;
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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>Holiday Tools</h4>
                            </div>
                            <div class="card-body">
                                <div class="tools-container">
                                    <div class="tool-card">
                                        <h5>Generate Weekend Holidays</h5>
                                        <p>Automatically mark all Saturdays and Sundays as holidays for a specific year.</p>
                                        <form action="{{ route('holidays.generate-weekends') }}" method="POST" class="tool-form">
                                            @csrf
                                            <select name="year" class="form-control">
                                                @foreach($years as $y)
                                                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary">Generate</button>
                                        </form>
                                    </div>
                                    <div class="tool-card">
                                        <h5>Import National Holidays</h5>
                                        <p>Import Indonesian national holidays for a specific year.</p>
                                        <form action="{{ route('holidays.import-national') }}" method="POST" class="tool-form">
                                            @csrf
                                            <select name="year" class="form-control">
                                                @foreach($years as $y)
                                                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary">Import</button>
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
                                <h4>Holidays List</h4>

                                <div class="filters-container">
                                    <form method="GET" action="{{ route('holidays.index') }}" class="d-flex gap-2">
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
    <script>
        // Confirm delete functionality
        $('.confirm-delete').click(function(e) {
            if (!confirm('Are you sure you want to delete this holiday?')) {
                e.preventDefault();
            }
        });
    </script>
@endpush
