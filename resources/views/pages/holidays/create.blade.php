@extends('layouts.app')

@section('title', 'Create Holiday')

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

        .form-section {
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .form-section-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-main);
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            padding: 1rem 0;
            margin-top: 1rem;
            border-top: 1px solid var(--border-color);
            gap: 10px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Create Holiday</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="{{ route('holidays.index') }}">Holidays</a></div>
                        <div class="breadcrumb-item">Create Holiday</div>
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-lg-8 offset-lg-2">
                        <div class="card">
                            <div class="card-header">
                                <h4>Holiday Information</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('holidays.store') }}" method="POST">
                                    @csrf

                                    <div class="form-section">
                                        <div class="form-section-title">Basic Information</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Holiday Name</label>
                                                    <input type="text" id="name"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" placeholder="Enter holiday name" value="{{ old('name') }}">
                                                    @error('name')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="date">Date</label>
                                                    <input type="text" id="date"
                                                        class="form-control datepicker @error('date') is-invalid @enderror"
                                                        name="date" placeholder="Select date" value="{{ old('date') }}">
                                                    @error('date')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="type">Holiday Type</label>
                                                    <select id="type" name="type" class="form-control @error('type') is-invalid @enderror">
                                                        <option value="national" {{ old('type') == 'national' ? 'selected' : '' }}>National Holiday</option>
                                                        <option value="company" {{ old('type') == 'company' ? 'selected' : '' }}>Company Holiday</option>
                                                    </select>
                                                    @error('type')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="d-block">Is Recurring</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="is_recurring" name="is_recurring" {{ old('is_recurring') ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_recurring">
                                                            This holiday repeats annually
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea id="description" name="description" class="form-control" style="height: 100px;">{{ old('description') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-footer">
                                        <a href="{{ route('holidays.index') }}" class="btn btn-light">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Create Holiday
                                        </button>
                                    </div>
                                </form>
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
        // Initialize datepicker
        $('.datepicker').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    </script>
@endpush
