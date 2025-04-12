<!-- edit.blade.php -->
@extends('layouts.app')

@section('title', 'Edit Company Profile')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
    <style>
        .form-section {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            padding: 20px;
        }

        .section-title-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f9f9f9;
        }

        .section-title-header i {
            font-size: 24px;
            margin-right: 15px;
            color: #6777ef;
            background: rgba(103, 119, 239, 0.1);
            padding: 10px;
            border-radius: 10px;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            height: auto;
            border-color: #e4e6fc;
            box-shadow: none;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #6777ef;
            box-shadow: 0 0 0 0.2rem rgba(103, 119, 239, 0.15);
        }

        .form-group label {
            font-weight: 600;
            color: #34395e;
            font-size: 14px;
            margin-bottom: 8px;
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

        .btn-save {
            padding: 10px 30px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 2px 6px rgba(103, 119, 239, 0.3);
        }

        .btn-cancel {
            padding: 10px 30px;
            font-weight: 600;
            border-radius: 50px;
        }

        .form-help {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        .map-container {
            height: 300px;
            background: #f4f6f9;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .location-tip {
            background: rgba(58, 186, 244, 0.1);
            border-left: 4px solid #3abaf4;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Company Profile</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('companies.show', $company->id) }}">Company Profile</a></div>
                    <div class="breadcrumb-item">Edit</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">Update Company Information</h2>
                <p class="section-lead">
                    Modify your company information and attendance settings below.
                </p>

                <form method="POST" action="{{ route('companies.update', $company->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Basic Information -->
                    <div class="form-section">
                        <div class="section-title-header">
                            <i class="fas fa-building"></i>
                            <h4>Company Information</h4>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 col-12">
                                <label for="name">Company Name</label>
                                <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $company->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 col-12">
                                <label for="email">Company Email</label>
                                <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $company->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-help">This email will be used for system notifications</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-12">
                                <label for="address">Company Address</label>
                                <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" style="height: 80px;">{{ old('address', $company->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Location Settings -->
                    <div class="form-section">
                        <div class="section-title-header">
                            <i class="fas fa-map-marker-alt"></i>
                            <h4>Location Settings</h4>
                        </div>

                        <div class="location-tip">
                            <div class="d-flex">
                                <div class="mr-3">
                                    <i class="fas fa-info-circle text-info"></i>
                                </div>
                                <div>
                                    <strong>Attendance Area</strong>
                                    <p class="mb-0">These coordinates define the center point for your company location. Employees can only check-in within the specified radius from this point.</p>
                                </div>
                            </div>
                        </div>

                        <div class="map-container mb-4">
                            <div id="map" style="height: 100%; width: 100%;"></div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4 col-12">
                                <label for="latitude">Latitude</label>
                                <input type="text" id="latitude" name="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $company->latitude) }}">
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4 col-12">
                                <label for="longitude">Longitude</label>
                                <input type="text" id="longitude" name="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $company->longitude) }}">
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4 col-12">
                                <label for="radius_km">Check-in Radius (km)</label>
                                <input type="number" step="0.01" id="radius_km" name="radius_km" class="form-control @error('radius_km') is-invalid @enderror" value="{{ old('radius_km', $company->radius_km) }}">
                                @error('radius_km')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-help">Maximum distance employees can be from office to check-in</div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Settings -->
                    <div class="form-section">
                        <div class="section-title-header">
                            <i class="fas fa-user-clock"></i>
                            <h4>Attendance Settings</h4>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4 col-12">
                                <label for="time_in">Office Check-in Time</label>
                                <input type="time" id="time_in" name="time_in" class="form-control @error('time_in') is-invalid @enderror" value="{{ old('time_in', $company->time_in) }}">
                                @error('time_in')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4 col-12">
                                <label for="time_out">Office Check-out Time</label>
                                <input type="time" id="time_out" name="time_out" class="form-control @error('time_out') is-invalid @enderror" value="{{ old('time_out', $company->time_out) }}">
                                @error('time_out')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-4 col-12">
                                <label for="attendance_type">Attendance Method</label>
                                <div class="select-wrapper">
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

                    <!-- Form Buttons -->
                    <div class="text-right">
                        <a href="{{ route('companies.show', $company->id) }}" class="btn btn-light btn-cancel mr-2">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="fas fa-save mr-1"></i> Save Changes
                        </button>
                    </div>
                </form>
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
            });

            // Company marker
            marker = new google.maps.Marker({
                position: companyLocation,
                map: map,
                title: '{{ $company->name }}',
                draggable: true
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
