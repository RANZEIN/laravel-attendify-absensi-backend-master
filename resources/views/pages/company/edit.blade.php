@extends('layouts.app')

@section('title', 'Edit Company Profile')

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

    /* Form Layout */
    .form-container {
        padding: 1.5rem;
    }

    /* Form Sections */
    .form-section {
        background: white;
        border-radius: 1rem;
        box-shadow: var(--card-shadow);
        margin-bottom: 1.5rem;
        transition: all var(--transition-speed) ease;
        overflow: hidden;
        border: 1px solid rgba(0, 0, 0, 0.03);
    }

    .form-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .form-header {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid var(--grey-light);
    }

    .form-icon {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        background: var(--primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 1rem;
        box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.2);
    }

    .form-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .form-body {
        padding: 1.5rem;
    }

    /* Form Controls */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        font-weight: 500;
        color: var(--text-main);
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        line-height: 1.5;
        color: var(--text-main);
        background-color: white;
        background-clip: padding-box;
        border: 1px solid var(--grey);
        border-radius: 0.5rem;
        transition: all var(--transition-speed) ease;
    }

    .form-control:focus {
        border-color: var(--primary);
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }

    .form-control.is-invalid {
        border-color: var(--danger);
    }

    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.75rem;
        color: var(--danger);
    }

    .form-text {
        display: block;
        margin-top: 0.25rem;
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    /* Input Groups */
    .input-group {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        width: 100%;
    }

    .input-group > .form-control {
        position: relative;
        flex: 1 1 auto;
        width: 1%;
        min-width: 0;
    }

    .input-group-prepend {
        display: flex;
    }

    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1.5;
        color: var(--text-secondary);
        text-align: center;
        white-space: nowrap;
        background-color: var(--light);
        border: 1px solid var(--grey);
        border-radius: 0.5rem 0 0 0.5rem;
        border-right: none;
    }

    .input-group > .form-control:not(:first-child) {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    /* Map Container */
    .map-container {
        height: 350px;
        width: 100%;
        border-radius: 0.5rem;
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: 1px solid var(--grey);
    }

    /* Location Tip */
    .location-tip {
        display: flex;
        align-items: flex-start;
        padding: 1rem;
        background-color: rgba(59, 130, 246, 0.1);
        border-left: 4px solid var(--info);
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .location-tip i {
        font-size: 1.25rem;
        color: var(--info);
        margin-right: 1rem;
        margin-top: 0.25rem;
    }

    .location-tip-content h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 0.25rem;
    }

    .location-tip-content p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 0;
    }

    /* Coordinate Input */
    .coordinate-input {
        font-family: monospace;
        font-weight: 500;
        background-color: var(--light);
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 1.5rem;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.5;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        user-select: none;
        border: 1px solid transparent;
        border-radius: 0.5rem;
        transition: all var(--transition-speed) ease;
    }

    .btn-primary {
        color: white;
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
    }

    .btn-light {
        color: var(--text-main);
        background-color: var(--light);
        border-color: var(--grey);
    }

    .btn-light:hover {
        background-color: var(--grey-light);
        border-color: var(--grey);
    }

    .btn-group {
        display: flex;
        gap: 0.75rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .form-container {
            padding: 1rem;
        }

        .form-header, .form-body {
            padding: 1rem;
        }

        .form-icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }

        .form-title {
            font-size: 1rem;
        }

        .map-container {
            height: 250px;
        }

        .btn {
            padding: 0.625rem 1.25rem;
        }
    }

    @media (max-width: 576px) {
        .btn-group {
            flex-direction: column;
            width: 100%;
        }

        .btn {
            width: 100%;
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
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                <h1 class="h3 mb-1">Edit Company Profile</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('companies.show', $company->id) }}">Company Profile</a></div>
                    <div class="breadcrumb-item">Edit</div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('companies.update', $company->id) }}">
            @csrf
            @method('PUT')

            <!-- Company Information -->
            <div class="form-section fade-in-up">
                <div class="form-header">
                    <div class="form-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="form-title">Company Information</div>
                </div>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Company Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    </div>
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $company->name) }}" placeholder="Enter company name">
                                </div>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label">Company Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $company->email) }}" placeholder="company@example.com">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text">This email will be used for system notifications</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">Company Address</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            </div>
                            <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="3" placeholder="Enter complete address">{{ old('address', $company->address) }}</textarea>
                        </div>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Location Settings -->
            <div class="form-section fade-in-up">
                <div class="form-header">
                    <div class="form-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="form-title">Location Settings</div>
                </div>
                <div class="form-body">
                    <div class="location-tip">
                        <i class="fas fa-info-circle"></i>
                        <div class="location-tip-content">
                            <h4>Attendance Area</h4>
                            <p>These coordinates define the center point for your company location. Employees can only check-in within the specified radius from this point.</p>
                        </div>
                    </div>

                    <div class="map-container mb-4">
                        <div id="map" style="height: 100%; width: 100%;"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="latitude" class="form-label">Latitude</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-crosshairs"></i></span>
                                    </div>
                                    <input type="text" id="latitude" name="latitude" class="form-control coordinate-input @error('latitude') is-invalid @enderror" value="{{ old('latitude', $company->latitude) }}">
                                </div>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="longitude" class="form-label">Longitude</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-crosshairs"></i></span>
                                    </div>
                                    <input type="text" id="longitude" name="longitude" class="form-control coordinate-input @error('longitude') is-invalid @enderror" value="{{ old('longitude', $company->longitude) }}">
                                </div>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="radius_km" class="form-label">Check-in Radius (km)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-circle-notch"></i></span>
                                    </div>
                                    <input type="number" step="0.01" id="radius_km" name="radius_km" class="form-control @error('radius_km') is-invalid @enderror" value="{{ old('radius_km', $company->radius_km) }}">
                                </div>
                                @error('radius_km')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text">Maximum distance employees can be from office to check-in</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Settings -->
            <div class="form-section fade-in-up">
                <div class="form-header">
                    <div class="form-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="form-title">Attendance Settings</div>
                </div>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="time_in" class="form-label">Office Check-in Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-sign-in-alt"></i></span>
                                    </div>
                                    <input type="time" id="time_in" name="time_in" class="form-control @error('time_in') is-invalid @enderror" value="{{ old('time_in', $company->time_in) }}">
                                </div>
                                @error('time_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="time_out" class="form-label">Office Check-out Time</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-sign-out-alt"></i></span>
                                    </div>
                                    <input type="time" id="time_out" name="time_out" class="form-control @error('time_out') is-invalid @enderror" value="{{ old('time_out', $company->time_out) }}">
                                </div>
                                @error('time_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="attendance_type" class="form-label">Attendance Method</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
                                    </div>
                                    <select id="attendance_type" name="attendance_type" class="form-control @error('attendance_type') is-invalid @enderror">
                                        <option value="Face" {{ old('attendance_type', $company->attendance_type) == 'Face' ? 'selected' : '' }}>
                                            Facial Recognition
                                        </option>
                                        <option value="QR" {{ old('attendance_type', $company->attendance_type) == 'QR' ? 'selected' : '' }}>
                                            QR Code Scanning
                                        </option>
                                        <option value="None" {{ old('attendance_type', $company->attendance_type) == 'None' ? 'selected' : '' }}>
                                            None (Disabled)
                                        </option>
                                    </select>
                                </div>
                                @error('attendance_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <div class="btn-group">
                    <a href="{{ route('companies.show', $company->id) }}" class="btn btn-light">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
<script>
    let map, marker, radiusCircle;

    function initMap() {
        const companyLocation = {
            lat: parseFloat(document.getElementById('latitude').value) || -6.2088,
            lng: parseFloat(document.getElementById('longitude').value) || 106.8456
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
            title: document.getElementById('name').value || 'Company Location',
            draggable: true,
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
        const radiusKm = parseFloat(document.getElementById('radius_km').value) || 1;
        radiusCircle = new google.maps.Circle({
            strokeColor: "#6366f1",
            strokeOpacity: 0.8,
            strokeWeight: 2,
            fillColor: "#6366f1",
            fillOpacity: 0.15,
            map,
            center: companyLocation,
            radius: radiusKm * 1000, // Convert km to meters
            editable: false
        });

        // Update coordinates when marker is dragged
        google.maps.event.addListener(marker, 'dragend', function(event) {
            document.getElementById('latitude').value = event.latLng.lat().toFixed(6);
            document.getElementById('longitude').value = event.latLng.lng().toFixed(6);
            updateCircle();
        });

        // Update map when coordinates are changed
        document.getElementById('latitude').addEventListener('change', updateMarker);
        document.getElementById('longitude').addEventListener('change', updateMarker);
        document.getElementById('radius_km').addEventListener('change', updateCircle);

        // Add click event to map to move marker
        google.maps.event.addListener(map, 'click', function(event) {
            marker.setPosition(event.latLng);
            document.getElementById('latitude').value = event.latLng.lat().toFixed(6);
            document.getElementById('longitude').value = event.latLng.lng().toFixed(6);
            updateCircle();
        });
    }

    function updateMarker() {
        const lat = parseFloat(document.getElementById('latitude').value) || -6.2088;
        const lng = parseFloat(document.getElementById('longitude').value) || 106.8456;
        const location = new google.maps.LatLng(lat, lng);

        marker.setPosition(location);
        map.setCenter(location);
        updateCircle();
    }

    function updateCircle() {
        const lat = parseFloat(document.getElementById('latitude').value) || -6.2088;
        const lng = parseFloat(document.getElementById('longitude').value) || 106.8456;
        const radiusKm = parseFloat(document.getElementById('radius_km').value) || 1;
        const location = new google.maps.LatLng(lat, lng);

        radiusCircle.setCenter(location);
        radiusCircle.setRadius(radiusKm * 1000); // Convert km to meters
    }
</script>
@endpush
