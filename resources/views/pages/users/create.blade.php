@extends('layouts.app')

@section('title', 'Create Time Off Request')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap-daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --success-color: #10b981;
            --info-color: #3b82f6;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --text-main: #1e293b;
            --text-secondary: #64748b;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body {
            color: var(--text-main);
            background-color: var(--light-bg);
        }

        .section-header {
            padding: 20px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .section-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid var(--border-color);
            padding: 15px 20px;
        }

        .card-header h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-main);
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border-radius: 8px;
            border-color: var(--border-color);
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            color: var(--danger-color);
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        .form-text {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        /* Select2 Customization */
        .select2-container--default .select2-selection--single {
            height: calc(2.25rem + 6px);
            border-radius: 8px;
            border-color: var(--border-color);
            padding: 0.375rem 0.75rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(2.25rem + 6px);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5;
            color: var(--text-main);
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary-color);
        }

        /* Date Range Container */
        .date-range-container {
            display: flex;
            gap: 15px;
        }

        .date-input {
            flex: 1;
        }

        /* Manual Days Input */
        .manual-days-input {
            margin-top: 15px;
            padding: 15px;
            background-color: #f8fafc;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        /* Alert Info */
        .alert-info {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--info-color);
            border-color: rgba(59, 130, 246, 0.2);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .alert-info i {
            font-size: 1.25rem;
            margin-top: 2px;
        }

        /* Button Styles */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-lg {
            padding: 1rem 1.5rem;
            font-size: 1rem;
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        /* Hidden Class */
        .hidden {
            display: none !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .section-header .btn {
                margin-top: 1rem;
                align-self: flex-start;
            }

            .date-range-container {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 576px) {
            .section-header h1 {
                font-size: 1.25rem;
            }

            .card-header h4 {
                font-size: 1rem;
            }

            .form-group label {
                font-size: 0.875rem;
            }
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Create Time Off Request</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="{{ route('time_offs.index') }}">Time Offs</a></div>
                        <div class="breadcrumb-item">Create Time Off Request</div>
                    </div>
                </div>
                {{-- <a href="{{ route('time_offs.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Time Off
                </a> --}}
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-8 offset-lg-2">
                        <div class="card">
                            <div class="card-header">
                                <h4>Time Off Request Form</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('time_offs.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="user_id">Employee</label>
                                        <select name="user_id" id="user_id" class="form-control select2 @error('user_id') is-invalid @enderror">
                                            <option value="">Select Employee</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->position }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="type">Time Off Type</label>
                                        <select name="type" id="type" class="form-control select2 @error('type') is-invalid @enderror">
                                            <option value="">Select Type</option>
                                            <option value="cuti_tahunan" {{ old('type') == 'cuti_tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                                            <option value="izin_jam_kerja" {{ old('type') == 'izin_jam_kerja' ? 'selected' : '' }}>Izin Jam Kerja</option>
                                            <option value="izin_sebelum_atau_sesudah_istirahat" {{ old('type') == 'izin_sebelum_atau_sesudah_istirahat' ? 'selected' : '' }}>Izin Sebelum/Sesudah Istirahat</option>
                                            <option value="cuti_umroh" {{ old('type') == 'cuti_umroh' ? 'selected' : '' }}>Cuti Umroh</option>
                                            <option value="cuti_haji" {{ old('type') == 'cuti_haji' ? 'selected' : '' }}>Cuti Haji</option>
                                            <option value="dinas_dalam_kota" {{ old('type') == 'dinas_dalam_kota' ? 'selected' : '' }}>Dinas Dalam Kota</option>
                                            <option value="dinas_luar_kota" {{ old('type') == 'dinas_luar_kota' ? 'selected' : '' }}>Dinas Luar Kota</option>
                                            <option value="izin_tidak_masuk" {{ old('type') == 'izin_tidak_masuk' ? 'selected' : '' }}>Izin Tidak Masuk</option>
                                            <option value="sakit_berkepanjangan_12_bulan_pertama" {{ old('type') == 'sakit_berkepanjangan_12_bulan_pertama' ? 'selected' : '' }}>Sakit Berkepanjangan (12 Bulan Pertama)</option>
                                            <option value="sakit_berkepanjangan_4_bulan_pertama" {{ old('type') == 'sakit_berkepanjangan_4_bulan_pertama' ? 'selected' : '' }}>Sakit Berkepanjangan (4 Bulan Pertama)</option>
                                            <option value="sakit_berkepanjangan_8_bulan_pertama" {{ old('type') == 'sakit_berkepanjangan_8_bulan_pertama' ? 'selected' : '' }}>Sakit Berkepanjangan (8 Bulan Pertama)</option>
                                            <option value="sakit_berkepanjangan_diatas_12_bulan_pertama" {{ old('type') == 'sakit_berkepanjangan_diatas_12_bulan_pertama' ? 'selected' : '' }}>Sakit Berkepanjangan (>12 Bulan)</option>
                                            <option value="sakit_dengan_surat_dokter" {{ old('type') == 'sakit_dengan_surat_dokter' ? 'selected' : '' }}>Sakit dengan Surat Dokter</option>
                                            <option value="sakit_tanpa_surat_dokter" {{ old('type') == 'sakit_tanpa_surat_dokter' ? 'selected' : '' }}>Sakit tanpa Surat Dokter</option>
                                            <option value="cuti_menikah" {{ old('type') == 'cuti_menikah' ? 'selected' : '' }}>Cuti Menikah</option>
                                            <option value="cuti_menikahkan_anak" {{ old('type') == 'cuti_menikahkan_anak' ? 'selected' : '' }}>Cuti Menikahkan Anak</option>
                                            <option value="cuti_khitanan_anak" {{ old('type') == 'cuti_khitanan_anak' ? 'selected' : '' }}>Cuti Khitanan Anak</option>
                                            <option value="cuti_istri_melahirkan_atau_keguguran" {{ old('type') == 'cuti_istri_melahirkan_atau_keguguran' ? 'selected' : '' }}>Cuti Istri Melahirkan/Keguguran</option>
                                            <option value="cuti_keluarga_meninggal" {{ old('type') == 'cuti_keluarga_meninggal' ? 'selected' : '' }}>Cuti Keluarga Meninggal</option>
                                            <option value="cuti_anggota_keluarga_dalam_satu_rumah_meninggal" {{ old('type') == 'cuti_anggota_keluarga_dalam_satu_rumah_meninggal' ? 'selected' : '' }}>Cuti Anggota Keluarga Serumah Meninggal</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="alert alert-info auto-calculate-info">
                                        <i class="fas fa-info-circle"></i>
                                        <div>
                                            <strong>Automatic Calculation</strong>
                                            <p class="mb-0">For leave types: <strong>Cuti Tahunan, Izin Tidak Masuk, Sakit dengan/tanpa Surat Dokter</strong>, the number of days will be calculated automatically based on the selected dates.</p>
                                        </div>
                                    </div>

                                    <div class="calendar-container">
                                        <div class="form-group">
                                            <label>Leave Dates</label>
                                            <div class="date-range-container">
                                                <div class="date-input">
                                                    <label for="start_date">Start Date</label>
                                                    <input type="text" name="start_date" id="start_date" class="form-control datepicker @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" placeholder="YYYY-MM-DD">
                                                    @error('start_date')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="date-input">
                                                    <label for="end_date">End Date</label>
                                                    <input type="text" name="end_date" id="end_date" class="form-control datepicker @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}" placeholder="YYYY-MM-DD">
                                                    @error('end_date')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group manual-days-input" id="manual-days-container">
                                        <label for="days">Number of Days</label>
                                        <input type="number" name="days" id="days" class="form-control @error('days') is-invalid @enderror" value="{{ old('days') }}" min="1">
                                        <small class="form-text text-muted">Enter the number of days manually for this leave type.</small>
                                        @error('days')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="reason">Reason</label>
                                        <textarea name="reason" id="reason" class="form-control @error('reason') is-invalid @enderror" style="height: 100px">{{ old('reason') }}</textarea>
                                        @error('reason')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="document">Supporting Document (Optional)</label>
                                        <div class="custom-file">
                                            <input type="file" name="document" id="document" class="custom-file-input @error('document') is-invalid @enderror">
                                            <label class="custom-file-label" for="document">Choose file</label>
                                            <small class="form-text text-muted">Allowed formats: jpeg, png, jpg, pdf. Max size: 2MB.</small>
                                            @error('document')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="  ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                                            <i class="fas fa-save mr-1"></i> Submit Request
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
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Select an option",
                width: '100%'
            });

            // Initialize Datepicker
            $('.datepicker').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Auto-calculate days for specific leave types
            const autoCalculateTypes = ['cuti_tahunan', 'izin_tidak_masuk', 'sakit_dengan_surat_dokter', 'sakit_tanpa_surat_dokter'];

            function calculateDays() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                if (startDate && endDate) {
                    const start = new Date(startDate);
                    const end = new Date(endDate);

                    // Calculate the difference in days
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 to include both start and end dates

                    $('#days').val(diffDays);
                }
            }

            function toggleManualDaysInput() {
                const selectedType = $('#type').val();

                if (autoCalculateTypes.includes(selectedType)) {
                    $('#manual-days-container').addClass('hidden');
                    $('.auto-calculate-info').removeClass('hidden');
                    calculateDays();
                } else {
                    $('#manual-days-container').removeClass('hidden');
                    $('.auto-calculate-info').addClass('hidden');
                }
            }

            // Calculate days when dates change
            $('#start_date, #end_date').on('change', function() {
                const selectedType = $('#type').val();
                if (autoCalculateTypes.includes(selectedType)) {
                    calculateDays();
                }
            });

            // Toggle manual days input when type changes
            $('#type').on('change', toggleManualDaysInput);

            // Initialize on page load
            toggleManualDaysInput();

            // Custom file input
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName || 'Choose file');
            });
        });
    </script>
@endpush

```blade file="resources/views/pages/time_offs/show.blade.php"
@extends('layouts.app')

@section('title', 'Time Off Request Detail')

@push('style')
    &lt;!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('library/bootstrap-social/assets/css/bootstrap.css') }}">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --success-color: #10b981;
            --info-color: #3b82f6;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --text-main: #1e293b;
            --text-secondary: #64748b;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body {
            color: var(--text-main);
            background-color: var(--light-bg);
        }

        .section-header {
            padding: 20px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .section-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid var(--border-color);
            padding: 15px 20px;
        }

        .card-header h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
            color: var(--text-main);
        }

        .card-body {
            padding: 20px;
        }

        .card-footer {
            background-color: #fff;
            border-top: 1px solid var(--border-color);
            padding: 15px 20px;
        }

        /* Form Group Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .form-group p {
            margin-bottom: 0;
            font-size: 1rem;
            color: var(--text-main);
        }

        /* Badge Styles */
        .badge {
            padding: 0.35em 0.65em;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 6px;
            display: inline-block;
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        .badge-info {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--info-color);
        }

        .badge-primary {
            background-color: rgba(99, 102, 241, 0.1);
            color: var(--primary-color);
        }

        .badge-secondary {
            background-color: rgba(100, 116, 139, 0.1);
            color: var(--text-secondary);
        }

        .badge-dark {
            background-color: rgba(30, 41, 59, 0.1);
            color: var(--text-main);
        }

        /* Button Styles */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.875rem;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-success:hover {
            background-color: #059669;
            border-color: #059669;
            transform: translateY(-1px);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-danger:hover {
            background-color: #dc2626;
            border-color: #dc2626;
            transform: translateY(-1px);
        }

        .btn-lg {
            padding: 1rem 1.5rem;
            font-size: 1rem;
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        /* Document Preview */
        .img-thumbnail {
            border-radius: 8px;
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        /* Time Off Header */
        .time-off-header {
            background: linear-gradient(135deg, #6366f1 0%, #818cf8 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .time-off-header::before {
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

        .time-off-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .time-off-header p {
            margin-bottom: 0;
            opacity: 0.9;
        }

        .time-off-status {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
            background: rgba(255, 255, 255, 0.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .section-header .btn {
                margin-top: 1rem;
                align-self: flex-start;
            }

            .time-off-header {
                padding: 15px;
            }

            .time-off-status {
                position: static;
                display: inline-block;
                margin-top: 10px;
            }

            .card-footer .row {
                flex-direction: column;
            }

            .card-footer .col-md-6 {
                margin-bottom: 10px;
            }
        }

        @media (max-width: 576px) {
            .section-header h1 {
                font-size: 1.25rem;
            }

            .card-header h4 {
                font-size: 1rem;
            }

            .time-off-header h2 {
                font-size: 1.25rem;
            }
        }
    </style>
@endpush

@section('main')
<div class="main-content">
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <div>
                <h1>Time Off Request Detail</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="{{ route('time_offs.index') }}">Time Off</a></div>
                    <div class="breadcrumb-item">Time Off Request Detail</div>
                </div>
            </div>
            {{-- <a href="{{ route('time_offs.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Time Off
            </a> --}}
        </div>

        <div class="row">
            <div class="col-12">
                <div class="time-off-header">
                    <h2>{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</h2>
                    <p>Requested by {{ $timeOff->user->name }} on {{ $timeOff->created_at->format('d M Y') }}</p>

                    @if($timeOff->status == 'pending')
                        <span class="time-off-status">Pending</span>
                    @elseif($timeOff->status == 'approved')
                        <span class="time-off-status">Approved</span>
                    @elseif($timeOff->status == 'rejected')
                        <span class="time-off-status">Rejected</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Employee Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar mr-3" style="width: 64px; height: 64px; background-color: #6366f1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 600;">
                                {{ substr($timeOff->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $timeOff->user->name }}</h5>
                                <p class="text-muted mb-0">{{ $timeOff->user->position ?? 'No position' }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <p>{{ $timeOff->user->email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <p>{{ $timeOff->user->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Department</label>
                                    <p>{{ $timeOff->user->department ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Role</label>
                                    <p>{{ ucfirst($timeOff->user->role ?? 'N/A') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Time Off Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Type</label>
                                    <p>
                                        @if($timeOff->type == 'cuti_tahunan')
                                            <span class="badge badge-success">Cuti Tahunan</span>
                                        @elseif($timeOff->type == 'izin_jam_kerja')
                                            <span class="badge badge-primary">Izin Jam Kerja</span>
                                        @elseif($timeOff->type == 'izin_sebelum_atau_sesudah_istirahat')
                                            <span class="badge badge-primary">Izin Sebelum/Sesudah Istirahat</span>
                                        @elseif($timeOff->type == 'cuti_umroh')
                                            <span class="badge badge-info">Cuti Umroh</span>
                                        @elseif($timeOff->type == 'cuti_haji')
                                            <span class="badge badge-info">Cuti Haji</span>
                                        @elseif($timeOff->type == 'dinas_dalam_kota')
                                            <span class="badge badge-secondary">Dinas Dalam Kota</span>
                                        @elseif($timeOff->type == 'dinas_luar_kota')
                                            <span class="badge badge-secondary">Dinas Luar Kota</span>
                                        @elseif($timeOff->type == 'izin_tidak_masuk')
                                            <span class="badge badge-warning">Izin Tidak Masuk</span>
                                        @elseif(strpos($timeOff->type, 'sakit') !== false)
                                            <span class="badge badge-danger">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                        @elseif(strpos($timeOff->type, 'cuti') !== false)
                                            <span class="badge badge-success">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                        @else
                                            <span class="badge badge-dark">{{ ucwords(str_replace('_', ' ', $timeOff->type)) }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <p>
                                        @if($timeOff->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($timeOff->status == 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($timeOff->status == 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Start Date</label>
                                    <p>{{ \Carbon\Carbon::parse($timeOff->start_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <p>{{ \Carbon\Carbon::parse($timeOff->end_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Total Days</label>
                                    <p>{{ $timeOff->days }} days</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Created At</label>
                                    <p>{{ $timeOff->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Additional Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Reason</label>
                            <div class="p-3 bg-light rounded">
                                {{ $timeOff->reason }}
                            </div>
                        </div>

                        @if($timeOff->document_url)
                        <div class="form-group">
                            <label>Supporting Document</label>
                            <div>
                                @php
                                    $extension = pathinfo($timeOff->document_url, PATHINFO_EXTENSION);
                                @endphp

                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                    <div class="document-preview">
                                        <img src="{{ asset('storage/' . $timeOff->document_url) }}"
                                            alt="Supporting Document" class="img-thumbnail mb-3" style="max-width: 300px;">
                                    </div>
                                @else
                                    <a href="{{ asset('storage/' . $timeOff->document_url) }}" class="btn btn-primary" target="_blank">
                                        <i class="fas fa-file-download mr-1"></i> Download Document
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>

                    @if($timeOff->status == 'pending')
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ route('time_offs.approve', $timeOff->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-lg btn-block" onclick="return confirm('Are you sure you want to approve this request?')">
                                        <i class="fas fa-check mr-1"></i> Approve Request
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('time_offs.reject', $timeOff->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger btn-lg btn-block" onclick="return confirm('Are you sure you want to reject this request?')">
                                        <i class="fas fa-times mr-1"></i> Reject Request
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <a href="{{ route('time_offs.edit', $timeOff->id) }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-edit mr-1"></i> Edit Request
                                </a>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('time_offs.edit', $timeOff->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit mr-1"></i> Edit Request
                                </a>
                                <form action="{{ route('time_offs.destroy', $timeOff->id) }}" method="POST" class="d-inline ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this time off request?')">
                                        <i class="fas fa-trash mr-1"></i> Delete Request
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    &lt;!-- JS Libraries -->
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>
@endpush
