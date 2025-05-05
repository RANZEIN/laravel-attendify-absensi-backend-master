@extends('layouts.app')

@section('title', 'Edit User')

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
                    <h1>Edit User</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></div>
                        <div class="breadcrumb-item">Edit User</div>
                    </div>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-light">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Users
                </a>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-lg-8 offset-lg-2">
                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fas fa-user-edit mr-2"></i>Edit User Information</h4>
                                <span class="last-login">Last updated: {{ $user->updated_at->diffForHumans() }}</span>
                            </div>
                            <div class="card-body">
                                <div class="text-center mb-4">
                                    <div class="user-avatar mx-auto">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <h5 class="mb-0">{{ $user->name }}</h5>
                                    <div class="mt-1">
                                        <span class="user-status {{ $user->role }}">{{ ucfirst($user->role) }}</span>
                                    </div>
                                </div>

                                <form action="{{ route('users.update', $user) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-section">
                                        <div class="form-section-title">Personal Information</div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Full Name</label>
                                                    <input type="text" id="name"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        name="name" value="{{ $user->name }}">
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
                                                        name="email" value="{{ $user->email }}">
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
                                                    <label for="password">New Password</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">
                                                                <i class="fas fa-lock"></i>
                                                            </div>
                                                        </div>
                                                        <input type="password" id="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            name="password" placeholder="Leave blank to keep current">
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-light toggle-password">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="password-info mt-2">
                                                        <i class="fas fa-info-circle mr-1"></i>
                                                        Leave password field empty if you don't want to change it.
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
                                                    <input type="text" id="phone" class="form-control"
                                                        name="phone" value="{{ $user->phone }}">
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
                                                        name="position" value="{{ $user->position }}">
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
                                                        <option value="Engineering" {{ $user->department == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                                        <option value="Marketing" {{ $user->department == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                                        <option value="HR" {{ $user->department == 'HR' ? 'selected' : '' }}>HR</option>
                                                        <option value="Finance" {{ $user->department == 'Finance' ? 'selected' : '' }}>Finance</option>
                                                        <option value="Operations" {{ $user->department == 'Operations' ? 'selected' : '' }}>Operations</option>
                                                        <option value="Sales" {{ $user->department == 'Sales' ? 'selected' : '' }}>Sales</option>
                                                        <option value="Customer Support" {{ $user->department == 'Customer Support' ? 'selected' : '' }}>Customer Support</option>
                                                        <option value="IT" {{ $user->department == 'IT' ? 'selected' : '' }}>IT</option>
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
                                                        @if ($user->role == 'admin') checked @endif>
                                                    <span class="selectgroup-button">
                                                        <i class="fas fa-shield-alt mr-1"></i> Admin
                                                    </span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="role" value="supervisor" class="selectgroup-input"
                                                        @if ($user->role == 'supervisor') checked @endif>
                                                    <span class="selectgroup-button">
                                                        <i class="fas fa-user-tie mr-1"></i> Supervisor
                                                    </span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="role" value="staff" class="selectgroup-input"
                                                        @if ($user->role == 'staff') checked @endif>
                                                    <span class="selectgroup-button">
                                                        <i class="fas fa-user mr-1"></i> Staff
                                                    </span>
                                                </label>
                                            </div>
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
                                                        name="leave_allowance" value="{{ $user->leave_allowance ?? 12 }}" min="0" max="30">
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
                                                        name="leave_period_start" value="{{ $user->leave_period_start ?? now()->format('Y-m-d') }}">
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
                                            <i class="fas fa-save mr-1"></i> Update User
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Danger Zone Card -->
                        <div class="card bg-light">
                            <div class="card-header bg-light">
                                <h4 class="text-danger">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Danger Zone
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Delete this user</h6>
                                        <p class="text-muted mb-0 small">Once deleted, this user will be permanently removed.</p>
                                    </div>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash mr-1"></i> Delete User
                                        </button>
                                    </form>
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
