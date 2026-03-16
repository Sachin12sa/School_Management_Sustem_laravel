@extends('layouts.app')
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center py-1">
                <div class="col-sm-6">
                    <h4 class="mb-0 fw-semibold"><i class="bi bi-tags-fill me-2 text-primary"></i>Edit Fee Type</h4>
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
                            <form action="{{ url('admin/fee_type/edit/'.$getRecord->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Fee Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $getRecord->name) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Default Amount (Rs.) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control" step="0.01" min="0" value="{{ old('amount', $getRecord->amount) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Frequency <span class="text-danger">*</span></label>
                                    <select name="frequency" class="form-select" required>
                                        @foreach(['monthly'=>'Monthly','quarterly'=>'Quarterly','yearly'=>'Yearly','one_time'=>'One Time'] as $val => $label)
                                            <option value="{{ $val }}" {{ $getRecord->frequency==$val?'selected':'' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="description" class="form-control" rows="2">{{ old('description', $getRecord->description) }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select" required>
                                        <option value="1" {{ $getRecord->status==1?'selected':'' }}>Active</option>
                                        <option value="0" {{ $getRecord->status==0?'selected':'' }}>Inactive</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Update Fee Type</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection