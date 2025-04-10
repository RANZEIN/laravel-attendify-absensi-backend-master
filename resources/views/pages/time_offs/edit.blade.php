@extends('layouts.app')

@section('title', 'Edit Time Off Request')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Time Off Request</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('time_offs.index') }}">Time Off</a></div>
                    <div class="breadcrumb-item">Edit Time Off Request</div>
                </div>
            </div>
            <div class="section-body">
                <h2 class="section-title">Edit Time Off Request</h2>
                <p class="section-lead">
                    Update information about employee time off request.
                </p>

                <div class="row mt-sm-4">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <form method="POST" action="{{ route('time_offs.update', $timeOff->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="card-header">
                                    <h4>Employee Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label>Employee</label>
                                            <select name="user_id" class="form-control select2" required>
                                                <option value="">Select Employee</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ $timeOff->user_id == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }} ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('user_id')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label>Leave Type</label>
                                            <select name="type" class="form-control" required>
                                                <option value="">Select Leave Type</option>
                                                <option value="annual_leave" {{ $timeOff->type == 'annual_leave' ? 'selected' : '' }}>Annual Leave</option>
                                                <option value="sick_leave" {{ $timeOff->type == 'sick_leave' ? 'selected' : '' }}>Sick Leave</option>
                                                <option value="unpaid_leave" {{ $timeOff->type == 'unpaid_leave' ? 'selected' : '' }}>Unpaid Leave</option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label>Start Date</label>
                                            <input type="date" name="start_date" class="form-control" required value="{{ $timeOff->start_date }}">
                                            @error('start_date')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label>End Date</label>
                                            <input type="date" name="end_date" class="form-control" required value="{{ $timeOff->end_date }}">
                                            @error('end_date')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12 col-12">
                                            <label>Reason</label>
                                            <textarea name="reason" class="form-control" style="height: 100px" required>{{ $timeOff->reason }}</textarea>
                                            @error('reason')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6 col-12">
                                            <label>Supporting Document</label>
                                            @if($timeOff->document_url)
                                                <div class="mb-2">
                                                    <a href="{{ asset('storage/' . $timeOff->document_url) }}" target="_blank">
                                                        View Current Document
                                                    </a>
                                                </div>
                                            @endif
                                            <div class="custom-file">
                                                <input type="file" name="document" class="custom-file-input" id="customFile">
                                                <label class="custom-file-label" for="customFile">Choose file</label>
                                            </div>
                                            <small class="form-text text-muted">Leave empty to keep current document. Accepted file types: JPG, PNG, PDF. Max size: 2MB</small>
                                            @error('document')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6 col-12">
                                            <label>Status</label>
                                            <select name="status" class="form-control" required>
                                                <option value="pending" {{ $timeOff->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="approved" {{ $timeOff->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="rejected" {{ $timeOff->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <a href="{{ route('time_offs.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
    <script src="{{ asset('library/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();

            // Initialize custom file input
            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });

            // Date validation
            $('input[name="end_date"]').change(function() {
                var startDate = $('input[name="start_date"]').val();
                var endDate = $(this).val();

                if (startDate && endDate) {
                    if (endDate < startDate) {
                        alert('End date cannot be earlier than start date');
                        $(this).val('');
                    }
                }
            });

            $('input[name="start_date"]').change(function() {
                var startDate = $(this).val();
                var endDate = $('input[name="end_date"]').val();

                if (startDate && endDate) {
                    if (endDate < startDate) {
                        $('input[name="end_date"]').val('');
                    }
                }
            });
        });
    </script>
@endpush
