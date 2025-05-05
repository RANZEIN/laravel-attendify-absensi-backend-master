@extends('layouts.app')

@section('title', 'Edit Attendance')

@push('style')
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <style>
        .section-header {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
            background-color: #fff;
            border-radius: 3px;
            border: none;
            position: relative;
            margin-bottom: 30px;
            padding: 20px;
            display: flex;
            align-items: center;
        }

        .section-header h1 {
            margin-bottom: 0;
            font-weight: 700;
            display: inline-block;
            font-size: 24px;
            margin-top: 3px;
            color: #34395e;
        }

        .section-header-breadcrumb {
            margin-left: auto;
            display: flex;
            align-items: center;
        }

        .section-header-breadcrumb .breadcrumb-item {
            font-size: 12px;
        }

        .section-title {
            font-size: 18px;
            color: #191d21;
            font-weight: 600;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .section-lead {
            margin-top: -5px;
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 15px;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
            background-color: #fff;
            border-radius: 3px;
            border: none;
            position: relative;
            margin-bottom: 30px;
        }

        .card-header {
            border-bottom-color: #f9f9f9;
            line-height: 30px;
            align-items: center;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
        }

        .card-header h4 {
            font-size: 16px;
            line-height: 28px;
            padding-right: 10px;
            margin-bottom: 0;
            font-weight: 600;
            color: #34395e;
        }

        .card-body {
            padding: 20px 25px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-control {
            height: 42px;
            border: 1px solid #e3eaef;
            border-radius: 3px;
            font-size: 14px;
            padding: 10px 15px;
            background-color: #fdfdff;
            color: #495057;
        }

        .form-control:focus {
            border-color: #6777ef;
            box-shadow: none;
        }

        label {
            font-weight: 600;
            color: #34395e;
            font-size: 12px;
            letter-spacing: .5px;
        }

        .btn-primary {
            background-color: #6777ef;
            border-color: #6777ef;
        }

        .btn-danger {
            background-color: #fc544b;
            border-color: #fc544b;
        }

        .btn i {
            margin-right: 5px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Attendance</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Attendances</a></div>
                    <div class="breadcrumb-item">Edit Attendance</div>
                </div>
            </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Attendance Form</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('attendances.update', $attendance->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label>Employee Name</label>
                                        <input type="text" class="form-control" value="{{ $attendance->user->name }}" disabled>
                                    </div>

                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="date" class="form-control @error('date') is-invalid @enderror"
                                            id="date" name="date" value="{{ old('date', $attendance->date) }}">
                                        @error('date')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="time_in">Time In</label>
                                                <input type="time" class="form-control @error('time_in') is-invalid @enderror"
                                                    id="time_in" name="time_in" value="{{ old('time_in', $attendance->time_in) }}">
                                                @error('time_in')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="time_out">Time Out</label>
                                                <input type="time" class="form-control @error('time_out') is-invalid @enderror"
                                                    id="time_out" name="time_out" value="{{ old('time_out', $attendance->time_out) }}">
                                                @error('time_out')
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
                                                <label for="latlon_in">Location In (Latitude, Longitude)</label>
                                                <input type="text" class="form-control @error('latlon_in') is-invalid @enderror"
                                                    id="latlon_in" name="latlon_in" value="{{ old('latlon_in', $attendance->latlon_in) }}">
                                                @error('latlon_in')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="latlon_out">Location Out (Latitude, Longitude)</label>
                                                <input type="text" class="form-control @error('latlon_out') is-invalid @enderror"
                                                    id="latlon_out" name="latlon_out" value="{{ old('latlon_out', $attendance->latlon_out) }}">
                                                @error('latlon_out')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="action-buttons">
                                        <a href="{{ route('attendances.index') }}" class="btn btn-danger">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Attendance
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
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>
@endpush
