<!-- edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Company Profile')

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

        .form-group label {
            font-weight: 600;
            color: #34395e;
            font-size: 14px;
            margin-bottom: 10px;
            display: block;
        }

        .select-wrapper {
            position: relative;
        }

        .select-wrapper:after {
            content: "";
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 6px solid #6c757d;
            pointer-events: none;
        }

        select.form-control {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            padding-right: 30px;
        }

        .btn-save {
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 12px rgba(103, 119, 239, 0.3);
            transition: all 0.3s ease;
            background-color: var(--primary);
            border: none;
        }

        .btn-save:hover {
            background-color: var(--primary-dark);
            box-shadow: 0 6px 15px rgba(103, 119, 239, 0.4);
            transform: translateY(-2px);
        }

        .btn-cancel {
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            color: #6c757d;
        }

        .btn-cancel:hover {
            background-color: #e9ecef;
            color: #212529;
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

        .input-group-time {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        }

        .input-group-time .input-group-text {
            background-color: var(--primary-light);
            border-color: #e4e6fc;
            color: var(--primary);
            padding: 0 15px;
        }

        .coordinate-input {
            background-color: #f8f9fa;
            font-family: monospace;
            font-weight: 500;
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

        /* Responsive Styles */
        @media (max-width: 768px) {
            .form-section {
                padding: 20px;
                margin-bottom: 20px;
            }

            .btn-save, .btn-cancel {
                padding: 10px 20px;
                font-size: 14px;
                width: 100%;
                margin-bottom: 10px;
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
        }

        /* Animation effect for form sections */
        .fade-in-up {
            animation: fadeInUp 0.6s both;
        }

        .fade-in-up:nth-child(2) {
            animation-delay: 0.2s;
        }

        .fade-in-up:nth-child(3) {
            animation-delay: 0.4s;
        }

        /* Custom time input styling */
        input[type="time"] {
            position: relative;
        }

        input[type="time"]::-webkit-calendar-picker-indicator {
            background: none;
            cursor: pointer;
            padding: 0;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary);
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Edit Company Profile</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="{{ route('companies.show', $company->id) }}">Company Profile</a></div>
                        <div class="breadcrumb-item">Edit</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('companies.update', $company->id) }}" class="mt-4">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="form-section fade-in-up">
                            <div class="section-title-header">
                                <i class="fas fa-building"></i>
                                <h4>Company Information</h4>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 col-12">
                                    <label for="name">Company Name</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $company->name) }}" placeholder="Enter company name" autocomplete="off">
                                      </div>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6 col-12">
                                    <label for="email">Company Email</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $company->email) }}" placeholder="company@example.com" autocomplete="off">
                                        </div>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="form-help">This email will be used for system notifications</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="address">Company Address</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="Enter complete address">{{ old('address', $company->address) }}</textarea>
                                        </div>
                                    </div>
                                    @error('address')
                                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                                    @enderror
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
                                <div class="form-group col-md-4 col-12">
                                    <label for="latitude">Latitude</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-crosshairs"></i></span>
                                        <input type="text" id="latitude" name="latitude" class="form-control coordinate-input @error('latitude') is-invalid @enderror" value="{{ old('latitude', $company->latitude) }}">
                                        </div>
                                    </div>
                                    @error('latitude')
                                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4 col-12">
                                    <label for="longitude">Longitude</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-crosshairs"></i></span>
                                        <input type="text" id="longitude" name="longitude" class="form-control coordinate-input @error('longitude') is-invalid @enderror" value="{{ old('longitude', $company->longitude) }}">
                                        </div>
                                    </div>
                                    @error('longitude')
                                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4 col-12">
                                    <label for="radius_km">Check-in Radius (km)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-circle-notch"></i></span>
                                        <input type="number" step="0.01" id="radius_km" name="radius_km" class="form-control @error('radius_km') is-invalid @enderror" value="{{ old('radius_km', $company->radius_km) }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">km</span>
                                        </div>
                                    </div>
                                    @error('radius_km')
                                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                                    @enderror
                                    <div class="form-help">Maximum distance employees can be from office to check-in</div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance Settings -->
                        <div class="form-section fade-in-up">
                            <div class="section-title-header">
                                <i class="fas fa-user-clock"></i>
                                <h4>Attendance Settings</h4>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4 col-12">
                                    <label for="time_in">Office Check-in Time</label>
                                    <div class="input-group input-group-time">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-sign-in-alt"></i></span>
                                        <input type="time" id="time_in" name="time_in" class="form-control @error('time_in') is-invalid @enderror" value="{{ old('time_in', $company->time_in) }}">
                                        </div>
                                    </div>
                                    @error('time_in')
                                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4 col-12">
                                    <label for="time_out">Office Check-out Time</label>
                                    <div class="input-group input-group-time">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-sign-out-alt"></i></span>
                                        <input type="time" id="time_out" name="time_out" class="form-control @error('time_out') is-invalid @enderror" value="{{ old('time_out', $company->time_out) }}">
                                        </div>
                                    </div>
                                    @error('time_out')
                                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4 col-12">
                                    <label for="attendance_type">Attendance Method</label>
                                    <div class="select-wrapper">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-fingerprint"></i></span>
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
                                    </div>
                                    @error('attendance_type')
                                        <div class="invalid-feedback text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Buttons -->
                        <div class="form-actions text-right">
                            <div class="row">
                                <div class="col-12 col-sm-6 col-md-4 ml-auto">
                                    <div class="d-flex flex-column flex-sm-row gap-2">
                                        <a href="{{ route('companies.show', $company->id) }}" class="btn btn-light btn-cancel mb-3 mb-sm-0 mr-0 mr-sm-2 w-100">
                                            <i class="fas fa-times mr-1"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-save w-100">
                                            <i class="fas fa-save mr-1"></i> Save Changes
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                        "stylers": [{"color": "#6777ef"}, {"visibility": "on"}]
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
                    fillColor: "#6777ef",
                    fillOpacity: 1,
                    strokeColor: "#ffffff",
                    strokeWeight: 2
                }
            });

            // Radius circle
            const radiusKm = parseFloat(document.getElementById('radius_km').value) || 1;
            radiusCircle = new google.maps.Circle({
                strokeColor: "#6777ef",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#6777ef",
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

        // Enhance user experience with animation for form sections
        document.addEventListener('DOMContentLoaded', function() {
            // Listen for changes in attendance type to show relevant fields
            document.getElementById('attendance_type').addEventListener('change', function() {
                const value = this.value;
                // You can add conditional logic here if needed
            });

            // Initialize tooltip for better UX
            if (typeof $().tooltip === 'function') {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    </script>
@endpush
