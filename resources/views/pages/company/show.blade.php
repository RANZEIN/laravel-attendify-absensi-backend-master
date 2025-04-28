<!-- show.blade.php -->
@extends('layouts.app')

@section('title', 'Company Profile')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary: #6777ef;
            --primary-light: rgba(103, 119, 239, 0.1);
            --primary-dark: #4e5ec7;
            --secondary: #1284e1;
            --success: #47c363;
            --info: #3abaf4;
            --warning: #ffa426;
            --danger: #fc544b;
            --light: #e3eaef;
            --dark: #191d21;
            --border-color: #f2f2f2;
            --card-shadow: 0 4px 25px 0 rgba(0, 0, 0, 0.1);
        }

        .form-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            margin-bottom: 30px;
            padding: 25px;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.03);
            overflow: hidden;
        }

        .form-section:hover {
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .section-title-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 18px;
            border-bottom: 1px solid #f9f9f9;
        }

        .section-title-header i {
            font-size: 20px;
            margin-right: 15px;
            color: var(--primary);
            background: var(--primary-light);
            padding: 14px;
            border-radius: 10px;
            height: 48px;
            width: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(103, 119, 239, 0.15);
        }

        .section-title-header h4 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
            color: #34395e;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 18px;
            height: auto;
            border-color: #e4e6fc;
            box-shadow: none;
            transition: all 0.3s;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.15);
        }

        .form-help {
            font-size: 12px;
            color: #6c757d;
            margin-top: 6px;
        }

        .map-container {
            height: 350px;
            background: #f4f6f9;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
        }

        .location-tip {
            background: rgba(58, 186, 244, 0.1);
            border-left: 4px solid #3abaf4;
            padding: 18px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .location-tip i {
            font-size: 24px;
            color: #3abaf4;
        }

        .invalid-feedback {
            font-size: 12px;
            display: block;
            margin-top: 5px;
            font-weight: 500;
        }

        .section-header {
            margin-bottom: 25px;
        }

        .section-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #34395e;
            margin-bottom: 5px;
        }

        .main-content {
            padding: 20px;
        }

        .btn-edit {
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 12px rgba(103, 119, 239, 0.3);
            transition: all 0.3s ease;
            background-color: var(--primary);
            border: none;
        }

        .btn-edit:hover {
            background-color: var(--primary-dark);
            box-shadow: 0 6px 15px rgba(103, 119, 239, 0.4);
            transform: translateY(-2px);
        }

        /* Specific styles for the show page */
        .info-label {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .info-value {
            font-size: 16px;
            color: #34395e;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .info-item i {
            font-size: 16px;
            color: var(--primary);
            margin-right: 15px;
            width: 16px;
            text-align: center;
        }

        .info-item span {
            color: #34395e;
            font-weight: 500;
        }

        .attendance-method-badge {
            display: inline-flex;
            align-items: center;
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .attendance-method-badge.face {
            background-color: rgba(71, 195, 99, 0.1);
            color: var(--success);
        }

        .attendance-method-badge.qr {
            background-color: rgba(58, 186, 244, 0.1);
            color: var(--info);
        }

        .attendance-method-badge.none {
            background-color: rgba(252, 84, 75, 0.1);
            color: var(--danger);
        }

        .attendance-method-badge i {
            margin-right: 8px;
            font-size: 16px;
        }

        .working-hours {
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .working-hours-divider {
            height: 40px;
            width: 1px;
            background-color: #e9ecef;
            margin: 0 15px;
        }

        .working-hour {
            flex: 1;
            text-align: center;
        }

        .working-hour-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .working-hour-value {
            font-size: 16px;
            font-weight: 600;
            color: #34395e;
        }

        .location-info {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .location-info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .location-info-item:last-child {
            margin-bottom: 0;
        }

        .location-info-label {
            font-size: 14px;
            color: #6c757d;
            width: 100px;
            font-weight: 500;
        }

        .location-info-value {
            font-size: 14px;
            font-weight: 600;
            color: #34395e;
        }

        .coordinate-value {
            font-family: monospace;
            background: #f1f3f9;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
        }

        .company-header {
            background: linear-gradient(135deg, #6777ef 0%, #3abaf4 100%);
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            color: white;
            box-shadow: 0 6px 18px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }

        .company-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg);
            pointer-events: none;
        }

        .company-logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1;
        }

        .company-logo img {
            max-width: 60%;
            max-height: 60%;
        }

        .badge-attendance-type {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-top: 5px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .form-section {
                padding: 20px;
                margin-bottom: 20px;
            }

            .company-header {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }

            .company-logo {
                margin-bottom: 15px;
            }

            .btn-edit {
                margin-top: 15px;
                padding: 10px 20px;
                font-size: 14px;
            }

            .section-title-header i {
                font-size: 18px;
                padding: 10px;
                height: 40px;
                width: 40px;
            }

            .section-title-header h4 {
                font-size: 16px;
            }

            .map-container {
                height: 280px;
            }
        }

        @media (max-width: 576px) {
            .section-header h1 {
                font-size: 20px;
            }

            .form-section {
                padding: 15px;
            }

            .section-title-header {
                padding-bottom: 12px;
                margin-bottom: 15px;
            }

            .working-hours {
                flex-direction: column;
            }

            .working-hours-divider {
                height: 1px;
                width: 100%;
                margin: 15px 0;
            }

            .location-info-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .location-info-label {
                width: 100%;
                margin-bottom: 5px;
            }
        }

        /* Animation effect for sections */
        .fade-in-up {
            animation: fadeInUp 0.6s both;
        }

        .fade-in-up:nth-child(2) {
            animation-delay: 0.2s;
        }

        .fade-in-up:nth-child(3) {
            animation-delay: 0.4s;
        }

        .fade-in-up:nth-child(4) {
            animation-delay: 0.6s;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Company Profile</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                        <div class="breadcrumb-item">Company Profile</div>
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <!-- Company Header -->
                <div class="company-header">
                    <div class="company-logo">
                        <img src="{{ asset('img/attendify-home.png') }}" alt="{{ $company->name }} Logo">
                    </div>
                    <div class="flex-grow-1">
                        <h2 class="mb-1">{{ $company->name }}</h2>
                        <p class="mb-1 text-white-50">{{ $company->email }}</p>
                        <span class="badge-attendance-type">
                            @if($company->attendance_type == 'Face')
                                <i class="fas fa-camera-retro mr-1"></i>
                            @elseif($company->attendance_type == 'QR')
                                <i class="fas fa-qrcode mr-1"></i>
                            @else
                                <i class="fas fa-ban mr-1"></i>
                            @endif
                            {{ $company->attendance_type }} Attendance
                        </span>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-light btn-edit">
                            <i class="fas fa-pencil-alt mr-1"></i> Edit Profile
                        </a>
                    </div>
                </div>

                <div class="row">
                    <!-- Company Information -->
                    <div class="col-lg-4 col-md-6">
                        <div class="form-section fade-in-up">
                            <div class="section-title-header">
                                <i class="fas fa-building"></i>
                                <h4>Company Information</h4>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="info-label">Company Name</div>
                                    <div class="info-value">{{ $company->name }}</div>

                                    <div class="info-label">Company Email</div>
                                    <div class="info-value">{{ $company->email }}</div>

                                    <div class="info-label">Company Address</div>
                                    <div class="info-value">{{ $company->address }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Settings -->
                    <div class="col-lg-4 col-md-6">
                        <div class="form-section fade-in-up">
                            <div class="section-title-header">
                                <i class="fas fa-user-clock"></i>
                                <h4>Attendance Settings</h4>
                            </div>

                            <div class="working-hours">
                                <div class="working-hour">
                                    <div class="working-hour-label">Check-in Time</div>
                                    <div class="working-hour-value">{{ \Carbon\Carbon::parse($company->time_in)->format('h:i A') }}</div>
                                </div>
                                <div class="working-hours-divider"></div>
                                <div class="working-hour">
                                    <div class="working-hour-label">Check-out Time</div>
                                    <div class="working-hour-value">{{ \Carbon\Carbon::parse($company->time_out)->format('h:i A') }}</div>
                                </div>
                            </div>

                            <div class="info-label">Total Working Hours</div>
                            <div class="info-value">
                                @php
                                    $timeIn = \Carbon\Carbon::parse($company->time_in);
                                    $timeOut = \Carbon\Carbon::parse($company->time_out);
                                    $totalHours = $timeOut->diffInHours($timeIn);
                                    $totalMinutes = $timeOut->diffInMinutes($timeIn) % 60;
                                @endphp
                                {{ $totalHours }} hours {{ $totalMinutes }} minutes
                            </div>

                            <div class="info-label">Attendance Method</div>
                            <div>
                                @if($company->attendance_type == 'Face')
                                    <div class="attendance-method-badge face">
                                        <i class="fas fa-camera"></i> Facial Recognition
                                    </div>
                                @elseif($company->attendance_type == 'QR')
                                    <div class="attendance-method-badge qr">
                                        <i class="fas fa-qrcode"></i> QR Code Scanning
                                    </div>
                                @else
                                    <div class="attendance-method-badge none">
                                        <i class="fas fa-ban"></i> None (Disabled)
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Additional Company Info -->
                    <div class="col-lg-4 col-md-6">
                        <div class="form-section fade-in-up">
                            <div class="section-title-header">
                                <i class="fas fa-info-circle"></i>
                                <h4>Additional Information</h4>
                            </div>

                            @if($company->attendance_type == 'Face')
                                <p>Employees must verify their identity using facial recognition technology when checking in and out.</p>
                            @elseif($company->attendance_type == 'QR')
                                <p>Employees must scan a QR code displayed at the office location to check in and out.</p>
                            @else
                                <p>Attendance tracking is currently disabled for this company.</p>
                            @endif

                            <div class="info-label mt-3">Other Details</div>
                            <div class="info-item">
                                <i class="fas fa-building"></i>
                                <span>Main Office</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-users"></i>
                                <span>Active Employees</span>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Established {{ date('Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location Settings -->
                <div class="form-section fade-in-up">
                    <div class="section-title-header">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Location Settings</h4>
                    </div>

                    <div class="location-tip">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            <strong>Attendance Area</strong>
                            <p class="mb-0">These coordinates define the center point for your company location. Employees can only check-in within the specified radius from this point.</p>
                        </div>
                    </div>

                    <div class="map-container mb-4">
                        <div id="map" style="height: 100%; width: 100%;"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="location-info">
                                <div class="location-info-item">
                                    <div class="location-info-label">Latitude</div>
                                    <div class="location-info-value coordinate-value">{{ $company->latitude }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="location-info">
                                <div class="location-info-item">
                                    <div class="location-info-label">Longitude</div>
                                    <div class="location-info-value coordinate-value">{{ $company->longitude }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="location-info">
                                <div class="location-info-item">
                                    <div class="location-info-label">Check-in Radius</div>
                                    <div class="location-info-value">{{ $company->radius_km }} kilometers</div>
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
        let map, marker, radiusCircle;

        function initMap() {
            const companyLocation = {
                lat: parseFloat('{{ $company->latitude }}') || -6.2088,
                lng: parseFloat('{{ $company->longitude }}') || 106.8456
            };

            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: companyLocation,
                styles: [
                    {
                        "featureType": "administrative",
                        "elementType": "labels.text.fill",
                        "stylers": [{"color": "#444444"}]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "all",
                        "stylers": [{"color": "#f2f2f2"}]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "all",
                        "stylers": [{"visibility": "off"}]
                    },
                    {
                        "featureType": "road",
                        "elementType": "all",
                        "stylers": [{"saturation": -100}, {"lightness": 45}]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "all",
                        "stylers": [{"visibility": "simplified"}]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "labels.icon",
                        "stylers": [{"visibility": "off"}]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "all",
                        "stylers": [{"visibility": "off"}]
                    },
                    {
                        "featureType": "water",
                        "elementType": "all",
                        "stylers": [{"color": "#6777ef"}, {"visibility": "on"}]
                    }
                ]
            });

            // Company marker
            marker = new google.maps.Marker({
                position: companyLocation,
                map: map,
                title: '{{ $company->name }}',
                animation: google.maps.Animation.DROP,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 10,
                    fillColor: "#6777ef",
                    fillOpacity: 1,
                    strokeColor: "#ffffff",
                    strokeWeight: 2
                }
            });

            // Radius circle
            const radiusKm = parseFloat('{{ $company->radius_km }}') || 1;

            // Circle with animation
            let opacity = 0.2;
            let increasing = true;

            radiusCircle = new google.maps.Circle({
                strokeColor: "#6777ef",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#6777ef",
                fillOpacity: opacity,
                map,
                center: companyLocation,
                radius: radiusKm * 1000, // Convert km to meters
            });

            // Animate the circle opacity
            setInterval(() => {
                if (increasing) {
                    opacity += 0.01;
                    if (opacity >= 0.3) increasing = false;
                } else {
                    opacity -= 0.01;
                    if (opacity <= 0.1) increasing = true;
                }
                radiusCircle.setOptions({fillOpacity: opacity});
            }, 50);
        }

        // Enhance user experience with animation
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltip for better UX if available
            if (typeof $().tooltip === 'function') {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    </script>
@endpush
