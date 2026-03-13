@extends('layouts.app')
@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">

            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;background:rgba(13,110,253,.1);color:#0d6efd;">
                            <i class="bi bi-calendar3-week-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Exam Schedule</h4>
                            <span class="text-muted small">Select an exam &amp; class to view or edit the schedule</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-primary"></i>
                    <h6 class="mb-0 fw-semibold">Search Exam Schedule</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="GET" action="{{ url('admin/examination/exam_schedule') }}">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-mortarboard me-1"></i>Exam Name <span class="text-danger">*</span>
                                </label>
                                <select name="exam_id" required class="form-select">
                                    <option value="">— Select Exam —</option>
                                    @foreach($getExam as $exam)
                                        <option {{ Request::get('exam_id') == $exam->id ? 'selected' : '' }}
                                                value="{{ $exam->id }}">{{ $exam->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-building me-1"></i>Class <span class="text-danger">*</span>
                                </label>
                                <select name="class_id" required class="form-select">
                                    <option value="">— Select Class —</option>
                                    @foreach($getClass as $class)
                                        <option {{ Request::get('class_id') == $class->id ? 'selected' : '' }}
                                                value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill fw-semibold">
                                    <i class="bi bi-search me-1"></i>Search
                                </button>
                                <a href="{{ url('admin/examination/exam_schedule') }}" class="btn btn-outline-secondary flex-fill">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">
            @include('message')

            @if(!empty($getRecord))
            <form action="{{ url('admin/examination/exam_schedule_insert') }}" method="post">
                @csrf
                <input type="hidden" name="exam_id"  value="{{ Request::get('exam_id') }}">
                <input type="hidden" name="class_id" value="{{ Request::get('class_id') }}">

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="bi bi-calendar3-week-fill me-2 text-primary"></i>Schedule Entries
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            @if(Request::get('exam_id'))
                                <span class="badge bg-primary bg-opacity-10 text-primary px-3">
                                    <i class="bi bi-mortarboard me-1"></i>
                                    {{ $getExam->firstWhere('id', Request::get('exam_id'))->name ?? '' }}
                                </span>
                            @endif
                            @if(Request::get('class_id'))
                                <span class="badge bg-info bg-opacity-10 text-info px-3">
                                    <i class="bi bi-building me-1"></i>
                                    {{ $getClass->firstWhere('id', Request::get('class_id'))->name ?? '' }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr class="table-light text-uppercase text-secondary" style="font-size:.72rem;letter-spacing:.05em;">
                                        <th class="ps-4">Subject</th>
                                        <th>Exam Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Room No.</th>
                                        <th>Full Mark</th>
                                        <th>Pass Mark</th>
                                        <th class="text-center pe-4" width="80">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $i = 1; @endphp
                                    @forelse($getRecord as $key => $value)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-2 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                                         style="width:28px;height:28px;font-size:.75rem;">
                                                        <i class="bi bi-journal-text"></i>
                                                    </div>
                                                    <span class="fw-semibold small text-dark">{{ $value['subject_name'] }}</span>
                                                </div>
                                                <input type="hidden" name="schedule[{{ $i }}][subject_id]" value="{{ $value['subject_id'] }}">
                                            </td>
                                            <td>
                                                <input type="date" id="date_{{ $i }}"
                                                       class="form-control form-control-sm"
                                                       name="schedule[{{ $i }}][exam_date]"
                                                       value="{{ $value['exam_date'] }}"
                                                       style="min-width:140px;">
                                            </td>
                                            <td>
                                                <input type="time"
                                                       class="form-control form-control-sm"
                                                       name="schedule[{{ $i }}][start_time]"
                                                       value="{{ $value['start_time'] }}"
                                                       style="min-width:110px;">
                                            </td>
                                            <td>
                                                <input type="time"
                                                       class="form-control form-control-sm"
                                                       name="schedule[{{ $i }}][end_time]"
                                                       value="{{ $value['end_time'] }}"
                                                       style="min-width:110px;">
                                            </td>
                                            <td>
                                                <input type="text"
                                                       class="form-control form-control-sm"
                                                       name="schedule[{{ $i }}][room_number]"
                                                       value="{{ $value['room_number'] }}"
                                                       placeholder="e.g. 101"
                                                       style="min-width:90px;">
                                            </td>
                                            <td>
                                                <input type="number"
                                                       class="form-control form-control-sm"
                                                       name="schedule[{{ $i }}][full_mark]"
                                                       value="{{ $value['full_mark'] }}"
                                                       min="0" placeholder="100"
                                                       style="min-width:80px;">
                                            </td>
                                            <td>
                                                <input type="number"
                                                       class="form-control form-control-sm"
                                                       name="schedule[{{ $i }}][passing_mark]"
                                                       value="{{ $value['passing_mark'] }}"
                                                       min="0" placeholder="40"
                                                       style="min-width:80px;">
                                            </td>
                                            <td class="text-center pe-4">
                                                @if(!empty($value['id']))
                                                <a href="{{ url('admin/examination/exam_schedule/delete/' . $value['id']) }}"
                                                   class="btn btn-sm btn-outline-danger px-2"
                                                   onclick="return confirm('Delete this schedule entry?')">
                                                    <i class="bi bi-trash3-fill"></i>
                                                </a>
                                                @else
                                                <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @php $i++; @endphp
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <i class="bi bi-calendar-x d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                                                <div class="fw-semibold small text-muted">No subjects found for this class</div>
                                                <div class="text-muted" style="font-size:.78rem;">Make sure subjects are assigned to the selected class.</div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if(count($getRecord) > 0)
                    <div class="card-footer bg-white border-top d-flex justify-content-end py-3">
                        <button type="submit" class="btn btn-primary px-5 fw-semibold">
                            <i class="bi bi-floppy-fill me-2"></i>Save Schedule
                        </button>
                    </div>
                    @endif
                </div>
            </form>
            @endif

        </div>
    </div>

</main>
@endsection