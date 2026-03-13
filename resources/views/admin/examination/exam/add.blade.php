@extends('layouts.app')
@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;background:rgba(111,66,193,.1);color:#6f42c1;">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div>
                            {{-- Fixed: title was "Add New Exam" but h3 was same --}}
                            <h4 class="mb-0 fw-semibold text-dark">Add New Exam</h4>
                            <span class="text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>
                                <a href="{{ url('admin/examination/exam/list') }}" class="text-muted text-decoration-none">Back to Exam List</a>
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
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                            <i class="bi bi-mortarboard-fill" style="color:#6f42c1;"></i>
                            <h6 class="mb-0 fw-semibold">Exam Details</h6>
                        </div>

                        <form method="post" action="">
                            @csrf
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">
                                            Exam Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name"
                                               value="{{ old('name') }}" required
                                               placeholder="e.g. Mid-Term 2025, Final Exam…"
                                               class="form-control @error('name') is-invalid @enderror">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">Note</label>
                                        <textarea name="note" rows="3"
                                                  placeholder="Optional notes about this exam…"
                                                  class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                                        @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Fields marked <span class="text-danger">*</span> are required.</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ url('admin/examination/exam/list') }}" class="btn btn-outline-secondary px-4">
                                        <i class="bi bi-x-circle me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn px-4 fw-semibold text-white" style="background:#6f42c1;">
                                        <i class="bi bi-plus-circle-fill me-2"></i>Create Exam
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