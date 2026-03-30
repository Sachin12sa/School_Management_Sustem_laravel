@extends('layouts.app')
@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;">
                                <i class="bi bi-building-add"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">Add New Class</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    <a href="{{ url('admin/class/list') }}" class="text-muted text-decoration-none">Back to
                                        Class List</a>
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
                                <i class="bi bi-building text-primary"></i>
                                <h6 class="mb-0 fw-semibold">Class Details</h6>
                            </div>

                            <form method="post" action="">
                                @csrf
                                <div class="card-body">
                                    <div class="row g-3">

                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-secondary">
                                                Class Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name" value="{{ old('name') }}" required
                                                placeholder="e.g. Grade 10, Class A…"
                                                class="form-control @error('name') is-invalid @enderror">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label fw-semibold small text-secondary">Status</label>
                                            <select name="status"
                                                class="form-select @error('status') is-invalid @enderror">
                                                <option {{ old('status') === '0' ? 'selected' : '' }} value="0">Active
                                                </option>
                                                <option {{ old('status') === '1' ? 'selected' : '' }} value="1">
                                                    Inactive</option>
                                            </select>
                                            @error('status')
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
                                        <a href="{{ url('admin/class/list') }}" class="btn btn-outline-secondary px-4">
                                            <i class="bi bi-x-circle me-1"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary px-4 fw-semibold">
                                            <i class="bi bi-plus-circle-fill me-2"></i>Create Class
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

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var dateInputs = document.querySelectorAll('.nepali-date');
            dateInputs.forEach(function(input) {
                input.NepaliDatePicker({
                    ndpYear: true,
                    ndpMonth: true,
                    readOnlyInput: true
                });
            });
        });
    </script>
@endsection
