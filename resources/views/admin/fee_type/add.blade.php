@extends('layouts.app')
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center py-1">
                <div class="col-sm-6">
                    <h4 class="mb-0 fw-semibold"><i class="bi bi-tags-fill me-2 text-primary"></i>Add Fee Type</h4>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <a href="{{ url('admin/fee_type/list') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            @if($errors->any())
                                <div class="alert alert-danger">{{ $errors->first() }}</div>
                            @endif
                            <form action="{{ url('admin/fee_type/add') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Fee Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Tuition Fee" value="{{ old('name') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Default Amount (Rs.) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control" step="0.01" min="0" value="{{ old('amount') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Frequency <span class="text-danger">*</span></label>
                                    <select name="frequency" class="form-select" required>
                                        <option value="">-- Select --</option>
                                        <option value="monthly" {{ old('frequency')=='monthly'?'selected':'' }}>Monthly</option>
                                        <option value="quarterly" {{ old('frequency')=='quarterly'?'selected':'' }}>Quarterly</option>
                                        <option value="yearly" {{ old('frequency')=='yearly'?'selected':'' }}>Yearly</option>
                                        <option value="one_time" {{ old('frequency')=='one_time'?'selected':'' }}>One Time</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="description" class="form-control" rows="2" placeholder="Optional description">{{ old('description') }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select" required>
                                        <option value="1" {{ old('status','1')=='1'?'selected':'' }}>Active</option>
                                        <option value="0" {{ old('status')=='0'?'selected':'' }}>Inactive</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Save Fee Type</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection