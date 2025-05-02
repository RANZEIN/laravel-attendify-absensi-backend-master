@extends('layouts.app')

@section('title', 'Generate QR Absen')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
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

        .form-group label {
            font-weight: 600;
            font-size: 13px;
            color: #34395e;
        }

        .form-control {
            border-color: #e4e6fc;
            font-size: 14px;
            padding: 10px 15px;
            height: 42px;
        }

        .form-control:focus {
            border-color: #6777ef;
        }

        .card-footer {
            padding: 15px 25px;
        }

        .btn-primary {
            background-color: #6777ef;
            border-color: #6777ef;
        }

        .btn-primary:hover {
            background-color: #5a67d8;
            border-color: #5a67d8;
        }

        .btn-back {
            background-color: #f4f6f9;
            border-color: #f4f6f9;
            color: #6c757d;
        }

        .btn-back:hover {
            background-color: #e9ecef;
            border-color: #e9ecef;
            color: #6c757d;
        }

        .generate-info {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #6777ef;
        }

        .generate-info h6 {
            color: #34395e;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .generate-info p {
            color: #6c757d;
            margin-bottom: 0;
            font-size: 13px;
            line-height: 1.5;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Generate QR Absen</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item active"><a href="{{ route('qr_absens.index') }}">QR Absen</a></div>
                        <div class="breadcrumb-item">Generate QR Absen</div>
                    </div>
                </div>
                {{-- <a href="{{ route('qr_absens.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-1"></i> Back to QR Absen
                </a> --}}
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-8 col-lg-6 mx-auto">
                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fas fa-qrcode mr-1"></i> Generate QR Code</h4>
                            </div>
                            <div class="card-body">
                                <div class="generate-info">
                                    <h6><i class="fas fa-info-circle mr-1"></i> Information</h6>
                                    <p>Select a month to generate QR codes for attendance. The system will create unique QR codes for daily check-in and check-out for each day of the selected month.</p>
                                </div>

                                <form action="{{ route('qr_absens.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="month">Select Month <span class="text-danger">*</span></label>
                                        <input type="month" id="month" name="month" class="form-control" required value="{{ date('Y-m') }}">
                                        <small class="form-text text-muted">Choose the month for which you want to generate QR codes.</small>
                                    </div>

                                    <div class="form-group text-right">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-qrcode mr-1"></i> Generate QR Codes
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
    <script src="{{ asset('library/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('library/selectric/public/jquery.selectric.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Set default month to current month
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            $('#month').val(`${year}-${month}`);
        });
    </script>
@endpush
