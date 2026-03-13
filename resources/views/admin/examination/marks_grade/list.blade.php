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
                            <h4 class="mb-0 fw-semibold text-dark">Marks Grades</h4>
                            <span class="text-muted small">Grade scale definitions</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-end">
                    <a href="{{ url('admin/examination/marks_grade/add') }}" class="btn btn-success px-4 shadow-sm fw-semibold">
                        <i class="bi bi-plus-circle-fill me-2"></i>Add New Grade
                    </a>
                </div>
            </div>

        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                    {{-- Fixed: card title said "Exam List" instead of Marks Grades --}}
                    <h6 class="mb-0 fw-semibold">
                        <i class="bi bi-award-fill me-2 text-success"></i>Grade Scale
                    </h6>
                    <span class="badge bg-success bg-opacity-10 text-success">
                        {{ $getRecord->count() }} {{ Str::plural('grade', $getRecord->count()) }}
                    </span>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr class="table-light text-uppercase text-secondary" style="font-size:.72rem;letter-spacing:.05em;">
                                    <th class="ps-4" width="60">#</th>
                                    <th>Grade Name</th>
                                    <th>Percent From</th>
                                    {{-- Fixed: typo "Percent T0" → "Percent To" --}}
                                    <th>Percent To</th>
                                    <th>Range</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th class="text-center pe-4" width="160">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($getRecord as $key => $value)
                                    <tr>
                                        {{-- Fixed: was using $value->id (actual DB id) instead of loop index --}}
                                        <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>

                                        <td>
                                            <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2"
                                                  style="font-size:.85rem;">
                                                {{ $value->name }}
                                            </span>
                                        </td>

                                        <td>
                                            <span class="fw-semibold text-dark">{{ $value->percent_from }}</span>
                                            <span class="text-muted small">%</span>
                                        </td>

                                        <td>
                                            <span class="fw-semibold text-dark">{{ $value->percent_to }}</span>
                                            <span class="text-muted small">%</span>
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-fill" style="height:6px;min-width:80px;max-width:120px;">
                                                    <div class="progress-bar bg-success"
                                                         style="width:{{ $value->percent_to }}%"></div>
                                                </div>
                                                <span class="text-muted small">{{ $value->percent_from }}–{{ $value->percent_to }}%</span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-person-circle text-muted"></i>
                                                <span class="small">{{ $value->created_name }} {{ $value->created_last_name }}</span>
                                            </div>
                                        </td>

                                        <td>
                                            <div class="small text-dark">{{ \Carbon\Carbon::parse($value->created_at)->format('d M Y') }}</div>
                                            <div class="text-muted" style="font-size:.72rem;">{{ \Carbon\Carbon::parse($value->created_at)->format('h:i A') }}</div>
                                        </td>

                                        <td class="text-center pe-4">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <a href="{{ url('admin/examination/marks_grade/edit/' . $value->id) }}"
                                                   class="btn btn-sm btn-outline-primary px-3">
                                                    <i class="bi bi-pencil-fill me-1"></i>Edit
                                                </a>
                                                <a href="{{ url('admin/examination/marks_grade/delete/' . $value->id) }}"
                                                   class="btn btn-sm btn-outline-danger px-2"
                                                   onclick="return confirm('Delete this grade?')">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <i class="bi bi-award d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                                            {{-- Fixed: empty state said "No admins found" --}}
                                            <div class="fw-semibold small text-muted">No grades defined yet</div>
                                            <div class="text-muted" style="font-size:.78rem;">Add a grade scale to get started.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</main>
@endsection