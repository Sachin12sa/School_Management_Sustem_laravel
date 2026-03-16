@extends('layouts.app')
@section('content')
<main class="app-main">
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center py-1">
                <div class="col-sm-6">
                    <h4 class="mb-0 fw-semibold"><i class="bi bi-cash-coin me-2 text-primary"></i>Edit Student Fee</h4>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <a href="{{ url('admin/fee/list') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Back</a>
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
                            <form action="{{ url('admin/fee/edit/'.$getRecord->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            Class <span class="text-danger">*</span>
                                        </label>

                                        <select name="class_id" class="form-select" required>
                                            <option value="">-- Select Class --</option>

                                            @foreach($getClasses as $s)
                                                <option value="{{ $s->id }}"
                                                    {{ old('class_id', $getRecord->class_id ?? '') == $s->id ? 'selected' : '' }}>
                                                    {{ $s->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Student <span class="text-danger">*</span>
                                    </label>

                                    <select name="student_id" class="form-select" required>
                                        <option value="">-- Select Student --</option>

                                        @foreach($getStudents as $s)
                                        <option value="{{ $s->id }}"
                                            {{ old('student_id', $getRecord->student_id ?? '') == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }} {{ $s->last_name }} 
                                            ({{ $s->admission_number }} - {{ $s->class_name }})
                                        </option>
                                    @endforeach

                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Fee Type <span class="text-danger">*</span></label>
                                    <select name="fee_type_id" class="form-select" required>
                                        <option value="">-- Select Fee Type --</option>
                                        @foreach($getFeeTypes as $ft)
                                            <option value="{{ $ft->id }}" {{ $getRecord->fee_type_id==$ft->id?'selected':'' }}>
                                                {{ $ft->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Amount (Rs.) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control" step="0.01" min="0"
                                           value="{{ old('amount', $getRecord->amount) }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Due Date <span class="text-danger">*</span></label>
                                    <input type="date" name="due_date" class="form-control"
                                           value="{{ old('due_date', $getRecord->due_date) }}" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Remarks</label>
                                    <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $getRecord->remarks) }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Update Fee Record</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
