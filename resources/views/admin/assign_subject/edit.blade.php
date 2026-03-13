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
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Edit Assigned Subjects</h4>
                            <span class="text-muted small">
                                <i class="bi bi-arrow-left me-1"></i>
                                <a href="{{ url('admin/assign_subject/list') }}" class="text-muted text-decoration-none">Back to Assign Subject List</a>
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
                <div class="col-md-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                            <i class="bi bi-pencil-fill text-warning"></i>
                            <h6 class="mb-0 fw-semibold">Edit All Subject Assignments</h6>
                        </div>

                        <form method="post" action="">
                            @csrf
                            <div class="card-body">
                                <div class="row g-4">

                                    {{-- Class --}}
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">
                                            <i class="bi bi-building me-1"></i>Class <span class="text-danger">*</span>
                                        </label>
                                        <select name="class_id" required
                                                class="form-select @error('class_id') is-invalid @enderror">
                                            <option value="">— Select Class —</option>
                                            @foreach($getClass as $class)
                                                <option {{ $getRecord->class_id == $class->id ? 'selected' : '' }}
                                                        value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('class_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- Subjects --}}
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">
                                            <i class="bi bi-journal-text me-1"></i>Select Subjects <span class="text-danger">*</span>
                                        </label>
                                        <div class="border rounded-3 p-3 bg-light" style="max-height:280px;overflow-y:auto;">
                                            @forelse($getSubject as $subject)
                                                <div class="form-check py-1 border-bottom border-light-subtle">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="subject_id[]"
                                                           value="{{ $subject->id }}"
                                                           id="subject_{{ $subject->id }}"
                                                           {{ in_array($subject->id, $assignedSubjectIds) ? 'checked' : '' }}>
                                                    <label class="form-check-label small fw-semibold text-dark" for="subject_{{ $subject->id }}">
                                                        {{ $subject->name }}
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary ms-1" style="font-size:.65rem;">
                                                            {{ $subject->type == 0 ? 'Theory' : 'Practical' }}
                                                        </span>
                                                    </label>
                                                </div>
                                            @empty
                                                <p class="text-muted small mb-0">No subjects available.</p>
                                            @endforelse
                                        </div>
                                        @error('subject_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold small text-secondary">Status</label>
                                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                                            <option {{ $getRecord->status == 0 ? 'selected' : '' }} value="0">Active</option>
                                            <option {{ $getRecord->status == 1 ? 'selected' : '' }} value="1">Inactive</option>
                                        </select>
                                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Select at least one subject.</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ url('admin/assign_subject/list') }}" class="btn btn-outline-secondary px-4">
                                        <i class="bi bi-x-circle me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-warning text-dark px-4 fw-semibold">
                                        <i class="bi bi-floppy-fill me-2"></i>Update Assignments
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