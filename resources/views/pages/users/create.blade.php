@extends('layouts.app')

@section('title', 'Create User')

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
                    <h1>Create New User</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></div>
                        <div class="breadcrumb-item">Create User</div>
                    </div>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Users
                </a>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-8 offset-lg-2">
                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fas fa-user-plus mr-2"></i>User Information</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('users.store') }}" method="POST">
                                    @csrf

                                    <div class="form-section">
                                        <div class="form-section-title">Personal Information</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Full Name</label>
                                                    <input type="text" id="name"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ old('name') }}" placeholder="Enter full name">
                                                    @error('name')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="email">Email Address</label>
                                                    <input type="email" id="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        name="email" value="{{ old('email') }}" placeholder="Enter email address">
                                                    @error('email')
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
                                                    <label for="password">Password</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-lock"></i>
                                                            </div>
                                                        </div>
                                                        <input type="password" id="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            name="password" placeholder="Enter password">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-light toggle-password">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @error('password')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone">Phone Number</label>
                                                    <input type="text" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                                        name="phone" value="{{ old('phone') }}" placeholder="Enter phone number">
                                                    @error('phone')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-section">
                                        <div class="form-section-title">Job Information</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="position">Position</label>
                                                    <input type="text" id="position"
                                                        class="form-control @error('position') is-invalid @enderror"
                                                        name="position" value="{{ old('position') }}" placeholder="Enter job position">
                                                    @error('position')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="department">Department</label>
                                                    <select id="department" name="department" class="form-control select2 @error('department') is-invalid @enderror">
                                                        <option value="">Select Department</option>
                                                        <option value="Engineering" {{ old('department') == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                                        <option value="Marketing" {{ old('department') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                                        <option value="HR" {{ old('department') == 'HR' ? 'selected' : '' }}>HR</option>
                                                        <option value="Finance" {{ old('department') == 'Finance' ? 'selected' : '' }}>Finance</option>
                                                        <option value="Operations" {{ old('department') == 'Operations' ? 'selected' : '' }}>Operations</option>
                                                        <option value="Sales" {{ old('department') == 'Sales' ? 'selected' : '' }}>Sales</option>
                                                        <option value="Customer Support" {{ old('department') == 'Customer Support' ? 'selected' : '' }}>Customer Support</option>
                                                        <option value="IT" {{ old('department') == 'IT' ? 'selected' : '' }}>IT</option>
                                                    </select>
                                                    @error('department')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="d-block">User Role</label>
                                            <div class="selectgroup selectgroup-pills">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="role" value="admin" class="selectgroup-input"
                                                        {{ old('role') == 'admin' ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">
                                                        <i class="fas fa-shield-alt mr-1"></i> Admin
                                                    </span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="role" value="supervisor" class="selectgroup-input"
                                                        {{ old('role') == 'supervisor' ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">
                                                        <i class="fas fa-user-tie mr-1"></i> Supervisor
                                                    </span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="role" value="staff" class="selectgroup-input"
                                                        {{ old('role') == 'staff' ? 'checked' : '' || old('role') == null }}>
                                                    <span class="selectgroup-button">
                                                        <i class="fas fa-user mr-1"></i> Staff
                                                    </span>
                                                </label>
                                            </div>
                                            @error('role')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-section">
                                        <div class="form-section-title">Leave Allowance</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="leave_allowance">Annual Leave Allowance (Days)</label>
                                                    <input type="number" id="leave_allowance"
                                                        class="form-control @error('leave_allowance') is-invalid @enderror"
                                                        name="leave_allowance" value="{{ old('leave_allowance', 12) }}" min="0" max="30">
                                                    @error('leave_allowance')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="leave_period_start">Leave Period Start Date</label>
                                                    <input type="text" id="leave_period_start"
                                                        class="form-control datepicker @error('leave_period_start') is-invalid @enderror"
                                                        name="leave_period_start" value="{{ old('leave_period_start', now()->format('Y-m-d')) }}">
                                                    @error('leave_period_start')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-footer">
                                        <a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Create User
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

            // Toggle password visibility
            document.querySelector('.toggle-password').addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>
@endpush
