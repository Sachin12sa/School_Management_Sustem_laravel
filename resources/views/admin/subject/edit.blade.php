@extends('layouts.app')
@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;background:rgba(255,193,7,.15);color:#d39e00;">
                            <i class="bi bi-journal-text"></i>
                        </div>
                        <div>
                            {{-- Fixed: title said "Edit Class" --}}
                            <h4 class="mb-0 fw-semibold text-dark">Edit Subject</h4>
                            <span class="text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>
                                <a href="{{ url('admin/subject/list') }}" class="text-muted text-decoration-none">Back to Subject List</a>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                        <i class="bi bi-journal-text me-1"></i>{{ $getRecord->name }}
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
                            <h6 class="mb-0 fw-semibold">Subject Details</h6>
                        </div>

                        <form method="post" action="{{ url('admin/subject/edit/' . $getRecord->id) }}">
                            @csrf
                            <div class="card-body">
                                <div class="row g-3">

                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">
                                            Subject Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="name"
                                               value="{{ old('name', $getRecord->name) }}" required
                                               placeholder="Enter subject name"
                                               class="form-control @error('name') is-invalid @enderror">
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Subject Type <span class="text-danger">*</span></label>
                                        <select name="type" required class="form-select @error('type') is-invalid @enderror">
                                            <option {{ old('type', $getRecord->type) == 0 ? 'selected' : '' }} value="0">Theory</option>
                                            <option {{ old('type', $getRecord->type) == 1 ? 'selected' : '' }} value="1">Practical</option>
                                        </select>
                                        @error('type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Status</label>
                                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                                            <option {{ old('status', $getRecord->status) == 0 ? 'selected' : '' }} value="0">Active</option>
                                            <option {{ old('status', $getRecord->status) == 1 ? 'selected' : '' }} value="1">Inactive</option>
                                        </select>
                                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Fields marked <span class="text-danger">*</span> are required.</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ url('admin/subject/list') }}" class="btn btn-outline-secondary px-4">
                                        <i class="bi bi-x-circle me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-warning text-dark px-4 fw-semibold">
                                        <i class="bi bi-floppy-fill me-2"></i>Update Subject
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