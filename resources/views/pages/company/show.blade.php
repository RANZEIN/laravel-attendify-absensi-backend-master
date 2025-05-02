@extends('layouts.app')

@section('title', 'Company Profile')

@push('style')
<style>
    :root {
        --primary: #6366f1;
        --primary-hover: #4f46e5;
        --secondary: #2f3c7e;
        --success: #10b981;
        --info: #3b82f6;
        --warning: #f59e0b;
        --danger: #ef4444;
        --light: #f8fafc;
        --dark: #0f172a;
        --grey-light: #f1f5f9;
        --grey: #e2e8f0;
        --text-main: #1e293b;
        --text-secondary: #64748b;
        --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        --transition-speed: 0.3s;
    }

    /* Company Profile Layout */
    .profile-container {
        padding: 1.5rem;
    }

    /* Company Header */
    .company-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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

    .company-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .company-logo {
        width: 100px;
        height: 100px;
        background: white;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .company-logo img {
        max-width: 70%;
        max-height: 70%;
    }

    .company-info {
        flex: 1;
    }

    .company-name {
        font-size: 1.75rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
    }

    .company-email {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 1rem;
    }

    .company-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 2rem;
        color: white;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .company-badge i {
        margin-right: 0.5rem;
    }

    .company-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-edit {
        background: white;
        color: var(--primary);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 2rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all var(--transition-speed) ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        background: var(--light);
    }

    /* Info Cards */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-card {
        background: white;
        border-radius: 1rem;
        box-shadow: var(--card-shadow);
        transition: all var(--transition-speed) ease;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .info-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .info-header {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid var(--grey-light);
    }

    .info-icon {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        background: var(--primary-hover);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 1rem;
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
    }

    .info-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .info-body {
        padding: 1.5rem;
    }

    .info-item {
        margin-bottom: 1.25rem;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-main);
    }

    /* Working Hours */
    .working-hours {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .working-hour {
        flex: 1;
        background: var(--light);
        border-radius: 0.75rem;
        padding: 1rem;
        text-align: center;
    }

    .working-hour-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .working-hour-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-main);
    }

    /* Attendance Method */
    .attendance-method {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--light);
        border-radius: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .attendance-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .attendance-icon.face {
        background: rgba(16, 185, 129, 0.1);
        color: var(--success);
    }

    .attendance-icon.qr {
        background: rgba(59, 130, 246, 0.1);
        color: var(--info);
    }

    .attendance-icon.none {
        background: rgba(239, 68, 68, 0.1);
        color: var(--danger);
    }

    .attendance-details {
        flex: 1;
    }

    .attendance-title {
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 0.25rem;
    }

    .attendance-description {
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    /* Location Card */
    .location-card {
        background: white;
        border-radius: 1rem;
        box-shadow: var(--card-shadow);
        transition: all var(--transition-speed) ease;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.03);
        margin-bottom: 1.5rem;
    }

    .location-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .location-header {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid var(--grey-light);
    }

    .location-icon {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        background: var(--info);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 1rem;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);
    }

    .location-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .map-container {
        height: 350px;
        width: 100%;
    }

    .location-details {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
        padding: 1.5rem;
    }

    .location-detail {
        background: var(--light);
        border-radius: 0.75rem;
        padding: 1rem;
    }

    .location-detail-label {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
    }

    .location-detail-value {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .coordinate-value {
        font-family: monospace;
        background: rgba(255, 255, 255, 0.5);
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .company-header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }

        .company-actions {
            margin-top: 1rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .profile-container {
            padding: 1rem;
        }

        .company-header {
            padding: 1.5rem;
        }

        .company-name {
            font-size: 1.5rem;
        }

        .working-hours {
            flex-direction: column;
            gap: 1rem;
        }

        .location-details {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .company-logo {
            width: 80px;
            height: 80px;
        }

        .company-actions {
            flex-direction: column;
            width: 100%;
        }

        .btn-edit {
            width: 100%;
            justify-content: center;
        }
    }

    /* Animation Classes */
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up:nth-child(1) { animation-delay: 0.1s; }
    .fade-in-up:nth-child(2) { animation-delay: 0.2s; }
    .fade-in-up:nth-child(3) { animation-delay: 0.3s; }
    .fade-in-up:nth-child(4) { animation-delay: 0.4s; }
</style>
@endpush

@section('main')
<div class="main-content">
    <div class="profile-container">
        <!-- Company Header -->
        <div class="company-header fade-in-up">
            <div class="company-header-content">
                <div class="company-logo">
                    <img src="{{ asset('img/attendify-home.png') }}" alt="{{ $company->name }} Logo">
                </div>
                <div class="company-info">
                    <h1 class="company-name">{{ $company->name }}</h1>
                    <div class="company-email">{{ $company->email }}</div>
                    <div class="company-badge">
                        @if($company->attendance_type == 'Face')
                            <i class="fas fa-camera-retro"></i> Facial Recognition
                        @elseif($company->attendance_type == 'QR')
                            <i class="fas fa-qrcode"></i> QR Code Scanning
                        @else
                            <i class="fas fa-ban"></i> No Attendance System
                        @endif
                    </div>
                </div>
                <div class="company-actions">
                    <a href="{{ route('companies.edit', $company->id) }}" class="btn-edit">
                        <i class="fas fa-pencil-alt"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Info Cards -->
        <div class="info-grid">
            <!-- Company Information -->
            <div class="info-card fade-in-up">
                <div class="info-header">
                    <div class="info-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="info-title">Company Information</div>
                </div>
                <div class="info-body">
                    <div class="info-item">
                        <div class="info-label">Company Name</div>
                        <div class="info-value">{{ $company->name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email Address</div>
                        <div class="info-value">{{ $company->email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Address</div>
                        <div class="info-value">{{ $company->address }}</div>
                    </div>
                </div>
            </div>

            <!-- Attendance Settings -->
            <div class="info-card fade-in-up">
                <div class="info-header">
                    <div class="info-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="info-title">Attendance Settings</div>
                </div>
                <div class="info-body">
                    <div class="working-hours">
                        <div class="working-hour">
                            <div class="working-hour-label">Check-in Time</div>
                            <div class="working-hour-value">{{ \Carbon\Carbon::parse($company->time_in)->format('h:i A') }}</div>
                        </div>
                        <div class="working-hour">
                            <div class="working-hour-label">Check-out Time</div>
                            <div class="working-hour-value">{{ \Carbon\Carbon::parse($company->time_out)->format('h:i A') }}</div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Working Hours</div>
                        <div class="info-value">
                            @php
                                $timeIn = \Carbon\Carbon::parse($company->time_in);
                                $timeOut = \Carbon\Carbon::parse($company->time_out);
                                $totalHours = $timeOut->diffInHours($timeIn);
                                $totalMinutes = $timeOut->diffInMinutes($timeIn) % 60;
                            @endphp
                            {{ $totalHours }} hours {{ $totalMinutes }} minutes
                        </div>
                    </div>

                    <div class="attendance-method
                        @if($company->attendance_type == 'Face') face
                        @elseif($company->attendance_type == 'QR') qr
                        @else none @endif">
                        <div class="attendance-icon
                            @if($company->attendance_type == 'Face') face
                            @elseif($company->attendance_type == 'QR') qr
                            @else none @endif">
                            @if($company->attendance_type == 'Face')
                                <i class="fas fa-camera"></i>
                            @elseif($company->attendance_type == 'QR')
                                <i class="fas fa-qrcode"></i>
                            @else
                                <i class="fas fa-ban"></i>
                            @endif
                        </div>
                        <div class="attendance-details">
                            <div class="attendance-title">
                                @if($company->attendance_type == 'Face')
                                    Facial Recognition
                                @elseif($company->attendance_type == 'QR')
                                    QR Code Scanning
                                @else
                                    No Attendance System
                                @endif
                            </div>
                            <div class="attendance-description">
                                @if($company->attendance_type == 'Face')
                                    Employees verify identity using facial recognition
                                @elseif($company->attendance_type == 'QR')
                                    Employees scan QR code to check in/out
                                @else
                                    Attendance tracking is currently disabled
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Card -->
        <div class="location-card fade-in-up">
            <div class="location-header">
                <div class="location-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="location-title">Location Settings</div>
            </div>
            <div class="map-container" id="map"></div>
            <div class="location-details">
                <div class="location-detail">
                    <div class="location-detail-label">Latitude</div>
                    <div class="location-detail-value">
                        <span class="coordinate-value">{{ $company->latitude }}</span>
                    </div>
                </div>
                <div class="location-detail">
                    <div class="location-detail-label">Longitude</div>
                    <div class="location-detail-value">
                        <span class="coordinate-value">{{ $company->longitude }}</span>
                    </div>
                </div>
                <div class="location-detail">
                    <div class="location-detail-label">Check-in Radius</div>
                    <div class="location-detail-value">{{ $company->radius_km }} kilometers</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
<script>
    let map, marker, radiusCircle;

    function initMap() {
        const companyLocation = {
            lat: parseFloat('{{ $company->latitude ?? -6.2088 }}'),
            lng: parseFloat('{{ $company->longitude ?? 106.8456 }}')
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
                    "stylers": [{"color": "#6366f1"}, {"visibility": "on"}]
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
                fillColor: "#6366f1",
                fillOpacity: 1,
                strokeColor: "#ffffff",
                strokeWeight: 2
            }
        });

        // Radius circle
        const radiusKm = parseFloat('{{ $company->radius_km ?? 1 }}');

        // Circle with animation
        let opacity = 0.2;
        let increasing = true;

        radiusCircle = new google.maps.Circle({
            strokeColor: "#6366f1",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#6366f1",
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
</script>
@endpush
