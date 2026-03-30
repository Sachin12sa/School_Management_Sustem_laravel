@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.css">
    <style>
        .field-row {
            display: grid;
            grid-template-columns: 220px 1fr;
            align-items: start;
            gap: 0;
            border-bottom: 1px solid #f0f0f0;
            padding: 14px 0;
        }

        .field-row:last-child {
            border-bottom: none;
        }

        .field-label {
            font-size: .85rem;
            color: #6b7280;
            font-weight: 500;
            padding-top: 6px;
        }

        .field-label .req {
            color: #dc2626;
        }

        .upload-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border: 1.5px dashed #d1d5db;
            border-radius: 8px;
            cursor: pointer;
            color: #6b7280;
            font-size: .85rem;
            background: #fafafa;
            transition: all .15s;
        }

        .upload-btn:hover {
            border-color: #0d6efd;
            color: #0d6efd;
            background: #eff6ff;
        }

        .upload-btn input {
            display: none;
        }

        .file-preview {
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .8rem;
            color: #374151;
        }

        .file-preview img {
            height: 40px;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }

        .spacing-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .note-editor {
            border-radius: 0 0 8px 8px !important;
        }

        .note-toolbar {
            border-radius: 8px 8px 0 0 !important;
            background: #f8f9fa !important;
        }
    </style>
@endsection

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-patch-check me-2 text-primary"></i>Add Certificate Template
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/certificate/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ url('admin/certificate/add') }}" method="POST" enctype="multipart/form-data"
                    id="certForm">
                    @csrf

                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body px-4 py-3">

                            {{-- Certificate Name --}}
                            <div class="field-row">
                                <div class="field-label">Certificate Name <span class="req">*</span></div>
                                <div>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        required placeholder="e.g. SCHOOL CERTIFICATE (A4 Landscape)">
                                </div>
                            </div>

                            {{-- Applicable User --}}
                            <div class="field-row">
                                <div class="field-label">Applicable User <span class="req">*</span></div>
                                <div>
                                    <select name="applicable_user" class="form-select" required>
                                        <option value="">Select</option>
                                        <option value="student"
                                            {{ old('applicable_user') === 'student' ? 'selected' : '' }}>Student</option>
                                        <option value="employee"
                                            {{ old('applicable_user') === 'employee' ? 'selected' : '' }}>Employee</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Page Layout --}}
                            <div class="field-row">
                                <div class="field-label">Page Layout <span class="req">*</span></div>
                                <div>
                                    <select name="page_layout" class="form-select" required>
                                        <option value="">Select</option>
                                        <option value="A4_landscape"
                                            {{ old('page_layout') === 'A4_landscape' ? 'selected' : '' }}>A4 (Landscape)
                                        </option>
                                        <option value="A4_portrait"
                                            {{ old('page_layout') === 'A4_portrait' ? 'selected' : '' }}>A4 (Portrait)
                                        </option>
                                        <option value="A5_landscape"
                                            {{ old('page_layout') === 'A5_landscape' ? 'selected' : '' }}>A5 (Landscape)
                                        </option>
                                        <option value="A5_portrait"
                                            {{ old('page_layout') === 'A5_portrait' ? 'selected' : '' }}>A5 (Portrait)
                                        </option>
                                    </select>
                                </div>
                            </div>

                            {{-- User Photo Style --}}
                            <div class="field-row">
                                <div class="field-label">User Photo Style <span class="req">*</span></div>
                                <div class="d-flex gap-3 align-items-center flex-wrap">
                                    <select name="photo_style" class="form-select" style="max-width:220px;" required>
                                        <option value="square"
                                            {{ old('photo_style', 'square') === 'square' ? 'selected' : '' }}>Square
                                        </option>
                                        <option value="circle" {{ old('photo_style') === 'circle' ? 'selected' : '' }}>
                                            Circle</option>
                                        <option value="none" {{ old('photo_style') === 'none' ? 'selected' : '' }}>None
                                            (No Photo)</option>
                                    </select>
                                    <input type="number" name="photo_size" class="form-control" style="max-width:160px;"
                                        placeholder="Photo Size (px)" value="{{ old('photo_size', 100) }}" min="0">
                                </div>
                            </div>

                            {{-- Layout Spacing --}}
                            <div class="field-row">
                                <div class="field-label">Layout Spacing <span class="req">*</span></div>
                                <div class="spacing-grid">
                                    <input type="number" name="top_space" class="form-control" placeholder="Top Space (px)"
                                        value="{{ old('top_space', 0) }}" min="0">
                                    <input type="number" name="bottom_space" class="form-control"
                                        placeholder="Bottom Space (px)" value="{{ old('bottom_space', 0) }}"
                                        min="0">
                                    <input type="number" name="right_space" class="form-control"
                                        placeholder="Right Space (px)" value="{{ old('right_space', 0) }}" min="0">
                                    <input type="number" name="left_space" class="form-control"
                                        placeholder="Left Space (px)" value="{{ old('left_space', 0) }}" min="0">
                                </div>
                            </div>

                            {{-- Signature Image --}}
                            <div class="field-row">
                                <div class="field-label">Signature Image</div>
                                <div>
                                    <label class="upload-btn">
                                        <i class="bi bi-image"></i> Select file
                                        <input type="file" name="signature_image" accept="image/*"
                                            onchange="previewFile(this, 'sig-preview')">
                                    </label>
                                    <div class="file-preview" id="sig-preview"></div>
                                </div>
                            </div>

                            {{-- Logo Image --}}
                            <div class="field-row">
                                <div class="field-label">Logo Image</div>
                                <div>
                                    <label class="upload-btn">
                                        <i class="bi bi-image"></i> Select file
                                        <input type="file" name="logo_image" accept="image/*"
                                            onchange="previewFile(this, 'logo-preview')">
                                    </label>
                                    <div class="file-preview" id="logo-preview"></div>
                                </div>
                            </div>

                            {{-- Background Image --}}
                            <div class="field-row">
                                <div class="field-label">Background Image</div>
                                <div>
                                    <label class="upload-btn">
                                        <i class="bi bi-image"></i> Select file
                                        <input type="file" name="background_image" accept="image/*"
                                            onchange="previewFile(this, 'bg-preview')">
                                    </label>
                                    <div class="file-preview" id="bg-preview"></div>
                                </div>
                            </div>

                            {{-- Certificate Content --}}
                            <div class="field-row" style="align-items:start;">
                                <div class="field-label" style="padding-top:10px;">
                                    Certificate Content <span class="req">*</span>
                                    <div class="mt-2 small text-muted" style="font-size:.7rem;line-height:1.5;">
                                        Available variables:<br>
                                        <code>{name}</code> <code>{class}</code> <code>{section}</code><br>
                                        <code>{roll}</code> <code>{admission_no}</code><br>
                                        <code>{date}</code> <code>{father_name}</code>
                                    </div>
                                </div>
                                <div>
                                    <textarea name="content" id="certContent" class="form-control" rows="10">{{ old('content') }}</textarea>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer bg-transparent text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Save
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs5.min.js"></script>
    <script>
        // Initialise Summernote rich editor
        $('#certContent').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'undo']],
            ],
            fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '28', '32', '36', '48', '64',
                '82'
            ],
            placeholder: 'Type certificate content here. Use {name}, {class}, {date} etc. as placeholders...',
        });

        // File preview helper
        function previewFile(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <span>${input.files[0].name}</span>`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
