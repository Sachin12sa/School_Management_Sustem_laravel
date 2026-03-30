<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Cards — {{ $template->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background: #f3f4f6;
            padding: 20px;
        }

        /* ── Action bar ── */
        .action-bar {
            max-width: 1100px;
            margin: 0 auto 16px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn {
            padding: 8px 18px;
            border-radius: 7px;
            border: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-print {
            background: #1a56a0;
            color: #fff;
        }

        .btn-print:hover {
            background: #0d3a7a;
        }

        .btn-back {
            background: #fff;
            color: #374151;
            border: 1.5px solid #d1d5db;
        }

        /* ── Grid of cards ── */
        .cards-grid {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
        }

        /* ── Single ID card ── */
        .id-card {
            width: {{ round($template->layout_width * 3.78) }}px;
            /* 1mm ≈ 3.7795px at 96dpi */
            min-width: 240px;
            max-width: 320px;
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 6px 30px rgba(0, 0, 0, .15);
            font-family: 'Arial', sans-serif;
            flex-shrink: 0;
            position: relative;
        }

        /* ── Card header ── */
        .card-header {
            background: {{ $template->accent_color }};
            color: {{ $template->text_color }};
            padding: {{ $template->top_space }}px {{ $template->right_space }}px 28px {{ $template->left_space }}px;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 30%;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
        }

        .header-top {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
        }

        .school-logo {
            width: 36px;
            height: 36px;
            object-fit: contain;
            border-radius: 6px;
            background: rgba(255, 255, 255, .15);
            flex-shrink: 0;
        }

        .school-logo-placeholder {
            width: 36px;
            height: 36px;
            border-radius: 6px;
            background: rgba(255, 255, 255, .2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .school-info .school-name {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .02em;
            line-height: 1.2;
        }

        .school-info .school-sub {
            font-size: 8px;
            opacity: .75;
            margin-top: 1px;
        }

        .id-label {
            position: absolute;
            top: 10px;
            right: {{ $template->right_space }}px;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            opacity: .7;
            z-index: 1;
        }

        /* ── Photo ── */
        .photo-wrap {
            display: flex;
            justify-content: center;
            margin-top: -30px;
            position: relative;
            z-index: 2;
        }

        .photo-img {
            width: {{ $template->photo_size }}px;
            height: {{ $template->photo_size }}px;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .18);

            @if ($template->photo_style === 'circle')
                border-radius: 50%;
            @elseif($template->photo_style === 'rounded')
                border-radius: 10px;
            @else
                border-radius: 4px;
            @endif
        }

        /* ── Body ── */
        .card-body {
            padding: 8px {{ $template->right_space }}px 0 {{ $template->left_space }}px;
            text-align: center;
        }

        .user-name {
            font-size: 14px;
            font-weight: 800;
            color: #111827;
            margin-top: 6px;
            line-height: 1.2;
        }

        .user-role {
            font-size: 8px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: {{ $template->accent_color }};
            margin-top: 2px;
            padding: 2px 10px;
            border-radius: 20px;
            background: {{ $template->accent_color }}18;
            display: inline-block;
            margin-bottom: 8px;
        }

        .divider {
            height: 1px;
            background: #f3f4f6;
            margin: 6px 0;
        }

        /* ── Info grid ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4px 8px;
            text-align: left;
            margin: 6px 0;
        }

        .info-item {}

        .info-label {
            font-size: 7px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #9ca3af;
            display: block;
        }

        .info-val {
            font-size: 9px;
            font-weight: 600;
            color: #374151;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── Bottom strip ── */
        .card-bottom {
            margin-top: 8px;
            background: {{ $template->accent_color }};
            padding: 6px {{ $template->right_space }}px {{ $template->bottom_space }}px {{ $template->left_space }}px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .validity {
            color: rgba(255, 255, 255, .9);
        }

        .validity .vld-label {
            font-size: 7px;
            opacity: .75;
        }

        .validity .vld-date {
            font-size: 9px;
            font-weight: 700;
        }

        /* ── QR code stub / barcode ── */
        .qr-area {
            width: 36px;
            height: 36px;
            background: #fff;
            border-radius: 5px;
            padding: 3px;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1.5px;
        }

        .qr-area span {
            background: {{ $template->accent_color }};
            border-radius: 1px;
        }

        /* ── Signature ── */
        .signature-area {
            text-align: center;
            padding: 2px 0;
        }

        .signature-img {
            height: 28px;
            object-fit: contain;
            margin-bottom: 1px;
        }

        .sig-line {
            width: 70px;
            height: 1px;
            background: rgba(255, 255, 255, .4);
            margin: 0 auto 2px;
        }

        .sig-label {
            font-size: 7px;
            color: rgba(255, 255, 255, .7);
        }

        /* ── Footer text ── */
        .card-footer-text {
            font-size: 7px;
            color: #9ca3af;
            text-align: center;
            padding: 4px {{ $template->right_space }}px {{ $template->bottom_space }}px;
            border-top: 1px solid #f3f4f6;
        }

        /* ── Print styles ── */
        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .action-bar {
                display: none !important;
            }

            .cards-grid {
                gap: 8mm;
                max-width: 100%;
            }

            .id-card {
                box-shadow: 0 0 0 1px #e5e7eb;
            }

            @page {
                margin: 10mm;
                size: A4;
            }
        }
    </style>
</head>

<body>

    {{-- Action bar --}}
    <div class="action-bar">
        <button class="btn btn-print" onclick="window.print()">🖨️ Print ID Cards</button>
        <a href="javascript:window.close()" class="btn btn-back">✕ Close</a>
        <span style="font-size:13px;color:#6b7280;">
            {{ $users->count() }} card(s) —
            <strong>{{ $template->name }}</strong>
        </span>
    </div>

    {{-- Cards grid --}}
    <div class="cards-grid">

        @foreach ($users as $user)
            @php
                $userTypeLabels = [
                    1 => 'Administrator',
                    2 => 'Teacher',
                    3 => 'Student',
                    4 => 'Parent',
                    5 => 'Accountant',
                    6 => 'Librarian',
                ];
                $roleLabel = $userTypeLabels[$user->user_type] ?? 'Staff';
                $fullName = $user->name . ' ' . $user->last_name;
            @endphp

            <div class="id-card">

                {{-- Header --}}
                <div class="card-header">
                    <div class="id-label">ID CARD</div>
                    <div class="header-top">
                        @if ($template->logo_image)
                            <img src="{{ asset('storage/' . $template->logo_image) }}" class="school-logo"
                                alt="logo">
                        @else
                            <div class="school-logo-placeholder">🎓</div>
                        @endif
                        <div class="school-info">
                            <div class="school-name">Brain Fart Institute</div>
                            <div class="school-sub">School Management System</div>
                        </div>
                    </div>
                </div>

                {{-- Photo --}}
                <div class="photo-wrap">
                    <img src="{{ $user->getProfile() }}" class="photo-img" alt="{{ $fullName }}"
                        onerror="this.src='{{ asset('dist/assets/img/user.jpg') }}'">
                </div>

                {{-- Body --}}
                <div class="card-body">
                    <div class="user-name">{{ $fullName }}</div>
                    <div class="user-role">{{ $roleLabel }}</div>

                    <div class="divider"></div>

                    <div class="info-grid">
                        @if ($user->admission_number)
                            <div class="info-item">
                                <span class="info-label">Adm. No.</span>
                                <span class="info-val">{{ $user->admission_number }}</span>
                            </div>
                        @endif
                        @if ($user->roll_number)
                            <div class="info-item">
                                <span class="info-label">Roll No.</span>
                                <span class="info-val">{{ $user->roll_number }}</span>
                            </div>
                        @endif
                        @if (isset($user->class_name) && $user->class_name)
                            <div class="info-item">
                                <span class="info-label">Class</span>
                                <span class="info-val">
                                    {{ $user->class_name }}
                                    @if (isset($user->section_name) && $user->section_name)
                                        — {{ $user->section_name }}
                                    @endif
                                </span>
                            </div>
                        @endif
                        @if ($user->blood_group)
                            <div class="info-item">
                                <span class="info-label">Blood Group</span>
                                <span class="info-val"
                                    style="color:#dc2626;font-weight:800;">{{ $user->blood_group }}</span>
                            </div>
                        @endif
                        @if ($user->mobile_number)
                            <div class="info-item">
                                <span class="info-label">Mobile</span>
                                <span class="info-val">{{ $user->mobile_number }}</span>
                            </div>
                        @endif
                        @if ($user->gender)
                            <div class="info-item">
                                <span class="info-label">Gender</span>
                                <span class="info-val">{{ $user->gender }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Bottom strip --}}
                <div class="card-bottom">
                    <div class="validity">
                        <div class="vld-label">Issued</div>
                        <div class="vld-date">{{ $printDateBs }}</div>
                    </div>

                    @if ($template->signature_image)
                        <div class="signature-area">
                            <img src="{{ asset('storage/' . $template->signature_image) }}" class="signature-img"
                                alt="signature">
                            <div class="sig-line"></div>
                            <div class="sig-label">Principal</div>
                        </div>
                    @endif

                    <div>
                        <div class="validity" style="text-align:right;">
                            <div class="vld-label">Expires</div>
                            <div class="vld-date">{{ $expiryDateBs }}</div>
                        </div>
                        {{-- QR stub representing unique ID --}}
                        <div class="qr-area" style="margin-top:4px;">
                            @for ($q = 0; $q < 25; $q++)
                                <span
                                    style="{{ in_array($q, [0, 1, 5, 10, 11, 12, 15, 16, 20, 24, 3, 8, 23, 6]) ? '' : 'background:transparent;' }}"></span>
                            @endfor
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                @if ($template->extra_content)
                    <div class="card-footer-text">{{ $template->extra_content }}</div>
                @else
                    <div class="card-footer-text">Brain Fart Institute • Kathmandu, Nepal</div>
                @endif

            </div>
        @endforeach

    </div>

</body>

</html>
