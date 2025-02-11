@extends('layouts.app')

@section('title', 'Edit Attendance')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Attendance</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Attendances</a></div>
                    <div class="breadcrumb-item">Edit Attendance</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Edit Attendance</h2>
                <p class="section-lead">
                    Update the attendance details below.
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('attendances.update', $attendance->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <!-- Hidden User ID -->
                                    <input type="hidden" name="user_id" value="{{ $attendance->user_id ?? '' }}">

                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" value="{{ $attendance->user->name ?? '' }}" disabled>
                                    </div>

                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control" id="date" name="date" value="{{ $attendance->date ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="time_in">Time In</label>
                                        <input type="time" class="form-control" id="time_in" name="time_in" value="{{ $attendance->time_in ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="time_out">Time Out</label>
                                        <input type="time" class="form-control" id="time_out" name="time_out" value="{{ $attendance->time_out ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="latlon_in">Latlong In</label>
                                        <input type="text" class="form-control" id="latlon_in" name="latlon_in" value="{{ $attendance->latlon_in ?? '' }}">
                                    </div>

                                    <div class="form-group">
                                        <label for="latlon_out">Latlong Out</label>
                                        <input type="text" class="form-control" id="latlon_out" name="latlon_out" value="{{ $attendance->latlon_out ?? '' }}">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Cancel</a>
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
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
@endpush
