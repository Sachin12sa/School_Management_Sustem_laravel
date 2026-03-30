@extends('layouts.app')

@section('style')
    <style>
        .step-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 18px 20px;
        }

        .step-number {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #f59e0b;
            color: #fff;
            font-size: .72rem;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .step-number.done {
            background: #16a34a;
        }

        .step-arrow {
            color: #d1d5db;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            padding: 0 4px;
        }

        .day-pill {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 14px;
            cursor: pointer;
            transition: all .15s;
            background: #fff;
            text-align: center;
            min-width: 80px;
            user-select: none;
        }

        .day-pill:hover {
            border-color: #f59e0b;
            background: #fffbeb;
        }

        .day-pill.active {
            border-color: #f59e0b;
            background: #fef3c7;
            box-shadow: 0 2px 8px rgba(245, 158, 11, .2);
        }

        .day-pill .day-abbr {
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .04em;
            margin-bottom: 2px;
        }

        .day-pill .day-full {
            font-size: .65rem;
            color: #6b7280;
        }

        .day-pill.active .day-full {
            color: #92400e;
        }

        .day-pill.disabled-pill {
            opacity: .4;
            pointer-events: none;
        }

        .tt-section-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }

        .tt-section-card .tt-head {
            background: linear-gradient(135deg, #1e293b, #334155);
            color: #fff;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .tt-head-title {
            font-size: .8rem;
            font-weight: 700;
        }

        .tt-head-meta {
            font-size: .72rem;
            opacity: .7;
            margin-top: 2px;
        }

        .subj-row {
            border-bottom: 1px solid #f3f4f6;
            transition: background .1s;
        }

        .subj-row:last-child {
            border-bottom: none;
        }

        .subj-row:hover {
            background: #fafbff;
        }

        .subj-name-cell {
            padding: 14px 16px;
            font-weight: 600;
            font-size: .85rem;
            color: #111827;
            min-width: 160px;
        }

        .subj-name-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            color: #1d4ed8;
            font-size: .8rem;
            font-weight: 600;
        }

        .tt-placeholder {
            text-align: center;
            padding: 60px 20px;
            color: #9ca3af;
        }

        .tt-placeholder .ph-icon {
            font-size: 3rem;
            opacity: .25;
            margin-bottom: 12px;
        }

        #subject-loading {
            display: none;
        }

        #subject-table {
            display: none;
        }
    </style>
@endsection

@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-2">
                    <div class="col-sm-6">
                        <h4 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-calendar3-week-fill me-2 text-warning"></i>Class Timetable
                        </h4>
                        <span class="text-muted small">Set weekly schedules per class, section and day</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill me-2"></i>{!! session('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- ── Step selectors row ── --}}
                <div class="row g-3 mb-4 align-items-start">

                    {{-- Step 1: Class --}}
                    <div class="col-md-3">
                        <div class="step-card">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="step-number" id="step1-num">1</span>
                                <span class="fw-semibold small text-secondary text-uppercase"
                                    style="letter-spacing:.05em;">Select Class</span>
                            </div>
                            <select id="sel-class" class="form-select">
                                <option value="">— Choose Class —</option>
                                @foreach ($getClass as $class)
                                    <option value="{{ $class->id }}"
                                        {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Arrow --}}
                    <div class="col-auto d-none d-md-flex step-arrow pt-4 mt-2">›</div>

                    {{-- Step 2: Section --}}
                    <div class="col-md-3">
                        <div class="step-card">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="step-number" id="step2-num">2</span>
                                <span class="fw-semibold small text-secondary text-uppercase"
                                    style="letter-spacing:.05em;">Select Section</span>
                            </div>
                            <select id="sel-section" class="form-select" disabled>
                                <option value="">— Select class first —</option>
                                @if (request('class_id') && !empty($getSection))
                                    @foreach ($getSection as $sec)
                                        <option value="{{ $sec->id }}"
                                            {{ request('section_id') == $sec->id ? 'selected' : '' }}>
                                            Section {{ $sec->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    {{-- Arrow --}}
                    <div class="col-auto d-none d-md-flex step-arrow pt-4 mt-2">›</div>

                    {{-- Step 3: Day --}}
                    <div class="col-md-5">
                        <div class="step-card">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <span class="step-number" id="step3-num">3</span>
                                <span class="fw-semibold small text-secondary text-uppercase"
                                    style="letter-spacing:.05em;">Select Day</span>
                            </div>
                            <div class="d-flex flex-wrap gap-2" id="day-pills">
                                @foreach ($getWeeks as $week)
                                    @php
                                        $dayColors = [
                                            'Sunday' => '#dc2626',
                                            'Monday' => '#1d4ed8',
                                            'Tuesday' => '#16a34a',
                                            'Wednesday' => '#0891b2',
                                            'Thursday' => '#d97706',
                                            'Friday' => '#6b7280',
                                            'Saturday' => '#374151',
                                        ];
                                        $dColor = $dayColors[$week->name] ?? '#374151';
                                    @endphp
                                    <div class="day-pill disabled-pill {{ request('week_id') == $week->id ? 'active' : '' }}"
                                        data-week-id="{{ $week->id }}" data-week-name="{{ $week->name }}"
                                        id="day-{{ $week->id }}">
                                        <div class="day-abbr" style="color:{{ $dColor }};">
                                            {{ strtoupper(substr($week->name, 0, 2)) }}
                                        </div>
                                        <div class="day-full">{{ $week->name }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ── Context banner ── --}}
                <div id="context-banner" class="d-flex align-items-center gap-2 mb-3"
                    style="{{ request('class_id') && request('week_id') ? '' : 'display:none!important;' }}">
                    <span class="text-muted small">Timetable for:</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-1 fw-semibold" id="ctx-class">
                        @if (request('class_id'))
                            <i class="bi bi-building me-1"></i>
                            {{ optional($getClass->firstWhere('id', request('class_id')))->name ?? '' }}
                        @endif
                    </span>
                    <span class="badge px-3 py-1 fw-semibold" id="ctx-section"
                        style="background:#ede9fe;color:#7c3aed;{{ !request('section_id') || request('section_id') == '0' ? 'display:none;' : '' }}">
                        @if (request('section_id') && request('section_id') != '0' && !empty($getSection))
                            <i class="bi bi-diagram-3 me-1"></i>
                            Section
                            {{ optional(collect($getSection)->firstWhere('id', request('section_id')))->name ?? '' }}
                        @endif
                    </span>
                    <span class="badge bg-warning bg-opacity-15 text-success px-3 py-1 fw-semibold" id="ctx-day">
                        @if (request('week_id'))
                            <i class="bi bi-calendar-day me-1"></i>
                            {{ optional($getWeeks->firstWhere('id', request('week_id')))->name ?? '' }}
                        @endif
                    </span>
                </div>

                {{-- ── Loading spinner ── --}}
                <div id="subject-loading" class="text-center py-5">
                    <div class="spinner-border text-warning" role="status"></div>
                    <div class="text-muted small mt-2">Loading subjects…</div>
                </div>

                {{-- ── Subject table ── --}}
                <div id="subject-table">
                    <form action="{{ url('admin/class_timetable/list') }}" method="POST" id="tt-form">
                        @csrf
                        <input type="hidden" name="class_id" id="f-class-id" value="{{ request('class_id') }}">
                        <input type="hidden" name="section_id" id="f-section-id" value="{{ request('section_id') }}">
                        <input type="hidden" name="week_id" id="f-week-id" value="{{ request('week_id') }}">

                        <div class="tt-section-card">
                            <div class="tt-head">
                                <div>
                                    <div class="tt-head-title">
                                        <i class="bi bi-calendar3-week me-2"></i>
                                        <span id="tt-head-day">Weekly Schedule</span>
                                    </div>
                                    <div class="tt-head-meta">Fill in times and room for each subject. Leave blank to
                                        mark as no class.</div>
                                </div>
                                <button type="submit" class="btn btn-warning text-dark fw-semibold px-4">
                                    <i class="bi bi-floppy-fill me-2"></i>Save
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table mb-0" id="tt-table">
                                    <thead>
                                        <tr style="background:#f8faff;">
                                            <th
                                                style="padding:10px 16px;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;width:200px;">
                                                Subject</th>
                                            <th
                                                style="padding:10px 16px;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;width:180px;">
                                                Start Time</th>
                                            <th
                                                style="padding:10px 16px;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;width:180px;">
                                                End Time</th>
                                            <th
                                                style="padding:10px 16px;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#6b7280;">
                                                Room / Location</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tt-tbody">
                                        @if (!empty($slotData))
                                            @foreach ($slotData as $i => $row)
                                                <tr class="subj-row">
                                                    <td class="subj-name-cell">
                                                        <input type="hidden"
                                                            name="timetable[{{ $i }}][subject_id]"
                                                            value="{{ $row['subject_id'] }}">
                                                        <div class="subj-name-badge">
                                                            <i class="bi bi-book-fill" style="font-size:.7rem;"></i>
                                                            {{ $row['subject_name'] }}
                                                        </div>
                                                    </td>
                                                    <td style="padding:12px 16px;">
                                                        <div class="input-group input-group-sm" style="max-width:155px;">
                                                            <span class="input-group-text bg-light border-end-0">
                                                                <i class="bi bi-clock"
                                                                    style="font-size:.72rem;color:#6b7280;"></i>
                                                            </span>
                                                            <input type="time"
                                                                name="timetable[{{ $i }}][start_time]"
                                                                value="{{ $row['start_time'] }}"
                                                                class="form-control border-start-0 ps-0">
                                                        </div>
                                                    </td>
                                                    <td style="padding:12px 16px;">
                                                        <div class="input-group input-group-sm" style="max-width:155px;">
                                                            <span class="input-group-text bg-light border-end-0">
                                                                <i class="bi bi-clock-history"
                                                                    style="font-size:.72rem;color:#6b7280;"></i>
                                                            </span>
                                                            <input type="time"
                                                                name="timetable[{{ $i }}][end_time]"
                                                                value="{{ $row['end_time'] }}"
                                                                class="form-control border-start-0 ps-0">
                                                        </div>
                                                    </td>
                                                    <td style="padding:12px 16px;">
                                                        <div class="input-group input-group-sm" style="max-width:200px;">
                                                            <span class="input-group-text bg-light border-end-0">
                                                                <i class="bi bi-door-open"
                                                                    style="font-size:.72rem;color:#6b7280;"></i>
                                                            </span>
                                                            <input type="text"
                                                                name="timetable[{{ $i }}][room_number]"
                                                                value="{{ $row['room_number'] }}"
                                                                placeholder="e.g. Room 101"
                                                                class="form-control border-start-0 ps-0">
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- ── Placeholder ── --}}
                <div id="tt-placeholder" class="tt-section-card" style="{{ !empty($slotData) ? 'display:none;' : '' }}">
                    <div class="tt-placeholder">
                        <div class="ph-icon"><i class="bi bi-calendar3-week"></i></div>
                        <div class="fw-semibold text-dark mb-1" id="ph-msg">Select a class to begin</div>
                        <div class="small text-muted" id="ph-sub">Follow the steps above: Class → Section → Day</div>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        var selectedClass = '{{ request('class_id', '') }}';
        var selectedSection = '{{ request('section_id', '') }}';
        var selectedWeek = '{{ request('week_id', '') }}';
        var selectedWeekName = '';
        var classHasSection = true; // will be set after loadSections response

        $(document).ready(function() {

            // ── Restore state on page reload after save ──────────────────
            if (selectedClass) {
                loadSections(selectedClass, selectedSection);
            }

            if (selectedClass && selectedSection && selectedWeek) {
                var $pill = $('[data-week-id="' + selectedWeek + '"]');
                selectedWeekName = $pill.data('week-name') || 'Selected Day';
                showTable(true);
                updateContextBanner();
            } else {
                updatePlaceholderMsg();
            }

            markStepDone();
        });

        // ── STEP 1: Class selected ────────────────────────────────────────
        $('#sel-class').on('change', function() {
            selectedClass = $(this).val();
            selectedSection = '';
            selectedWeek = '';

            resetTable();
            disableDayPills();
            updatePlaceholderMsg();

            var $sec = $('#sel-section');

            if (!selectedClass) {
                $sec.html('<option value="">— Select class first —</option>').prop('disabled', true);
                markStepDone();
                return;
            }

            $sec.html('<option value="">Loading…</option>').prop('disabled', true);
            loadSections(selectedClass, null);
            markStepDone();
        });

        // ── Load sections via AJAX ────────────────────────────────────────
        function loadSections(classId, preselect) {
            $.post('{{ url('admin/class_timetable/get_sections') }}', {
                _token: '{{ csrf_token() }}',
                class_id: classId
            }, function(res) {
                var $sec = $('#sel-section');

                // ── Class has NO sections → skip section step ──
                if (!res.sections || res.sections.length === 0) {
                    classHasSection = false;
                    selectedSection = '0';

                    $sec.html('<option value="0">— No Section —</option>').prop('disabled', true);

                    // Mark step 2 done automatically and enable day pills
                    markStepDone();
                    enableDayPills();
                    updatePlaceholderMsg();
                    return;
                }

                // ── Class HAS sections ──
                classHasSection = true;
                $sec.prop('disabled', false).html(res.section_html);

                if (preselect) {
                    $sec.val(preselect);
                    if ($sec.val()) {
                        selectedSection = preselect;
                        enableDayPills();
                        markStepDone();
                    }
                }

                updatePlaceholderMsg();
            });
        }

        // ── STEP 2: Section manually selected ────────────────────────────
        $('#sel-section').on('change', function() {
            selectedSection = $(this).val();
            selectedWeek = '';

            resetTable();
            $('.day-pill').removeClass('active');
            updatePlaceholderMsg();
            markStepDone();

            if (selectedSection) {
                enableDayPills(); // ← KEY FIX: enable pills when section chosen
            } else {
                disableDayPills();
            }
        });

        // ── STEP 3: Day pill clicked ──────────────────────────────────────
        $(document).on('click', '.day-pill', function() {
            if (!selectedClass || !selectedSection) return;

            $('.day-pill').removeClass('active');
            $(this).addClass('active');

            selectedWeek = $(this).data('week-id');
            selectedWeekName = $(this).data('week-name');

            loadSlot();
            markStepDone();
        });

        // ── Load slot via AJAX ────────────────────────────────────────────
        function loadSlot() {
            $('#tt-placeholder').hide();
            $('#subject-table').hide();
            $('#subject-loading').show();

            $.post('{{ url('admin/class_timetable/get_slot') }}', {
                _token: '{{ csrf_token() }}',
                class_id: selectedClass,
                section_id: selectedSection, // will be '0' for no-section classes
                week_id: selectedWeek,
            }, function(res) {
                $('#subject-loading').hide();

                if (!res.success || !res.rows || res.rows.length === 0) {
                    $('#ph-msg').text('No subjects assigned to this class yet.');
                    $('#ph-sub').text('Please assign subjects to this class first.');
                    $('#tt-placeholder').show();
                    return;
                }

                buildTable(res.rows);
                updateContextBanner();
                showTable(false);
            }).fail(function() {
                $('#subject-loading').hide();
                $('#ph-msg').text('Failed to load subjects. Please try again.');
                $('#tt-placeholder').show();
            });
        }

        // ── Build table rows from JSON ────────────────────────────────────
        function buildTable(rows) {
            var html = '';
            rows.forEach(function(row, i) {
                html +=
                    '<tr class="subj-row">' +
                    '<td class="subj-name-cell">' +
                    '<input type="hidden" name="timetable[' + i + '][subject_id]" value="' + row.subject_id + '">' +
                    '<div class="subj-name-badge"><i class="bi bi-book-fill" style="font-size:.7rem;"></i> ' +
                    escHtml(row.subject_name) + '</div>' +
                    '</td>' +
                    '<td style="padding:12px 16px;">' +
                    '<div class="input-group input-group-sm" style="max-width:155px;">' +
                    '<span class="input-group-text bg-light border-end-0"><i class="bi bi-clock" style="font-size:.72rem;color:#6b7280;"></i></span>' +
                    '<input type="time" name="timetable[' + i + '][start_time]" value="' + (row.start_time || '') +
                    '" class="form-control border-start-0 ps-0">' +
                    '</div>' +
                    '</td>' +
                    '<td style="padding:12px 16px;">' +
                    '<div class="input-group input-group-sm" style="max-width:155px;">' +
                    '<span class="input-group-text bg-light border-end-0"><i class="bi bi-clock-history" style="font-size:.72rem;color:#6b7280;"></i></span>' +
                    '<input type="time" name="timetable[' + i + '][end_time]" value="' + (row.end_time || '') +
                    '" class="form-control border-start-0 ps-0">' +
                    '</div>' +
                    '</td>' +
                    '<td style="padding:12px 16px;">' +
                    '<div class="input-group input-group-sm" style="max-width:200px;">' +
                    '<span class="input-group-text bg-light border-end-0"><i class="bi bi-door-open" style="font-size:.72rem;color:#6b7280;"></i></span>' +
                    '<input type="text" name="timetable[' + i + '][room_number]" value="' + escHtml(row
                        .room_number || '') +
                    '" placeholder="e.g. Room 101" class="form-control border-start-0 ps-0">' +
                    '</div>' +
                    '</td>' +
                    '</tr>';
            });

            $('#tt-tbody').html(html);
            $('#f-class-id').val(selectedClass);
            $('#f-section-id').val(selectedSection);
            $('#f-week-id').val(selectedWeek);
            $('#tt-head-day').text(selectedWeekName + ' — Schedule');
        }

        // ── Show / hide helpers ───────────────────────────────────────────
        function showTable(alreadyHasData) {
            $('#subject-loading').hide();
            $('#tt-placeholder').hide();
            $('#subject-table').show();
            if (alreadyHasData) {
                $('#tt-head-day').text(selectedWeekName + ' — Schedule');
            }
        }

        function resetTable() {
            $('#subject-table').hide();
            $('#subject-loading').hide();
            $('#tt-placeholder').show();
            $('#tt-tbody').html('');
        }

        function enableDayPills() {
            $('.day-pill').removeClass('disabled-pill');
        }

        function disableDayPills() {
            $('.day-pill').addClass('disabled-pill').removeClass('active');
        }

        // ── Context banner ────────────────────────────────────────────────
        function updateContextBanner() {
            var className = $('#sel-class option:selected').text().trim();
            var sectionName = $('#sel-section option:selected').text().trim();

            $('#ctx-class').html('<i class="bi bi-building me-1"></i>' + className);

            if (selectedSection === '0' || !classHasSection) {
                $('#ctx-section').hide();
            } else {
                $('#ctx-section').show().html('<i class="bi bi-diagram-3 me-1"></i>' + sectionName);
            }

            $('#ctx-day').html('<i class="bi bi-calendar-day me-1"></i>' + selectedWeekName);
            $('#context-banner').show();
        }

        function updatePlaceholderMsg() {
            $('#context-banner').hide();

            if (!selectedClass) {
                $('#ph-msg').text('Select a class to begin');
                $('#ph-sub').text('Follow the steps above: Class → Section → Day');
                return;
            }

            if (!classHasSection && selectedClass) {
                // No section needed — just waiting for day
                if (!selectedWeek) {
                    $('#ph-msg').text('Now click a day above to load subjects');
                    $('#ph-sub').text('This class has no sections — just pick a day');
                }
                return;
            }

            if (!selectedSection) {
                $('#ph-msg').text('Now select a section');
                $('#ph-sub').text('Follow the steps above: Class → Section → Day');
                return;
            }

            if (!selectedWeek) {
                $('#ph-msg').text('Now click a day above to load subjects');
                $('#ph-sub').text('All set — just pick which day to edit');
            }
        }

        // ── Step indicators ───────────────────────────────────────────────
        function markStepDone() {
            $('#step1-num').toggleClass('done', !!selectedClass);
            // Step 2 is "done" if section selected OR class has no sections
            $('#step2-num').toggleClass('done', !!selectedSection);
            $('#step3-num').toggleClass('done', !!selectedWeek);
        }

        function escHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }
    </script>
@endsection
