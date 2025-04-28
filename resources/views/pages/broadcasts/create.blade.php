@extends('layouts.app')

@section('title', 'Create Broadcast')

@push('style')
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

        .file-upload-wrapper {
            position: relative;
            width: 100%;
            height: 120px;
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #fff;
            transition: all 0.3s ease;
        }

        .file-upload-wrapper:hover {
            border-color: var(--primary-color);
            background-color: rgba(99, 102, 241, 0.05);
        }

        .file-upload-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 10;
        }

        .file-upload-message {
            text-align: center;
            padding: 20px;
        }

        .file-upload-icon {
            font-size: 24px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .file-upload-text {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .file-preview {
            display: none;
            width: 100%;
            padding: 10px;
            background-color: rgba(99, 102, 241, 0.05);
            border-radius: 8px;
            margin-top: 10px;
        }

        .file-preview.active {
            display: flex;
            align-items: center;
        }

        .file-preview-icon {
            font-size: 24px;
            color: var(--primary-color);
            margin-right: 15px;
        }

        .file-preview-info {
            flex: 1;
        }

        .file-preview-name {
            font-weight: 500;
            margin-bottom: 2px;
            word-break: break-all;
        }

        .file-preview-size {
            font-size: 12px;
            color: var(--text-secondary);
        }

        .file-preview-remove {
            color: var(--danger-color);
            cursor: pointer;
            margin-left: 10px;
        }

        .select2-container--default .select2-selection--multiple {
            border-color: var(--border-color);
            border-radius: 6px;
        }

        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: var(--primary-color);
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            border-radius: 4px;
            padding: 2px 8px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: rgba(255, 255, 255, 0.7);
            margin-right: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: white;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between align-items-center">
                <div>
                    <h1>Create New Broadcast</h1>
                    <div class="section-header-breadcrumb mt-1">
                        <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                        <div class="breadcrumb-item"><a href="{{ route('broadcasts.index') }}">Broadcasts</a></div>
                        <div class="breadcrumb-item">Create Broadcast</div>
                    </div>
                </div>
                <a href="{{ route('broadcasts.index') }}" class="btn btn-primary">
                  Back to Broadcasts
                </a>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Broadcast Information</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('broadcasts.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="form-group">
                                        <label for="title">Broadcast Title <span class="text-danger">*</span></label>
                                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="message">Message <span class="text-danger">*</span></label>
                                        <textarea id="message" name="message" class="form-control @error('message') is-invalid @enderror" style="height: 150px;" required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="file">Attachment (Optional)</label>
                                        <div class="file-upload-wrapper">
                                            <input type="file" id="file" name="file" class="file-upload-input @error('file') is-invalid @enderror">
                                            <div class="file-upload-message">
                                                <div class="file-upload-icon">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                </div>
                                                <div class="file-upload-text">
                                                    <strong>Click to upload</strong> or drag and drop<br>
                                                    <span class="text-muted">Max file size: 10MB</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="file-preview">
                                            <div class="file-preview-icon">
                                                <i class="fas fa-file"></i>
                                            </div>
                                            <div class="file-preview-info">
                                                <div class="file-preview-name"></div>
                                                <div class="file-preview-size"></div>
                                            </div>
                                            <div class="file-preview-remove">
                                                <i class="fas fa-times"></i>
                                            </div>
                                        </div>
                                        @error('file')
                                            <div class="text-danger mt-2">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="send_to_all" name="send_to_all" value="1" {{ old('send_to_all') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="send_to_all">Send to all users</label>
                                            <div class="text-muted small">This will send the broadcast to all users in the system ({{ \App\Models\User::count() }} users)</div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="recipients-container">
                                        <label for="recipients">Recipients <span class="text-danger">*</span></label>
                                        <select id="recipients" name="recipients[]" class="form-control select2 @error('recipients') is-invalid @enderror" multiple>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ in_array($user->id, old('recipients', [])) ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('recipients')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="send_now" name="send_now" value="1" {{ old('send_now') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="send_now">Send immediately</label>
                                            <div class="text-muted small">If unchecked, broadcast will be saved as draft</div>
                                        </div>
                                    </div>

                                    <div class="form-group text-right">
                                        <a href="{{ route('broadcasts.index') }}" class="btn btn-light mr-2">Cancel</a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i> Save Broadcast
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
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Select recipients",
                allowClear: true
            });

            // File upload preview
            $('#file').on('change', function(e) {
                var file = e.target.files[0];
                if (file) {
                    $('.file-preview').addClass('active');
                    $('.file-preview-name').text(file.name);

                    // Format file size
                    var size = file.size;
                    var sizeStr = '';
                    if (size < 1024) {
                        sizeStr = size + ' bytes';
                    } else if (size < 1024 * 1024) {
                        sizeStr = (size / 1024).toFixed(2) + ' KB';
                    } else {
                        sizeStr = (size / (1024 * 1024)).toFixed(2) + ' MB';
                    }

                    $('.file-preview-size').text(sizeStr);

                    // Set icon based on file type
                    var fileType = file.type.split('/')[0];
                    var iconClass = 'fas fa-file';

                    if (fileType === 'image') {
                        iconClass = 'fas fa-file-image';
                    } else if (fileType === 'video') {
                        iconClass = 'fas fa-file-video';
                    } else if (fileType === 'audio') {
                        iconClass = 'fas fa-file-audio';
                    } else if (file.name.endsWith('.pdf')) {
                        iconClass = 'fas fa-file-pdf';
                    } else if (file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
                        iconClass = 'fas fa-file-word';
                    } else if (file.name.endsWith('.xls') || file.name.endsWith('.xlsx')) {
                        iconClass = 'fas fa-file-excel';
                    }

                    $('.file-preview-icon i').attr('class', iconClass);
                } else {
                    $('.file-preview').removeClass('active');
                }
            });

            // Toggle recipients field based on send_to_all checkbox
            $('#send_to_all').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#recipients-container').hide();
                    $('#recipients').prop('required', false);
                    // Clear the select2 selection when switching to "send to all"
                    $('#recipients').val(null).trigger('change');
                } else {
                    $('#recipients-container').show();
                    $('#recipients').prop('required', true);
                }
            });

            // Initialize on page load
            if ($('#send_to_all').is(':checked')) {
                $('#recipients-container').hide();
                $('#recipients').prop('required', false);
            }

            // Remove file
            $('.file-preview-remove').on('click', function() {
                $('#file').val('');
                $('.file-preview').removeClass('active');
            });
        });
    </script>
@endpush
