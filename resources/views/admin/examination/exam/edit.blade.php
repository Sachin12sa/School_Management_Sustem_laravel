@extends('layouts.app')
@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <div>
                            {{-- Fixed: title said "Add New Exam" on the edit page --}}
                            <h4 class="mb-0 fw-semibold text-dark">Edit Exam</h4>
                            <span class="text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>
                                <a href="{{ url('admin/examination/exam/list') }}" class="text-muted text-decoration-none">Back to Exam List</a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                        <i class="bi bi-mortarboard me-1"></i>{{ $getRecord->name }}
                    </span>
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
                            <i class="bi bi-pencil-fill text-warning"></i>
                            {{-- Fixed: card title said "Fill the details to add exam" --}}
                            <h6 class="mb-0 fw-semibold">Edit Exam Details</h6>
                        </div>

                        <form method="post" action="{{ url('admin/examination/exam/edit/' . $getRecord->id) }}">
                            @csrf
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">
                                            Exam Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name"
                                               value="{{ old('name', $getRecord->name) }}" required
                                               placeholder="Enter exam name"
                                               class="form-control @error('name') is-invalid @enderror">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">Note</label>
                                        <textarea name="note" rows="3"
                                                  placeholder="Optional notes…"
                                                  class="form-control @error('note') is-invalid @enderror">{{ old('note', $getRecord->note) }}</textarea>
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
                                    <button type="submit" class="btn btn-warning text-dark px-4 fw-semibold">
                                        <i class="bi bi-floppy-fill me-2"></i>Update Exam
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