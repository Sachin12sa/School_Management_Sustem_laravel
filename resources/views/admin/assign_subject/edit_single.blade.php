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
                            <h4 class="mb-0 fw-semibold text-dark">Edit Single Subject Assignment</h4>
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
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                            <i class="bi bi-pencil-fill text-warning"></i>
                            <h6 class="mb-0 fw-semibold">Edit Single Assignment</h6>
                        </div>

                        <form method="post" action="">
                            @csrf
                            <div class="card-body">
                                <div class="row g-3">

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

                                    {{-- Subject --}}
                                    <div class="col-12">
                                        <label class="form-label fw-semibold small text-secondary">
                                            <i class="bi bi-journal-text me-1"></i>Subject <span class="text-danger">*</span>
                                        </label>
                                        <select name="subject_id" required
                                                class="form-select @error('subject_id') is-invalid @enderror">
                                            <option value="">— Select Subject —</option>
                                            @foreach($getSubject as $subject)
                                                <option {{ $getRecord->subject_id == $subject->id ? 'selected' : '' }}
                                                        value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('subject_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-12">
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
                                <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Fields marked <span class="text-danger">*</span> are required.</span>
                                <div class="d-flex gap-2">
                                    <a href="{{ url('admin/assign_subject/list') }}" class="btn btn-outline-secondary px-4">
                                        <i class="bi bi-x-circle me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-warning text-dark px-4 fw-semibold">
                                        <i class="bi bi-floppy-fill me-2"></i>Update Assignment
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