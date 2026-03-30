@extends('layouts.app')
@section('content')
    <main class="app-main">

        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row align-items-center mb-4">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-info bg-opacity-10 text-info d-flex align-items-center
                             justify-content-center flex-shrink-0"
                                style="width:46px;height:46px;font-size:1.4rem;">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold text-dark">My Timetable</h4>
                                <span class="text-muted small">Weekly class schedule</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                @include('message')

                {{-- Day tabs --}}
                <ul class="nav nav-pills gap-2 mb-4" id="dayTabs">
                    @foreach ($getRecord as $i => $day)
                        <li class="nav-item">
                            <button
                                class="nav-link d-flex align-items-center gap-1
                                       {{ $day['is_today'] ? 'active' : '' }}"
                                data-day="{{ $i }}" onclick="showDay({{ $i }})">
                                @if ($day['is_today'])
                                    <span class="badge bg-warning text-dark me-1" style="font-size:.6rem;">TODAY</span>
                                @endif
                                {{ $day['week_name'] }}
                                @if (count($day['slots']) > 0)
                                    <span class="badge bg-white bg-opacity-25 ms-1"
                                        style="font-size:.65rem;">{{ count($day['slots']) }}</span>
                                @endif
                            </button>
                        </li>
                    @endforeach
                </ul>

                {{-- Day panels --}}
                @foreach ($getRecord as $i => $day)
                    <div class="day-panel" id="panel-{{ $i }}"
                        style="{{ $day['is_today'] ? '' : 'display:none;' }}">

                        @if ($day['is_today'])
                            <div class="alert alert-warning border-0 d-flex align-items-center gap-2 mb-3 py-2">
                                <i class="bi bi-sun-fill"></i>
                                <span class="small fw-semibold">Today is {{ $day['week_name'] }}</span>
                            </div>
                        @endif

                        @if (count($day['slots']) > 0)
                            <div class="row g-3">
                                @foreach ($day['slots'] as $slot)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start justify-content-between">
                                                    <div>
                                                        <div class="fw-semibold text-dark mb-1">
                                                            {{ $slot['subject_name'] }}
                                                        </div>
                                                        <div class="text-success small fw-semibold">
                                                            <i class="bi bi-clock me-1"></i>
                                                            {{ date('h:i A', strtotime($slot['start_time'])) }}
                                                            –
                                                            {{ date('h:i A', strtotime($slot['end_time'])) }}
                                                        </div>
                                                        @if ($slot['room_number'])
                                                            <div class="text-muted small mt-1">
                                                                <i class="bi bi-door-open me-1"></i>
                                                                Room {{ $slot['room_number'] }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <span class="badge bg-info bg-opacity-10 text-info px-2">
                                                        <i class="bi bi-book-fill" style="font-size:.65rem;"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center py-5">
                                    <i class="bi bi-emoji-smile d-block mb-2 text-muted"
                                        style="font-size:2.5rem;opacity:.3;"></i>
                                    <div class="fw-semibold small text-muted">No classes on {{ $day['week_name'] }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach

            </div>
        </div>
    </main>

    <script>
        function showDay(index) {
            document.querySelectorAll('.day-panel').forEach(p => p.style.display = 'none');
            document.querySelectorAll('#dayTabs .nav-link').forEach(b => b.classList.remove('active'));
            document.getElementById('panel-' + index).style.display = '';
            document.querySelector('[data-day="' + index + '"]').classList.add('active');
        }
    </script>
@endsection
