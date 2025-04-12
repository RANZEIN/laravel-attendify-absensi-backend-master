<!-- show.blade.php -->
@extends('layouts.app')

@section('title', 'Company Profile')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
    <style>
        .company-profile-header {
            background: linear-gradient(to right, #6777ef, #3abaf4);
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            color: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .company-logo {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .company-logo img {
            max-width: 80%;
            max-height: 80%;
        }

        .info-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            transition: transform 0.3s;
        }

        .info-card:hover {
            transform: translateY(-5px);
        }

        .info-card-header {
            padding: 15px 20px;
            border-bottom: 1px solid #f9f9f9;
            display: flex;
            align-items: center;
        }

        .info-card-header i {
            font-size: 16px;
            margin-right: 15px;
            color: #6777ef;
        }

        .info-card-body {
            padding: 20px;
        }

        .info-label {
            color: #6c757d;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .info-value {
            color: #34395e;
            font-size: 14px;
            font-weight: 500;
        }

        .map-container {
            height: 300px;
            background: #f4f6f9;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 25px;
        }

        .badge-company-type {
            background: #6777ef;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .edit-btn {
            padding: 10px 25px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 2px 6px rgba(103, 119, 239, 0.3);
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Company Profile</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item">Company Profile</div>
                </div>
            </div>

            <div class="section-body">
                <!-- Company Profile Header -->
                <div class="company-profile-header d-flex align-items-center">
                    <div class="company-logo">
                        <img src="{{ asset('img/stisla-fill.svg') }}" alt="{{ $company->name }} Logo">
                    </div>
                    <div>
                        <h2 class="mb-1">{{ $company->name }}</h2>
                        <p class="mb-0">{{ $company->address }}</p>
                        <span class="badge badge-company-type mt-2">{{ $company->attendance_type }} Attendance</span>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-light edit-btn">
                            <i class="fas fa-pencil-alt mr-1"></i> Edit Profile
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- Contact Information -->
                    <div class="col-lg-4">
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-address-card"></i>
                                <h4>Contact Information</h4>
                            </div>
                            <div class="info-card-body">
                                <div class="mb-4">
                                    <div class="info-label">Email Address</div>
                                    <div class="info-value">{{ $company->email }}</div>
                                </div>
                                <div class="mb-0">
                                    <div class="info-label">Office Address</div>
                                    <div class="info-value">{{ $company->address }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Settings -->
                    <div class="col-lg-4">
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-clock"></i>
                                <h4>Working Hours</h4>
                            </div>
                            <div class="info-card-body">
                                <div class="mb-4">
                                    <div class="info-label">Check-in Time</div>
                                    <div class="info-value">{{ \Carbon\Carbon::parse($company->time_in)->format('h:i A') }}</div>
                                </div>
                                <div class="mb-0">
                                    <div class="info-label">Check-out Time</div>
                                    <div class="info-value">{{ \Carbon\Carbon::parse($company->time_out)->format('h:i A') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Method -->
                    <div class="col-lg-4">
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-fingerprint"></i>
                                <h4>Attendance Method</h4>
                            </div>
                            <div class="info-card-body">
                                <div class="mb-4">
                                    <div class="info-label">Method Type</div>
                                    <div class="info-value">
                                        @if($company->attendance_type == 'Face')
                                            <i class="fas fa-camera text-success mr-2"></i> Facial Recognition
                                        @elseif($company->attendance_type == 'QR')
                                            <i class="fas fa-qrcode text-primary mr-2"></i> QR Code Scanning
                                        @else
                                            <i class="fas fa-ban text-danger mr-2"></i> None
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-0">
                                    <div class="info-label">Radius Limitation</div>
                                    <div class="info-value">{{ $company->radius_km }} km</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-map-marker-alt mr-2"></i> Office Location</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-label">Latitude</div>
                                <div class="info-value">{{ $company->latitude }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Longitude</div>
                                <div class="info-value">{{ $company->longitude }}</div>
                            </div>
                        </div>

                        <div class="map-container">
                            <div id="map" style="height: 100%; width: 100%;"></div>
                        </div>

                        <div class="alert alert-light" role="alert">
                            <div class="d-flex">
                                <div class="mr-3">
                                    <i class="fas fa-info-circle text-primary fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="alert-heading">Attendance Area</h6>
                                    <p class="mb-0">Employees can only check-in within a {{ $company->radius_km }} km radius from this office location.</p>
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
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>

    <script>
        function initMap() {
            const companyLocation = {
                lat: parseFloat('{{ $company->latitude }}'),
                lng: parseFloat('{{ $company->longitude }}')
            };

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: companyLocation,
            });

            // Company marker
            const marker = new google.maps.Marker({
                position: companyLocation,
                map: map,
                title: '{{ $company->name }}',
                animation: google.maps.Animation.DROP
            });

            // Radius circle
            const radiusCircle = new google.maps.Circle({
                strokeColor: "#6777ef",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#6777ef",
                fillOpacity: 0.15,
                map,
                center: companyLocation,
                radius: {{ $company->radius_km }} * 1000, // Convert km to meters
            });
        }
    </script>
@endpush
