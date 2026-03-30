@extends('layouts.app')

@section('style')
    <style>
        /* ── Live preview ─────────────────────────────────────────── */
        #card-preview-wrap {
            position: sticky;
            top: 80px;
        }

        .id-card-preview {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 40px rgba(0, 0, 0, .18);
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
            font-family: 'Arial', sans-serif;
            transition: all .3s;
        }

        .icp-header {
            padding: 16px 14px 12px;
            text-align: center;
            position: relative;
        }

        .icp-logo {
            width: 36px;
            height: 36px;
            object-fit: contain;
            position: absolute;
            top: 12px;
            left: 12px;
        }

        .icp-school-name {
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .04em;
            line-height: 1.2;
        }

        .icp-school-sub {
            font-size: .55rem;
            opacity: .7;
            margin-top: 1px;
        }

        .icp-photo-wrap {
            display: flex;
            justify-content: center;
            margin-top: -20px;
            position: relative;
            z-index: 2;
        }

        .icp-photo {
            background: #e5e7eb;
            border: 3px solid #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 1.2rem;
        }

        .icp-body {
            padding: 10px 14px 14px;
            text-align: center;
        }

        .icp-name {
            font-size: .85rem;
            font-weight: 800;
            color: #111827;
        }

        .icp-role {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .icp-divider {
            height: 1px;
            background: #f3f4f6;
            margin: 8px 0;
        }

        .icp-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .icp-info-label {
            font-size: .6rem;
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .icp-info-val {
            font-size: .65rem;
            color: #374151;
            font-weight: 600;
        }

        .icp-footer {
            padding: 8px 14px;
            text-align: center;
            font-size: .55rem;
        }

        .icp-qr-stub {
            width: 32px;
            height: 32px;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            margin: 6px auto 0;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            padding: 3px;
        }

        .icp-qr-stub span {
            background: #374151;
            border-radius: 1px;
        }

        /* ── Form card ────────────────────────────────────────────── */
        .form-section {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .form-section .fs-head {
            padding: 12px 18px;
            background: #fafafa;
            border-bottom: 1px solid #f3f4f6;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .form-section .fs-body {
            padding: 18px;
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
                            <i class="bi bi-person-badge-fill me-2 text-primary"></i>
                            {{ isset($getRecord) ? 'Edit Template' : 'New ID Card Template' }}
                        </h4>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <a href="{{ url('admin/id_card/list') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i>Back
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

                <form
                    action="{{ isset($getRecord) ? url('admin/id_card/edit/' . $getRecord->id) : url('admin/id_card/add') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-4">

                        {{-- ── LEFT: Form ── --}}
                        <div class="col-lg-7">

                            {{-- Basic Info --}}
                            <div class="form-section">
                                <div class="fs-head"><i class="bi bi-info-circle text-primary"></i>Basic Information</div>
                                <div class="fs-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Template Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name', $getRecord->name ?? '') }}"
                                                placeholder="e.g. Student Card 2082" required id="inp-name">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Applicable User <span
                                                    class="text-danger">*</span></label>
                                            <select name="applicable_user" class="form-select" required id="inp-user">
                                                @foreach (['student' => 'Student', 'teacher' => 'Teacher', 'admin' => 'Admin', 'accountant' => 'Accountant', 'librarian' => 'Librarian'] as $val => $lbl)
                                                    <option value="{{ $val }}"
                                                        {{ old('applicable_user', $getRecord->applicable_user ?? '') == $val ? 'selected' : '' }}>
                                                        {{ $lbl }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Layout & Design --}}
                            <div class="form-section">
                                <div class="fs-head"><i class="bi bi-layout-three-columns text-primary"></i>Layout & Design
                                </div>
                                <div class="fs-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Width (mm) <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="layout_width" step="0.1" class="form-control"
                                                value="{{ old('layout_width', $getRecord->layout_width ?? '85.60') }}"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Height (mm) <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="layout_height" step="0.1" class="form-control"
                                                value="{{ old('layout_height', $getRecord->layout_height ?? '54.00') }}"
                                                required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Accent Color</label>
                                            <div class="input-group">
                                                <input type="color" name="accent_color"
                                                    class="form-control form-control-color"
                                                    value="{{ old('accent_color', $getRecord->accent_color ?? '#1a56a0') }}"
                                                    id="inp-accent" style="width:50px;">
                                                <input type="text" class="form-control" id="accent-hex"
                                                    value="{{ old('accent_color', $getRecord->accent_color ?? '#1a56a0') }}"
                                                    readonly style="font-family:monospace;">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Header Text Color</label>
                                            <div class="input-group">
                                                <input type="color" name="text_color"
                                                    class="form-control form-control-color"
                                                    value="{{ old('text_color', $getRecord->text_color ?? '#ffffff') }}"
                                                    id="inp-text-color" style="width:50px;">
                                                <input type="text" class="form-control" id="text-color-hex"
                                                    value="{{ old('text_color', $getRecord->text_color ?? '#ffffff') }}"
                                                    readonly style="font-family:monospace;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Photo --}}
                            <div class="form-section">
                                <div class="fs-head"><i class="bi bi-person-circle text-primary"></i>Photo Settings</div>
                                <div class="fs-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Photo Style <span
                                                    class="text-danger">*</span></label>
                                            <select name="photo_style" class="form-select" id="inp-photo-style" required>
                                                @foreach (['circle' => 'Circle', 'square' => 'Square', 'rounded' => 'Rounded'] as $val => $lbl)
                                                    <option value="{{ $val }}"
                                                        {{ old('photo_style', $getRecord->photo_style ?? 'circle') == $val ? 'selected' : '' }}>
                                                        {{ $lbl }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small">Photo Size (px)</label>
                                            <input type="number" name="photo_size" class="form-control"
                                                value="{{ old('photo_size', $getRecord->photo_size ?? 80) }}"
                                                min="30" max="200" id="inp-photo-size">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Spacing --}}
                            <div class="form-section">
                                <div class="fs-head"><i class="bi bi-arrows-fullscreen text-primary"></i>Layout Spacing
                                    (px)</div>
                                <div class="fs-body">
                                    <div class="row g-3">
                                        <div class="col-6 col-md-3">
                                            <label class="form-label fw-semibold small">Top</label>
                                            <input type="number" name="top_space" class="form-control"
                                                value="{{ old('top_space', $getRecord->top_space ?? 10) }}">
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <label class="form-label fw-semibold small">Bottom</label>
                                            <input type="number" name="bottom_space" class="form-control"
                                                value="{{ old('bottom_space', $getRecord->bottom_space ?? 10) }}">
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <label class="form-label fw-semibold small">Left</label>
                                            <input type="number" name="left_space" class="form-control"
                                                value="{{ old('left_space', $getRecord->left_space ?? 10) }}">
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <label class="form-label fw-semibold small">Right</label>
                                            <input type="number" name="right_space" class="form-control"
                                                value="{{ old('right_space', $getRecord->right_space ?? 10) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Images --}}
                            <div class="form-section">
                                <div class="fs-head"><i class="bi bi-images text-primary"></i>Images</div>
                                <div class="fs-body">
                                    <div class="row g-3">
                                        @foreach (['logo_image' => ['School Logo', 'bi-building'], 'signature_image' => ['Signature', 'bi-pen'], 'background_image' => ['Background Image', 'bi-image']] as $field => [$label, $icon])
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small">
                                                    <i class="bi {{ $icon }} me-1"></i>{{ $label }}
                                                </label>
                                                @if (isset($getRecord) && $getRecord->$field)
                                                    <div class="mb-2">
                                                        <img src="{{ asset('storage/' . $getRecord->$field) }}"
                                                            class="rounded border"
                                                            style="height:50px;object-fit:contain;">
                                                    </div>
                                                @endif
                                                <input type="file" name="{{ $field }}"
                                                    class="form-control form-control-sm" accept="image/*">
                                                @error($field)
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            {{-- Extra content --}}
                            <div class="form-section">
                                <div class="fs-head"><i class="bi bi-textarea-t text-primary"></i>Footer / Extra Content
                                </div>
                                <div class="fs-body">
                                    <textarea name="extra_content" class="form-control" rows="3"
                                        placeholder="Optional footer text, e.g. school address, hotline…">{{ old('extra_content', $getRecord->extra_content ?? '') }}</textarea>
                                    <div class="form-text">This text appears at the bottom of every card.</div>
                                </div>
                            </div>

                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ url('admin/id_card/list') }}"
                                    class="btn btn-outline-secondary px-4">Cancel</a>
                                <button type="submit" class="btn btn-primary px-5 fw-semibold">
                                    <i class="bi bi-floppy-fill me-2"></i>
                                    {{ isset($getRecord) ? 'Update Template' : 'Save Template' }}
                                </button>
                            </div>
                        </div>

                        {{-- ── RIGHT: Live Preview ── --}}
                        <div class="col-lg-5" id="card-preview-wrap">
                            <div class="card border-0 shadow-sm rounded-3 mb-3">
                                <div class="card-header bg-transparent border-bottom py-2">
                                    <span class="fw-semibold small text-muted text-uppercase"
                                        style="letter-spacing:.06em;">
                                        <i class="bi bi-eye me-1"></i>Live Preview
                                    </span>
                                </div>
                                <div class="card-body text-center py-4" style="background:#f8faff;">

                                    {{-- FRONT --}}
                                    <div class="small fw-semibold text-muted mb-2 text-uppercase"
                                        style="letter-spacing:.06em;font-size:.65rem;">Front</div>
                                    <div class="id-card-preview" id="preview-card">
                                        <div class="icp-header" id="prev-header" style="background:#1a56a0;color:#fff;">
                                            <div class="icp-school-name" id="prev-school">Brain Fart Institute</div>
                                            <div class="icp-school-sub">School Management System</div>
                                        </div>
                                        <div class="icp-photo-wrap" style="margin-top:-22px;">
                                            <div class="icp-photo" id="prev-photo"
                                                style="width:56px;height:56px;border-radius:50%;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                        </div>
                                        <div class="icp-body">
                                            <div class="icp-name">Student Name</div>
                                            <div class="icp-role" id="prev-role" style="color:#1a56a0;">Student</div>
                                            <div class="icp-divider"></div>
                                            <div class="icp-info-row">
                                                <span class="icp-info-label">Adm. No</span>
                                                <span class="icp-info-val">ADM-2082-001</span>
                                            </div>
                                            <div class="icp-info-row">
                                                <span class="icp-info-label">Class</span>
                                                <span class="icp-info-val">Class X — A</span>
                                            </div>
                                            <div class="icp-info-row">
                                                <span class="icp-info-label">Blood</span>
                                                <span class="icp-info-val">A+</span>
                                            </div>
                                            <div class="icp-info-row">
                                                <span class="icp-info-label">Mobile</span>
                                                <span class="icp-info-val">98XXXXXXXX</span>
                                            </div>
                                            <div class="icp-divider"></div>
                                            <div style="display:flex;align-items:center;justify-content:space-between;">
                                                <div style="text-align:left;">
                                                    <div style="font-size:.55rem;color:#9ca3af;">Valid until</div>
                                                    <div style="font-size:.62rem;font-weight:700;">2083 Chaitra</div>
                                                </div>
                                                <div class="icp-qr-stub">
                                                    <span></span><span
                                                        style="background:transparent;"></span><span></span><span></span>
                                                    <span style="background:transparent;"></span><span></span><span
                                                        style="background:transparent;"></span><span></span>
                                                    <span></span><span></span><span></span><span
                                                        style="background:transparent;"></span>
                                                    <span style="background:transparent;"></span><span></span><span
                                                        style="background:transparent;"></span><span></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="icp-footer" id="prev-footer"
                                            style="background:#f8faff;color:#6b7280;">
                                            Brain Fart Institute • Kathmandu, Nepal
                                        </div>
                                    </div>

                                    <div class="mt-3 text-muted" style="font-size:.68rem;">
                                        <i class="bi bi-info-circle me-1"></i>
                                        Changes reflect as you type. Actual card uses real student data.
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        // ── Live preview updates ──────────────────────────────────────
        document.getElementById('inp-accent').addEventListener('input', function() {
            var c = this.value;
            document.getElementById('accent-hex').value = c;
            document.getElementById('prev-header').style.background = c;
            document.getElementById('prev-role').style.color = c;
        });

        document.getElementById('inp-text-color').addEventListener('input', function() {
            var c = this.value;
            document.getElementById('text-color-hex').value = c;
            document.getElementById('prev-header').style.color = c;
        });

        document.getElementById('inp-user').addEventListener('change', function() {
            var labels = {
                student: 'Student',
                teacher: 'Teacher',
                admin: 'Administrator',
                accountant: 'Accountant',
                librarian: 'Librarian'
            };
            document.getElementById('prev-role').textContent = labels[this.value] || this.value;
        });

        document.getElementById('inp-photo-style').addEventListener('change', function() {
            var el = document.getElementById('prev-photo');
            var r = {
                circle: '50%',
                square: '0%',
                rounded: '8px'
            } [this.value] || '50%';
            el.style.borderRadius = r;
        });

        document.getElementById('inp-photo-size').addEventListener('input', function() {
            var sz = Math.max(30, Math.min(120, parseInt(this.value) || 56));
            var el = document.getElementById('prev-photo');
            el.style.width = sz + 'px';
            el.style.height = sz + 'px';
            el.style.fontSize = (sz * 0.4) + 'px';
        });
    </script>
@endsection
