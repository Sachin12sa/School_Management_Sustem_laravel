@extends('layouts.app')
@section('content')
<main class="app-main">

    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row align-items-center mb-2">
                <div class="col-sm-8">
                    <div class="d-flex align-items-center gap-3">
                        @if(!empty(Auth::user()->getProfile()))
                            <img src="{{ Auth::user()->getProfile() }}"
                                 class="rounded-circle shadow flex-shrink-0"
                                 style="width:54px;height:54px;object-fit:cover;border:3px solid rgba(220,53,69,.3);">
                        @else
                            <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold shadow flex-shrink-0"
                                 style="width:54px;height:54px;font-size:1.3rem;">
                                {{ strtoupper(substr(Auth::user()->name,0,1)) }}{{ strtoupper(substr(Auth::user()->last_name??'',0,1)) }}
                            </div>
                        @endif
                        <div>
                            @php $h = now()->hour; $greet = $h < 12 ? 'Good Morning' : ($h < 17 ? 'Good Afternoon' : 'Good Evening'); @endphp
                            <p class="mb-0 text-muted small">{{ $greet }},</p>
                            <h4 class="mb-0 fw-bold text-dark">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 small mt-1">
                                <i class="bi bi-house-heart-fill me-1"></i>Parent
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 text-end text-muted small">
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, d F Y') }}
                </div>
            </div>
        </div>
    </div>

    <div class="app-content">
        <div class="container-fluid">

            {{-- Stat Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:52px;height:52px;font-size:1.4rem;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-3 text-dark lh-1">{{ $TotalStudent ?? '0' }}</div>
                                <div class="text-muted small mt-1">My Children</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top py-2">
                            <a href="{{ url('parent/my_student') }}" class="text-danger text-decoration-none small">
                                View all <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:52px;height:52px;font-size:1.4rem;">
                                <i class="bi bi-megaphone-fill"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-3 text-dark lh-1">{{ $TotalNotice ?? '0' }}</div>
                                <div class="text-muted small mt-1">Notices</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top py-2">
                            <a href="{{ url('parent/my_notice_board') }}" class="text-warning text-decoration-none small">
                                View all <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-3 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                                 style="width:52px;height:52px;font-size:1.4rem;">
                                <i class="bi bi-calendar-event-fill"></i>
                            </div>
                            <div>
                                <div class="fw-bold fs-5 text-dark lh-1">{{ now()->format('d M') }}</div>
                                <div class="text-muted small mt-1">Today's Date</div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top py-2">
                            <span class="text-primary small">{{ now()->format('l') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-lg-8">

                    {{-- Quick Actions --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                            <i class="bi bi-lightning-charge-fill text-danger"></i>
                            <h6 class="mb-0 fw-semibold">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @php
                                $actions = [
                                    ['url'=>'parent/my_student',              'icon'=>'bi-people-fill',    'color'=>'danger',    'label'=>'My Children'],
                                    ['url'=>'parent/my_notice_board',         'icon'=>'bi-megaphone-fill', 'color'=>'warning',   'label'=>'Notice Board'],
                                    ['url'=>'parent/account',                 'icon'=>'bi-person-circle',  'color'=>'primary',   'label'=>'My Account'],
                                    ['url'=>'parent/profile/change_password', 'icon'=>'bi-lock-fill',      'color'=>'secondary', 'label'=>'Change Password'],
                                ];
                                @endphp
                                @foreach($actions as $a)
                                <div class="col-md-3 col-6">
                                    <a href="{{ url($a['url']) }}"
                                       class="text-decoration-none d-flex flex-column align-items-center gap-2 p-3 rounded-3 border h-100 text-center action-tile">
                                        <div class="rounded-circle bg-{{ $a['color'] }} bg-opacity-10 text-{{ $a['color'] }} d-flex align-items-center justify-content-center"
                                             style="width:44px;height:44px;font-size:1.1rem;">
                                            <i class="bi {{ $a['icon'] }}"></i>
                                        </div>
                                        <span class="small fw-semibold text-dark" style="font-size:.74rem;line-height:1.2;">{{ $a['label'] }}</span>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Students Table --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-people-fill text-danger"></i>
                                <h6 class="mb-0 fw-semibold">My Children</h6>
                            </div>
                            <a href="{{ url('parent/my_student') }}" class="btn btn-sm btn-outline-danger px-3">
                                <i class="bi bi-eye me-1"></i>View All
                            </a>
                        </div>
                        <div class="card-body p-0">
                            @if(!empty($getStudents) && $getStudents->count())
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light text-uppercase text-secondary" style="font-size:.7rem;letter-spacing:.05em;">
                                        <tr>
                                            <th class="ps-4">#</th>
                                            <th>Student</th>
                                            <th>Class</th>
                                            <th>Attendance</th>
                                            <th class="text-center pe-4">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($getStudents as $student)
                                        <tr>
                                            <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($student->profile_pic)
                                                        <img src="{{ asset('storage/'.$student->profile_pic) }}"
                                                             class="rounded-circle flex-shrink-0"
                                                             style="width:34px;height:34px;object-fit:cover;">
                                                    @else
                                                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                                             style="width:34px;height:34px;font-size:.78rem;">
                                                            {{ strtoupper(substr($student->name,0,1)) }}{{ strtoupper(substr($student->last_name??'',0,1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold small text-dark">{{ $student->name }} {{ $student->last_name }}</div>
                                                        <div class="text-muted" style="font-size:.72rem;">{{ $student->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-primary bg-opacity-10 text-primary px-2">{{ $student->class_name ?? 'N/A' }}</span></td>
                                            <td>
                                                @php $att = $student->attendance_percent ?? null; @endphp
                                                @if($att !== null)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="progress flex-fill" style="height:6px;width:80px;">
                                                            <div class="progress-bar {{ $att >= 75 ? 'bg-success' : ($att >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                                                 style="width:{{ $att }}%"></div>
                                                        </div>
                                                        <span class="small text-muted">{{ $att }}%</span>
                                                    </div>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center pe-4">
                                                <span class="badge rounded-pill {{ $student->status == 0 ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }} px-3">
                                                    <i class="bi bi-circle-fill me-1" style="font-size:.4rem;vertical-align:middle;"></i>
                                                    {{ $student->status == 0 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="bi bi-people d-block mb-2 text-muted" style="font-size:2.5rem;opacity:.3;"></i>
                                    <div class="fw-semibold small text-muted">No students linked yet</div>
                                    <div class="text-muted" style="font-size:.78rem;">Contact the admin to link your children.</div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                {{-- Profile --}}
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
                            <i class="bi bi-house-heart-fill text-danger"></i>
                            <h6 class="mb-0 fw-semibold">My Profile</h6>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center text-center py-4 gap-3">
                            @if(!empty(Auth::user()->getProfile()))
                                <img src="{{ Auth::user()->getProfile() }}"
                                     class="rounded-circle shadow"
                                     style="width:90px;height:90px;object-fit:cover;border:3px solid rgba(220,53,69,.3);">
                            @else
                                <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold shadow"
                                     style="width:90px;height:90px;font-size:2rem;">
                                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}{{ strtoupper(substr(Auth::user()->last_name??'',0,1)) }}
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold text-dark">{{ Auth::user()->name }} {{ Auth::user()->last_name }}</div>
                                <div class="text-muted small">{{ Auth::user()->email }}</div>
                                <span class="badge bg-danger bg-opacity-10 text-danger mt-1">Parent</span>
                            </div>
                            <div class="w-100">
                                <div class="d-flex justify-content-between border-bottom py-2 small">
                                    <span class="text-muted"><i class="bi bi-phone me-1"></i>Mobile</span>
                                    <span class="fw-semibold text-dark">{{ Auth::user()->mobile_number ?? '—' }}</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom py-2 small">
                                    <span class="text-muted"><i class="bi bi-briefcase me-1"></i>Occupation</span>
                                    <span class="fw-semibold text-dark text-truncate ms-2" style="max-width:120px;">{{ Auth::user()->occupation ?? '—' }}</span>
                                </div>
                                <div class="d-flex justify-content-between border-bottom py-2 small">
                                    <span class="text-muted"><i class="bi bi-droplet-fill me-1"></i>Blood Group</span>
                                    <span class="fw-semibold text-dark">{{ Auth::user()->blood_group ?? '—' }}</span>
                                </div>
                                <div class="d-flex justify-content-between py-2 small">
                                    <span class="text-muted"><i class="bi bi-geo-alt me-1"></i>Address</span>
                                    <span class="fw-semibold text-dark text-end text-truncate ms-2" style="max-width:130px;">{{ Auth::user()->address ?? '—' }}</span>
                                </div>
                            </div>
                            <a href="{{ url('parent/account') }}" class="btn btn-danger btn-sm w-100">
                                <i class="bi bi-pencil-fill me-2"></i>Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</main>
<style>
.action-tile { transition: all .15s ease; background: #f8f9fa; }
.action-tile:hover { background: rgba(220,53,69,.06) !important; border-color: rgba(220,53,69,.3) !important; transform: translateY(-2px); }
</style>
@endsection