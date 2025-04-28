@extends('layouts.app')

@section('title', 'QR Absen Detail')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
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

        .qr-card {
            border: 1px solid #e3eaef;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .qr-card:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .qr-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f1f1;
        }

        .qr-card-header h5 {
            margin: 0;
            font-weight: 600;
            color: #34395e;
            display: flex;
            align-items: center;
        }

        .qr-card-header h5 i {
            margin-right: 8px;
            color: #6777ef;
        }

        .qr-code-container {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .qr-code-info {
            margin-top: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-code-info strong {
            margin-right: 5px;
            color: #6c757d;
        }

        .qr-code-value {
            font-family: monospace;
            color: #2d3748;
            background-color: #e9ecef;
            padding: 2px 6px;
            border-radius: 3px;
        }

        .qr-date-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            background-color: #e3f2fd;
            color: #2196f3;
            margin-left: 10px;
        }

        .btn-back {
            background-color: #f4f6f9;
            border-color: #f4f6f9;
            color: #6c757d;
            margin-right: 10px;
        }

        .btn-back:hover {
            background-color: #e9ecef;
            border-color: #e9ecef;
            color: #6c757d;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>QR Absen Detail</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="{{ route('qr_absens.index') }}">QR Absen</a></div>
                        <div class="breadcrumb-item">Detail</div>
                    </div>
                </div>
                <div>
                    <a href="{{ route('qr_absens.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left mr-1"></i>
                    </a>
                    <a href="{{ route('qr_absens.download', $qrAbsen->id) }}" class="btn btn-success">
                        <i class="fas fa-download mr-1"></i>Download PDF
                    </a>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>
                                    QR Absen
                                    <div class="qr-date-badge">
                                        <i class="fas fa-calendar-day mr-1"></i>
                                        {{ \Carbon\Carbon::parse($qrAbsen->date)->locale('id')->isoFormat('DD MMMM YYYY') }}
                                    </div>
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="qr-card">
                                            <div class="qr-card-header">
                                                <h5><i class="fas fa-sign-in-alt"></i> QR Check-in</h5>
                                            </div>
                                            <div class="qr-code-container text-center">
                                                {!! QrCode::size(200)->generate($qrAbsen->qr_checkin) !!}
                                            </div>
                                            <div class="qr-code-info">
                                                <strong>Code:</strong>
                                                <span class="qr-code-value">{{ $qrAbsen->qr_checkin }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="qr-card">
                                            <div class="qr-card-header">
                                                <h5><i class="fas fa-sign-out-alt"></i> QR Check-out</h5>
                                            </div>
                                            <div class="qr-code-container text-center">
                                                {!! QrCode::size(200)->generate($qrAbsen->qr_checkout) !!}
                                            </div>
                                            <div class="qr-code-info">
                                                <strong>Code:</strong>
                                                <span class="qr-code-value">{{ $qrAbsen->qr_checkout }}</span>
                                            </div>
                                        </div>
                                    </div>
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
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
@endpush
