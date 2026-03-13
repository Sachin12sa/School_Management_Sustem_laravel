@extends('layouts.app')
@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Add New Grade</h4>
                            <span class="text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>
                                <a href="{{ url('admin/examination/marks_grade/list') }}" class="text-muted text-decoration-none">Back to Grades List</a>
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
                            <i class="bi bi-award-fill text-success"></i>
                            <h6 class="mb-0 fw-semibold">Grade Details</h6>
                        </div>

                        <form method="post" action="">
                            @csrf
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">
                                            Grade Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name"
                                               value="{{ old('name') }}" required
                                               placeholder="e.g. A+, A, B+, B…"
                                               class="form-control @error('name') is-invalid @enderror">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">
                                            Percent From <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="percent_from"
                                                   value="{{ old('percent_from') }}" required
                                                   min="0" max="100" step="0.01"
                                                   placeholder="0"
                                                   class="form-control @error('percent_from') is-invalid @enderror">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        @error('percent_from') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">
                                            Percent To <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" name="percent_to"
                                                   value="{{ old('percent_to') }}" required
                                                   min="0" max="100" step="0.01"
                                                   placeholder="100"
                                                   class="form-control @error('percent_to') is-invalid @enderror">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        @error('percent_to') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Fields marked <span class="text-danger">*</span> are required.</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ url('admin/examination/marks_grade/list') }}" class="btn btn-outline-secondary px-4">
                                        <i class="bi bi-x-circle me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-success px-4 fw-semibold">
                                        <i class="bi bi-plus-circle-fill me-2"></i>Create Grade
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