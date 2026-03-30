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
    </style>
@endsection

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-patch-check me-2 text-primary"></i>Edit Certificate Template
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

                <form action="{{ url('admin/certificate/update/' . $getRecord->id) }}" method="POST"
                    enctype="multipart/form-data" id="certForm">
                    @csrf

                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body px-4 py-3">

                            {{-- Certificate Name --}}
                            <div class="field-row">
                                <div class="field-label">Certificate Name <span class="req">*</span></div>
                                <div>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $getRecord->name) }}" required>
                                </div>
                            </div>

                            {{-- Applicable User --}}
                            <div class="field-row">
                                <div class="field-label">Applicable User <span class="req">*</span></div>
                                <div>
                                    <select name="applicable_user" class="form-select" required>
                                        <option value="student"
                                            {{ old('applicable_user', $getRecord->applicable_user) === 'student' ? 'selected' : '' }}>
                                            Student</option>
                                        <option value="employee"
                                            {{ old('applicable_user', $getRecord->applicable_user) === 'employee' ? 'selected' : '' }}>
                                            Employee</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Page Layout --}}
                            <div class="field-row">
                                <div class="field-label">Page Layout <span class="req">*</span></div>
                                <div>
                                    <select name="page_layout" class="form-select" required>
                                        @foreach (['A4_landscape' => 'A4 (Landscape)', 'A4_portrait' => 'A4 (Portrait)', 'A5_landscape' => 'A5 (Landscape)', 'A5_portrait' => 'A5 (Portrait)'] as $val => $label)
                                            <option value="{{ $val }}"
                                                {{ old('page_layout', $getRecord->page_layout) === $val ? 'selected' : '' }}>
                                                {{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- User Photo Style --}}
                            <div class="field-row">
                                <div class="field-label">User Photo Style <span class="req">*</span></div>
                                <div class="d-flex gap-3 align-items-center flex-wrap">
                                    <select name="photo_style" class="form-select" style="max-width:220px;" required>
                                        <option value="square"
                                            {{ old('photo_style', $getRecord->photo_style) === 'square' ? 'selected' : '' }}>
                                            Square</option>
                                        <option value="circle"
                                            {{ old('photo_style', $getRecord->photo_style) === 'circle' ? 'selected' : '' }}>
                                            Circle</option>
                                        <option value="none"
                                            {{ old('photo_style', $getRecord->photo_style) === 'none' ? 'selected' : '' }}>
                                            None (No Photo)</option>
                                    </select>
                                    <input type="number" name="photo_size" class="form-control" style="max-width:160px;"
                                        placeholder="Photo Size (px)"
                                        value="{{ old('photo_size', $getRecord->photo_size) }}" min="0">
                                </div>
                            </div>

                            {{-- Layout Spacing --}}
                            <div class="field-row">
                                <div class="field-label">Layout Spacing <span class="req">*</span></div>
                                <div class="spacing-grid">
                                    <input type="number" name="top_space" class="form-control" placeholder="Top Space (px)"
                                        value="{{ old('top_space', $getRecord->top_space) }}" min="0">
                                    <input type="number" name="bottom_space" class="form-control"
                                        placeholder="Bottom Space (px)"
                                        value="{{ old('bottom_space', $getRecord->bottom_space) }}" min="0">
                                    <input type="number" name="right_space" class="form-control"
                                        placeholder="Right Space (px)"
                                        value="{{ old('right_space', $getRecord->right_space) }}" min="0">
                                    <input type="number" name="left_space" class="form-control"
                                        placeholder="Left Space (px)"
                                        value="{{ old('left_space', $getRecord->left_space) }}" min="0">
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
                                    <div class="file-preview" id="sig-preview">
                                        @if ($getRecord->signature_image)
                                            <img src="{{ asset('storage/' . $getRecord->signature_image) }}"
                                                alt="Signature">
                                            <span class="text-muted small">Current signature</span>
                                        @endif
                                    </div>
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
                                    <div class="file-preview" id="logo-preview">
                                        @if ($getRecord->logo_image)
                                            <img src="{{ asset('storage/' . $getRecord->logo_image) }}" alt="Logo">
                                            <span class="text-muted small">Current logo</span>
                                        @endif
                                    </div>
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
                                    <div class="file-preview" id="bg-preview">
                                        @if ($getRecord->background_image)
                                            <img src="{{ asset('storage/' . $getRecord->background_image) }}"
                                                alt="Background">
                                            <span class="text-muted small">Current background</span>
                                        @endif
                                    </div>
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
                                    <textarea name="content" id="certContent" class="form-control" rows="10">{!! old('content', $getRecord->content) !!}</textarea>
                                </div>
                            </div>

                        </div>

                        <div class="card-footer bg-transparent text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Update
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
                '82'],
        });

        function previewFile(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.innerHTML =
                        `<img src="${e.target.result}" alt="Preview"><span>${input.files[0].name}</span>`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
