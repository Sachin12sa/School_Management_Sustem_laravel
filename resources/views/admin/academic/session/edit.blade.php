{{-- ═══ academic_session/add.blade.php ═══ --}}
@extends('layouts.app')
@section('content')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;background:#fef3c7;color:#d97706;">
                                <i class="bi bi-calendar3-range-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">
                                    @isset($getRecord)
                                        Edit Academic Session
                                    @else
                                        Add New Academic Session
                                    @endisset
                                </h4>
                                <span class="text-muted small">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <a href="{{ url('admin/academic_session/list') }}"
                                        class="text-muted text-decoration-none">Back to Sessions</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @include('message')

                <div class="row justify-content-center">
                    <div class="col-md-5">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                                <i class="bi bi-calendar3 " style="color:#d97706;"></i>
                                <h6 class="mb-0 fw-semibold">Session Details</h6>
                            </div>

                            <form method="POST"
                                action="{{ isset($getRecord)
                                    ? url('admin/academic_session/update/' . $getRecord->id)
                                    : url('admin/academic_session/insert') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-secondary">
                                                Session Name / Year <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name"
                                                value="{{ old('name', $getRecord->name ?? '') }}" required
                                                placeholder="e.g. 2082, 2082-83"
                                                class="form-control @error('name') is-invalid @enderror">
                                            <div class="form-text">Use the Nepali BS year e.g. <strong>2082</strong></div>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-secondary">Label
                                                (optional)</label>
                                            <input type="text" name="label"
                                                value="{{ old('label', $getRecord->label ?? '') }}"
                                                placeholder="e.g. Academic Year 2082 B.S."
                                                class="form-control @error('label') is-invalid @enderror">
                                            @error('label')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">Start Date</label>
                                            <x-bs-date-input name="start_date"
                                                value="{{ old('start_date', $getRecord->start_date ?? '') }}"
                                                class="form-control @error('start_date') is-invalid @enderror" />
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold small text-secondary">End Date</label>
                                            <x-bs-date-input name="end_date"
                                                value="{{ old('end_date', $getRecord->end_date ?? '') }}"
                                                class="form-control @error('end_date') is-invalid @enderror" />
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>

                                <div
                                    class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                                    <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Fields marked <span
                                            class="text-danger">*</span> are required.</span>
                                    <div class="d-flex gap-2">
                                        <a href="{{ url('admin/academic_session/list') }}"
                                            class="btn btn-outline-secondary px-4">
                                            <i class="bi bi-x-circle me-1"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn px-4 fw-semibold text-white"
                                            style="background:#d97706;">
                                            @isset($getRecord)
                                                <i class="bi bi-floppy-fill me-2"></i>Update Session
                                            @else
                                                <i class="bi bi-plus-circle-fill me-2"></i>Create Session
                                            @endisset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
