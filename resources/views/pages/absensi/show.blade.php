@extends('layouts.app')

@section('title', 'Attendance Details')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Attendance Details</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Attendances</a></div>
                    <div class="breadcrumb-item">Attendance Details</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Attendance Information</h2>
                <p class="section-lead">
                    Below are the details of the attendance record.
                </p>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Name</label>
                                    <p class="form-control">{{ $attendance->user->name }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Date</label>
                                    <p class="form-control">{{ $attendance->date }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Time In</label>
                                    <p class="form-control">{{ $attendance->time_in }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Time Out</label>
                                    <p class="form-control">{{ $attendance->time_out }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Latlong In</label>
                                    <p class="form-control">{{ $attendance->latlon_in }}</p>
                                </div>
                                <div class="form-group">
                                    <label>Latlong Out</label>
                                    <p class="form-control">{{ $attendance->latlon_out }}</p>
                                </div>
                                <a href="{{ route('attendances.index') }}" class="btn btn-primary">Back to List</a>
                                <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-warning">Edit</a>
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
