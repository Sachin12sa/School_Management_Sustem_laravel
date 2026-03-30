@extends('layouts.app')
@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center py-1">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold">
                            <i class="bi bi-person-badge-fill me-2 text-primary"></i>ID Card Templates
                        </h4>
                        <span class="text-muted small">Design reusable ID card templates for students and staff</span>
                    </div>
                    <div class="col-sm-6 text-sm-end d-flex gap-2 justify-content-sm-end flex-wrap">
                        <a href="{{ url('admin/id_card/student_generate') }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-people-fill me-1"></i>Student ID Cards
                        </a>
                        <a href="{{ url('admin/id_card/staff_generate') }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-person-workspace me-1"></i>Staff ID Cards
                        </a>
                        <a href="{{ url('admin/id_card/add') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>Add Template
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($getRecord->isEmpty())
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body text-center py-5">
                            <i class="bi bi-person-badge d-block mb-3 text-primary" style="font-size:3rem;opacity:.3;"></i>
                            <div class="fw-semibold text-dark mb-1">No templates yet</div>
                            <p class="text-muted small mb-3">Create your first ID card template to get started.</p>
                            <a href="{{ url('admin/id_card/add') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>Create Template
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach ($getRecord as $tpl)
                            @php
                                $userLabels = [
                                    'student' => 'Student',
                                    'teacher' => 'Teacher',
                                    'admin' => 'Admin',
                                    'accountant' => 'Accountant',
                                    'librarian' => 'Librarian',
                                ];
                                $userColors = [
                                    'student' => 'primary',
                                    'teacher' => 'success',
                                    'admin' => 'danger',
                                    'accountant' => 'warning',
                                    'librarian' => 'info',
                                ];
                                $uColor = $userColors[$tpl->applicable_user] ?? 'secondary';
                                $uLabel = $userLabels[$tpl->applicable_user] ?? ucfirst($tpl->applicable_user);
                            @endphp
                            <div class="col-md-6 col-xl-4">
                                <div class="card border-0 shadow-sm rounded-3 h-100">
                                    {{-- Card preview thumbnail --}}
                                    <div class="card-img-top position-relative overflow-hidden"
                                        style="height:160px;background:{{ $tpl->accent_color }};border-radius:.75rem .75rem 0 0;">
                                        @if ($tpl->background_image)
                                            <img src="{{ asset('storage/' . $tpl->background_image) }}"
                                                class="w-100 h-100 position-absolute top-0 start-0"
                                                style="object-fit:cover;opacity:.35;">
                                        @endif
                                        {{-- Mini card preview --}}
                                        <div class="position-absolute top-50 start-50 translate-middle"
                                            style="width:90px;height:130px;background:#fff;border-radius:8px;box-shadow:0 4px 20px rgba(0,0,0,.3);overflow:hidden;">
                                            <div style="height:38px;background:{{ $tpl->accent_color }};"></div>
                                            <div class="d-flex flex-column align-items-center mt-1 gap-1 px-1">
                                                <div class="rounded-circle bg-light"
                                                    style="width:28px;height:28px;margin-top:-14px;border:2px solid #fff;">
                                                </div>
                                                <div style="height:5px;width:60px;background:#e5e7eb;border-radius:3px;">
                                                </div>
                                                <div style="height:3px;width:40px;background:#e5e7eb;border-radius:3px;">
                                                </div>
                                                <div
                                                    style="height:3px;width:50px;background:#e5e7eb;border-radius:3px;margin-top:4px;">
                                                </div>
                                                <div style="height:3px;width:35px;background:#e5e7eb;border-radius:3px;">
                                                </div>
                                                <div
                                                    style="height:16px;width:16px;background:#f3f4f6;border-radius:3px;margin-top:4px;">
                                                </div>
                                            </div>
                                        </div>
                                        {{-- User type badge --}}
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-{{ $uColor }}">
                                            {{ $uLabel }}
                                        </span>
                                    </div>

                                    <div class="card-body">
                                        <div class="fw-bold mb-1">{{ $tpl->name }}</div>
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            <span class="badge bg-light text-dark border" style="font-size:.68rem;">
                                                <i class="bi bi-aspect-ratio me-1"></i>
                                                {{ $tpl->layout_width }}mm × {{ $tpl->layout_height }}mm
                                            </span>
                                            <span class="badge bg-light text-dark border" style="font-size:.68rem;">
                                                <i class="bi bi-person-circle me-1"></i>{{ ucfirst($tpl->photo_style) }}
                                            </span>
                                            @if ($tpl->logo_image)
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"
                                                    style="font-size:.68rem;">
                                                    <i class="bi bi-check-circle me-1"></i>Logo
                                                </span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center gap-1 mb-3">
                                            <div class="rounded-circle border"
                                                style="width:18px;height:18px;background:{{ $tpl->accent_color }};"></div>
                                            <span class="text-muted"
                                                style="font-size:.72rem;">{{ $tpl->accent_color }}</span>
                                        </div>
                                        <div class="text-muted" style="font-size:.7rem;">
                                            Created {{ $tpl->created_at->format('d M Y') }}
                                        </div>
                                    </div>

                                    <div class="card-footer bg-white border-top d-flex gap-2 py-2">
                                        <a href="{{ url('admin/id_card/edit/' . $tpl->id) }}"
                                            class="btn btn-sm btn-outline-primary flex-fill">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                        @if ($tpl->applicable_user === 'student')
                                            <a href="{{ url('admin/id_card/student_generate?template_id=' . $tpl->id) }}"
                                                class="btn btn-sm btn-outline-success flex-fill">
                                                <i class="bi bi-printer me-1"></i>Generate
                                            </a>
                                        @else
                                            <a href="{{ url('admin/id_card/staff_generate?template_id=' . $tpl->id) }}"
                                                class="btn btn-sm btn-outline-success flex-fill">
                                                <i class="bi bi-printer me-1"></i>Generate
                                            </a>
                                        @endif
                                        <a href="{{ url('admin/id_card/delete/' . $tpl->id) }}"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this template?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </main>
@endsection
