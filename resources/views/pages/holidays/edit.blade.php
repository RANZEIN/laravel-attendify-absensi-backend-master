@extends('layouts.app')

@section('title', 'Edit Holiday')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-style.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Edit Holiday</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="{{ route('holidays.index') }}">Holidays</a></div>
                        <div class="breadcrumb-item">Edit Holiday</div>
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-lg-8 offset-lg-2">
                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fas fa-edit mr-2"></i>Edit Holiday Information</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('holidays.update', $holiday->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-section">
                                        <div class="form-section-title">Basic Information</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Holiday Name</label>
                                                    <input type="text" id="name"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" placeholder="Enter holiday name" value="{{ old('name', $holiday->name) }}">
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
                                                        name="date" placeholder="Select date" value="{{ old('date', $holiday->date->format('Y-m-d')) }}">
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
                                                    <select id="type" name="type" class="form-control select2 @error('type') is-invalid @enderror">
                                                        <option value="national" {{ old('type', $holiday->type) == 'national' ? 'selected' : '' }}>National Holiday</option>
                                                        <option value="company" {{ old('type', $holiday->type) == 'company' ? 'selected' : '' }}>Company Holiday</option>
                                                        <option value="weekend" {{ old('type', $holiday->type) == 'weekend' ? 'selected' : '' }}>Weekend</option>
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
                                                        <input class="form-check-input" type="checkbox" id="is_recurring" name="is_recurring" {{ old('is_recurring', $holiday->is_recurring) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="is_recurring">
                                                            This holiday repeats annually
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea id="description" name="description" class="form-control" style="height: 100px;" placeholder="Enter holiday description">{{ old('description', $holiday->description) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="form-footer">
                                        <a href="{{ route('holidays.index') }}" class="btn btn-light">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Update Holiday
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
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();

            // Initialize datepicker
            $('.datepicker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
        });
    </script>
@endpush
