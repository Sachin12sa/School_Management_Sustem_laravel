@extends('layouts.app')

@section('content')
<main class="app-main">

    {{-- ── Page Header ─────────────────────────────────────────────────── --}}
    <div class="app-content-header">
        <div class="container-fluid">

            <div class="row align-items-center mb-4">
                <div class="col-sm-6">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:46px;height:46px;font-size:1.4rem;">
                            <i class="bi bi-calendar3-week-fill"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-semibold text-dark">Class Timetable</h4>
                            <span class="text-muted small">
                                <i class="bi bi-clock me-1"></i>
                                Select a class and subject to view or edit the schedule
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Flash Messages --}}
            @include('message')

            {{-- ── Filter Card ──────────────────────────────────────────────── --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                    <i class="bi bi-funnel-fill text-warning"></i>
                    <h6 class="mb-0 fw-semibold">Select Class & Subject</h6>
                </div>
                <div class="card-body bg-light bg-opacity-50">
                    <form method="get" action="" id="filterForm">
                        <div class="row g-3 align-items-end">

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-building me-1"></i>Class Name
                                </label>
                                <select name="class_id" class="form-select getClass">
                                    <option value="">— Select Class —</option>
                                    @foreach($getClass as $class)
                                        <option value="{{ $class->id }}"
                                            {{ Request::get('class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-secondary">
                                    <i class="bi bi-journal-bookmark me-1"></i>Subject Name
                                </label>
                                <select name="subject_id" class="form-select getSubject">
                                    @if(!empty($getSubject) && $getSubject->count() > 0)
                                        <option value="">— Select Subject —</option>
                                        @foreach($getSubject as $subject)
                                            <option value="{{ $subject->subject_id }}"
                                                {{ Request::get('subject_id') == $subject->subject_id ? 'selected' : '' }}>
                                                {{ $subject->subject_name }}
                                            </option>
                                        @endforeach
                                    @elseif(!empty(Request::get('class_id')))
                                        <option value="">No subjects assigned to this class</option>
                                    @else
                                        <option value="">Select a class first</option>
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-warning text-dark flex-fill">
                                    <i class="bi bi-search me-1"></i>Load Timetable
                                </button>
                                <a href="{{ url('admin/class_timetable/list') }}" class="btn btn-outline-secondary flex-fill">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- ── Timetable Form (only shown when class + subject selected) ──────── --}}
    <div class="app-content">
        <div class="container-fluid">

            @if(!empty(Request::get('class_id')) && !empty(Request::get('subject_id')))

                {{-- Context Pills --}}
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="text-muted small">Editing timetable for:</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 fw-semibold">
                        <i class="bi bi-building me-1"></i>
                        {{ optional($getClass->firstWhere('id', Request::get('class_id')))->name ?? 'Selected Class' }}
                    </span>
                    <span class="badge bg-info bg-opacity-10 text-info px-3 py-1 fw-semibold">
                        <i class="bi bi-journal-bookmark me-1"></i>
                        {{ optional($getSubject?->firstWhere('subject_id', Request::get('subject_id')))->subject_name ?? 'Selected Subject' }}
                    </span>
                </div>

                <form action="{{ url('admin/class_timetable/add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ Request::get('class_id') }}">
                    <input type="hidden" name="subject_id" value="{{ Request::get('subject_id') }}">

                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="bi bi-calendar3-week me-2 text-warning"></i>Weekly Schedule
                            </h6>
                            <span class="badge bg-warning bg-opacity-10 text-warning small">
                                <i class="bi bi-pencil me-1"></i>Editable
                            </span>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead>
                                        <tr class="table-light text-uppercase text-secondary" style="font-size:.72rem;letter-spacing:.05em;">
                                            <th class="ps-4" style="width:140px;">Day</th>
                                            <th style="width:200px;">Start Time</th>
                                            <th style="width:200px;">End Time</th>
                                            <th>Room Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach($week as $value)
                                            <tr>
                                                <input type="hidden" name="timetable[{{ $i }}][week_id]" value="{{ $value['id'] }}">

                                                {{-- Day Label --}}
                                                <td class="ps-4">
                                                    @php
                                                        $dayColors = [
                                                            'Sunday'    => 'danger',
                                                            'Monday'    => 'primary',
                                                            'Tuesday'   => 'success',
                                                            'Wednesday' => 'info',
                                                            'Thursday'  => 'warning',
                                                            'Friday'    => 'secondary',
                                                            'Saturday'  => 'dark',
                                                        ];
                                                        $color = $dayColors[$value['week_name']] ?? 'primary';
                                                    @endphp
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="rounded-2 bg-{{ $color }} bg-opacity-10 text-{{ $color }} d-flex align-items-center justify-content-center flex-shrink-0"
                                                             style="width:32px;height:32px;font-size:.75rem;font-weight:700;">
                                                            {{ strtoupper(substr($value['week_name'], 0, 2)) }}
                                                        </div>
                                                        <span class="fw-semibold small text-dark">{{ $value['week_name'] }}</span>
                                                    </div>
                                                </td>

                                                {{-- Start Time --}}
                                                <td>
                                                    <div class="input-group input-group-sm" style="max-width:160px;">
                                                        <span class="input-group-text bg-light border-end-0">
                                                            <i class="bi bi-clock text-muted" style="font-size:.75rem;"></i>
                                                        </span>
                                                        <input type="time"
                                                               name="timetable[{{ $i }}][start_time]"
                                                               value="{{ $value['start_time'] }}"
                                                               class="form-control border-start-0 ps-0">
                                                    </div>
                                                </td>

                                                {{-- End Time --}}
                                                <td>
                                                    <div class="input-group input-group-sm" style="max-width:160px;">
                                                        <span class="input-group-text bg-light border-end-0">
                                                            <i class="bi bi-clock-history text-muted" style="font-size:.75rem;"></i>
                                                        </span>
                                                        <input type="time"
                                                               name="timetable[{{ $i }}][end_time]"
                                                               value="{{ $value['end_time'] }}"
                                                               class="form-control border-start-0 ps-0">
                                                    </div>
                                                </td>

                                                {{-- Room Number --}}
                                                <td>
                                                    <div class="input-group input-group-sm" style="max-width:220px;">
                                                        <span class="input-group-text bg-light border-end-0">
                                                            <i class="bi bi-door-open text-muted" style="font-size:.75rem;"></i>
                                                        </span>
                                                        <input type="text"
                                                               name="timetable[{{ $i }}][room_number]"
                                                               value="{{ trim($value['room_number']) }}"
                                                               placeholder="e.g. Room 101"
                                                               class="form-control border-start-0 ps-0">
                                                    </div>
                                                </td>
                                            </tr>
                                            @php $i++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer bg-white d-flex align-items-center justify-content-between py-3">
                            <span class="text-muted small">
                                <i class="bi bi-info-circle me-1"></i>
                                Leave fields empty to mark a day as no class.
                            </span>
                            <button type="submit" class="btn btn-warning text-dark px-4 fw-semibold">
                                <i class="bi bi-floppy-fill me-2"></i>Save Timetable
                            </button>
                        </div>
                    </div>

                </form>

            @else

                {{-- Placeholder when nothing selected --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-6" style="padding:4rem;">
                        <i class="bi bi-calendar3-week d-block mb-3 text-warning" style="font-size:3.5rem;opacity:.4;"></i>
                        <h6 class="fw-semibold text-dark mb-1">No Timetable Selected</h6>
                        <p class="text-muted small mb-0">
                            Use the filter above to select a <strong>Class</strong> and <strong>Subject</strong> to view or edit their weekly schedule.
                        </p>
                    </div>
                </div>

            @endif

        </div>
    </div>

</main>
@endsection

@section('script')
<script>
$(document).ready(function () {

    // Dynamic subject loading on class change
    $('.getClass').on('change', function () {
        var class_id = $(this).val();
        var $subjectSelect = $('.getSubject');

        if (class_id !== '') {
            $subjectSelect.html('<option value="">Loading…</option>').prop('disabled', true);

            $.ajax({
                url: "{{ url('admin/class_timetable/get_subject') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    class_id: class_id,
                },
                dataType: 'json',
                success: function (response) {
                    $subjectSelect.prop('disabled', false).html(response.html);
                },
                error: function () {
                    $subjectSelect.prop('disabled', false)
                                  .html('<option value="">Failed to load subjects</option>');
                }
            });
        } else {
            $subjectSelect.prop('disabled', false)
                          .html('<option value="">Select a class first</option>');
        }
    });

});
</script>
@endsection