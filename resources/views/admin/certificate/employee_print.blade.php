<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Staff Certificates</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=EB+Garamond:ital,wght@0,400;0,500;1,400&family=Cinzel+Decorative:wght@400;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            background: #fff;
        }

        .certificate-page {
            width: 297mm;
            height: 210mm;
            position: relative;
            overflow: hidden;
            background: #f8f4ed;
            page-break-after: always;
            display: flex;
            align-items: center;
            justify-content: center;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        /* ── Background watermark ── */
        .bg-seal {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 130mm;
            height: 130mm;
            opacity: .04;
            pointer-events: none;
        }

        /* ── Border system — slightly different from student: thicker outer, thinner inner ── */
        .border-outer {
            position: absolute;
            inset: 7mm;
            border: 2pt solid #8b6914;
            pointer-events: none;
        }

        .border-mid {
            position: absolute;
            inset: 9.5mm;
            border: .4pt solid #c9a84c;
            pointer-events: none;
        }

        .border-inner {
            position: absolute;
            inset: 11.5mm;
            border: .4pt solid #c9a84c88;
            pointer-events: none;
        }

        /* ── Decorative top/bottom bands ── */
        .band-top {
            position: absolute;
            top: 7mm;
            left: 7mm;
            right: 7mm;
            height: 5mm;
            background: linear-gradient(90deg, transparent 0%, #d4af3718 15%, #d4af3732 50%, #d4af3718 85%, transparent 100%);
            pointer-events: none;
        }

        .band-bottom {
            position: absolute;
            bottom: 7mm;
            left: 7mm;
            right: 7mm;
            height: 5mm;
            background: linear-gradient(90deg, transparent 0%, #d4af3718 15%, #d4af3732 50%, #d4af3718 85%, transparent 100%);
            pointer-events: none;
        }

        /* ── Corner ornaments — more elaborate than student version ── */
        .corner {
            position: absolute;
            width: 22mm;
            height: 22mm;
        }

        .corner svg {
            width: 100%;
            height: 100%;
        }

        .tl {
            top: 3mm;
            left: 3mm;
        }

        .tr {
            top: 3mm;
            right: 3mm;
            transform: scaleX(-1);
        }

        .bl {
            bottom: 3mm;
            left: 3mm;
            transform: scaleY(-1);
        }

        .br {
            bottom: 3mm;
            right: 3mm;
            transform: scale(-1, -1);
        }

        /* ── Side accent lines ── */
        .side-line {
            position: absolute;
            top: 20mm;
            bottom: 20mm;
            width: .3pt;
            background: linear-gradient(to bottom, transparent, #c9a84c44 20%, #c9a84c44 80%, transparent);
        }

        .side-left {
            left: 30mm;
        }

        .side-right {
            right: 30mm;
        }

        /* ── Content ── */
        .cert-content {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 18mm 24mm 15mm;
            text-align: center;
        }

        /* ── School header ── */
        .school-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4mm;
            margin-bottom: 2mm;
        }

        .logo-wrap {
            width: 13mm;
            height: 13mm;
            border-radius: 50%;
            border: 1pt solid #b8972a;
            background: linear-gradient(135deg, #f5e9cc, #e8d49a);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .logo-initials {
            font-family: 'Cinzel', serif;
            font-size: 5pt;
            font-weight: 700;
            color: #6b4a10;
        }

        .school-info {
            text-align: left;
        }

        .school-name {
            font-family: 'Cinzel', serif;
            font-size: 7pt;
            font-weight: 700;
            color: #4a3520;
            letter-spacing: .15em;
            text-transform: uppercase;
        }

        .school-tagline {
            font-family: 'EB Garamond', serif;
            font-size: 5pt;
            color: #7a5c3a;
            letter-spacing: .08em;
            font-style: italic;
            margin-top: .4mm;
        }

        /* ── Dividers ── */
        .divider-gold {
            width: 82%;
            height: .4pt;
            background: linear-gradient(90deg, transparent, #b8972a 15%, #d4af37 50%, #b8972a 85%, transparent);
            margin: 1.5mm 0;
        }

        .divider-ornament {
            display: flex;
            align-items: center;
            gap: 2.5mm;
            width: 55%;
            margin: 1.2mm 0;
        }

        .divider-ornament hr {
            flex: 1;
            border: none;
            border-top: .3pt solid #c9a84c88;
        }

        .ornament-shape {
            width: 3mm;
            height: 3mm;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Cert title — uses a ribbon-style treatment ── */
        .ribbon-wrap {
            position: relative;
            margin: 0 0 1mm;
            display: flex;
            align-items: center;
            gap: 3mm;
            width: 88%;
            justify-content: center;
        }

        .ribbon-line {
            flex: 1;
            height: .3pt;
            background: linear-gradient(90deg, transparent, #b8972a);
        }

        .ribbon-line.right {
            background: linear-gradient(90deg, #b8972a, transparent);
        }

        .cert-title {
            font-family: 'Cinzel Decorative', serif;
            font-size: 14pt;
            font-weight: 700;
            color: #6b4a10;
            letter-spacing: .1em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .cert-type-badge {
            font-family: 'Cinzel', serif;
            font-size: 5pt;
            color: #9a7840;
            letter-spacing: .28em;
            text-transform: uppercase;
            margin-top: .5mm;
        }

        /* ── "This is to certify" ── */
        .present-text {
            font-family: 'EB Garamond', serif;
            font-size: 6.5pt;
            color: #5a4020;
            letter-spacing: .15em;
            text-transform: uppercase;
            font-style: italic;
            margin: 1.8mm 0 .8mm;
        }

        /* ── Staff name — larger than student version, befitting seniority ── */
        .staff-name {
            font-family: 'Cinzel', serif;
            font-size: 20pt;
            font-weight: 600;
            color: #2d1f0e;
            letter-spacing: .06em;
            border-bottom: .6pt solid #c9a84c88;
            padding-bottom: 1.2mm;
            margin-bottom: 1mm;
            min-width: 85mm;
        }

        /* ── Staff details row ── */
        .staff-details {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 3mm;
            margin-bottom: 1.5mm;
        }

        .staff-detail-pill {
            font-family: 'EB Garamond', serif;
            font-size: 5.5pt;
            color: #6b4a10;
            letter-spacing: .08em;
            text-transform: uppercase;
            border: .3pt solid #c9a84c77;
            border-radius: .5mm;
            padding: .4mm 2mm;
        }

        .staff-detail-sep {
            font-family: 'EB Garamond', serif;
            font-size: 6pt;
            color: #c9a84c;
            font-style: normal;
        }

        /* ── Body text ── */
        .body-text {
            font-family: 'EB Garamond', serif;
            font-size: 7.5pt;
            color: #3d2b10;
            line-height: 1.65;
            max-width: 83%;
            margin: .5mm 0 1.5mm;
        }

        .body-text em {
            font-style: italic;
            color: #6b4e22;
        }

        .body-text strong {
            font-weight: 500;
            color: #2d1f0e;
        }

        /* ── Service badge ── */
        .service-badge {
            display: inline-flex;
            align-items: center;
            gap: 2mm;
            background: linear-gradient(135deg, #f9f0d8, #eee0aa);
            border: .7pt solid #c9a84c;
            border-radius: 1mm;
            padding: 1mm 5mm;
            margin: .8mm 0 1.2mm;
        }

        .service-badge .badge-text {
            font-family: 'Cinzel', serif;
            font-size: 6pt;
            font-weight: 700;
            color: #7a5210;
            letter-spacing: .1em;
        }

        /* ── Signatures — 4 column layout for staff ── */
        .sig-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            width: 92%;
            gap: 3mm;
            margin-top: 1.5mm;
        }

        .sig-block {
            text-align: center;
            flex: 1;
        }

        .sig-image {
            max-height: 7mm;
            max-width: 20mm;
            object-fit: contain;
            margin-bottom: .8mm;
        }

        .sig-line {
            width: 100%;
            height: .4pt;
            background: #b8972a77;
            margin-bottom: .8mm;
        }

        .sig-name {
            font-family: 'Cinzel', serif;
            font-size: 5pt;
            color: #4a3520;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .sig-role {
            font-family: 'EB Garamond', serif;
            font-size: 4.5pt;
            color: #7a6040;
            font-style: italic;
            margin-top: .3mm;
        }

        /* ── Center seal (more elaborate for staff) ── */
        .seal-block {
            text-align: center;
            flex: 0 0 22mm;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .seal-outer {
            width: 16mm;
            height: 16mm;
            border-radius: 50%;
            border: 1pt solid #b8972a;
            background: linear-gradient(135deg, #f9f0d8, #edd88a);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            margin-bottom: .8mm;
        }

        .seal-text {
            font-family: 'Cinzel', serif;
            font-size: 3.5pt;
            color: #7a5210;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        /* ── Footer stamps ── */
        .date-stamp {
            position: absolute;
            bottom: 13mm;
            right: 24mm;
            text-align: right;
        }

        .cert-no-stamp {
            position: absolute;
            bottom: 13mm;
            left: 24mm;
        }

        .stamp-label {
            font-family: 'EB Garamond', serif;
            font-size: 4.5pt;
            color: #9a7840;
            letter-spacing: .1em;
            text-transform: uppercase;
            font-style: italic;
        }

        .stamp-val {
            font-family: 'Cinzel', serif;
            font-size: 5.5pt;
            color: #4a3520;
            letter-spacing: .06em;
        }

        /* ── Photo (optional — floated right inside content) ── */
        .staff-photo-float {
            position: absolute;
            top: 30mm;
            right: 26mm;
            z-index: 3;
        }

        .staff-photo-frame {
            border: 1pt solid #b8972a;
            background: #f9f0d8;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .staff-photo-frame.square {
            border-radius: 1mm;
        }

        .staff-photo-frame.circle {
            border-radius: 50%;
        }

        .staff-photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ── Print bar (screen only) ── */
        .print-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            background: #1a1714;
            color: #d4af37;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: 'Cinzel', serif;
            font-size: 11px;
            letter-spacing: .1em;
        }

        .print-bar .count {
            color: #9a7840;
        }

        .print-bar button {
            padding: 6px 20px;
            border-radius: 4px;
            border: 1px solid #b8972a;
            cursor: pointer;
            font-family: 'Cinzel', serif;
            font-size: 10px;
            letter-spacing: .1em;
            transition: all .2s;
        }

        .btn-print {
            background: #b8972a;
            color: #1a1714;
        }

        .btn-print:hover {
            background: #d4af37;
        }

        .btn-close2 {
            background: transparent;
            color: #b8972a;
            margin-left: 8px;
        }

        @media screen {
            body {
                background: #2a2520;
                padding-top: 50px;
            }

            .certificate-page {
                margin: 10px auto;
                box-shadow: 0 8px 40px rgba(0, 0, 0, .7), 0 0 0 1pt rgba(212, 175, 55, .2);
            }
        }

        @media print {
            body {
                background: #fff;
                padding: 0;
            }

            .print-bar {
                display: none !important;
            }

            .certificate-page {
                margin: 0;
                box-shadow: none;
            }
        }
    </style>
</head>

<body>

    <!-- Print bar -->
    <div class="print-bar">
        <span>
            ✦ <span class="count">{{ $employees->count() }}</span>
            Staff Certificate{{ $employees->count() !== 1 ? 's' : '' }} Ready
        </span>
        <div>
            <button class="btn-print" onclick="window.print()">⎙ &nbsp;Print / Save PDF</button>
            <button class="btn-close2" onclick="window.close()">✕ Close</button>
        </div>
    </div>

    @php
        $printDate = $request->print_date ?? now()->toDateString();
        $printDateFmt = \Carbon\Carbon::parse($printDate)->format('d F Y');
        $printYear = \Carbon\Carbon::parse($printDate)->format('Y');
        $schoolName = config('app.school_name', env('SCHOOL_NAME', 'Your Institution Name'));
        $schoolTagline = config('app.school_tagline', env('SCHOOL_TAGLINE', 'Excellence · Integrity · Service'));
        $photoSize = $template->photo_size ?? 24; // mm
        $photoStyle = $template->photo_style ?? 'square';

        // Reusable ornate corner SVG (larger and more detailed than student version)
        $cornerSvg = '<svg viewBox="0 0 66 66"><g stroke="#b8972a" fill="none">
        <path d="M2 2 L32 2 L32 7 L7 7 L7 32 L2 32 Z" stroke-width=".8" fill="#d4af3712"/>
        <path d="M10 10 L24 10 L24 15 L15 15 L15 24 L10 24 Z" stroke-width=".5" fill="#d4af3708"/>
        <circle cx="32" cy="32" r="5"  fill="#d4af3725" stroke-width=".6"/>
        <circle cx="32" cy="32" r="2.5" fill="#b8972a" stroke="none"/>
        <line x1="37" y1="32" x2="55" y2="32" stroke-width=".4"/>
        <line x1="32" y1="37" x2="32" y2="55" stroke-width=".4"/>
        <line x1="37" y1="27" x2="45" y2="20" stroke-width=".3"/>
        <line x1="27" y1="37" x2="20" y2="45" stroke-width=".3"/>
        <circle cx="4" cy="4" r="2.5" fill="#b8972a" stroke="none"/>
        <circle cx="14" cy="4" r="1"   fill="#c9a84c" stroke="none"/>
        <circle cx="4" cy="14" r="1"   fill="#c9a84c" stroke="none"/>
    </g></svg>';
    @endphp

    @foreach ($employees as $emp)
        @php
            // Replace all placeholders
            $content = $template->content;
            $empFullName = trim(($emp->name ?? '') . ' ' . ($emp->last_name ?? ''));
            $content = str_replace('{name}', $empFullName, $content);
            $content = str_replace('{first_name}', $emp->name ?? '', $content);
            $content = str_replace('{last_name}', $emp->last_name ?? '', $content);
            $content = str_replace('{staff_id}', $emp->staff_id ?? '', $content);
            $content = str_replace('{department}', $emp->department ?? '', $content);
            $content = str_replace('{designation}', $emp->designation ?? '', $content);
            $content = str_replace('{mobile}', $emp->mobileno ?? '', $content);
            $content = str_replace('{email}', $emp->email ?? '', $content);
            $content = str_replace('{date}', $printDateFmt, $content);
            $content = str_replace('{print_date}', $printDateFmt, $content);
            $content = str_replace('{year}', $printYear, $content);
            $content = str_replace('{school_name}', $schoolName, $content);

            $certNo =
                strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $schoolName), 0, 3)) .
                '/EMP/' .
                $printYear .
                '/' .
                str_pad($emp->id, 4, '0', STR_PAD_LEFT);
        @endphp

        <div class="certificate-page">

            {{-- Background watermark --}}
            <svg class="bg-seal" viewBox="0 0 200 200">
                <circle cx="100" cy="100" r="96" fill="none" stroke="#8b6914" stroke-width="1.8" />
                <circle cx="100" cy="100" r="86" fill="none" stroke="#8b6914" stroke-width=".8" />
                <circle cx="100" cy="100" r="70" fill="none" stroke="#8b6914" stroke-width=".4" />
                <g stroke="#8b6914" stroke-width=".7">
                    <line x1="100" y1="10" x2="100" y2="30" />
                    <line x1="100" y1="170" x2="100" y2="190" />
                    <line x1="10" y1="100" x2="30" y2="100" />
                    <line x1="170" y1="100" x2="190" y2="100" />
                    <line x1="29" y1="29" x2="43" y2="43" />
                    <line x1="157" y1="157" x2="171" y2="171" />
                    <line x1="171" y1="29" x2="157" y2="43" />
                    <line x1="29" y1="171" x2="43" y2="157" />
                    <!-- diagonal rays -->
                    <line x1="59" y1="17" x2="65" y2="30" />
                    <line x1="141" y1="17" x2="135" y2="30" />
                    <line x1="17" y1="59" x2="30" y2="65" />
                    <line x1="17" y1="141" x2="30" y2="135" />
                </g>
                <polygon points="100,26 109,88 165,88 118,122 136,178 100,144 64,178 82,122 35,88 91,88" fill="none"
                    stroke="#8b6914" stroke-width="1.2" />
                <text x="100" y="104" text-anchor="middle" font-family="Cinzel,serif" font-size="8.5" fill="#8b6914"
                    letter-spacing="3">EXCELLENCE</text>
                <text x="100" y="116" text-anchor="middle" font-family="Cinzel,serif" font-size="5.5" fill="#8b6914"
                    letter-spacing="2.5">IN SERVICE</text>
            </svg>

            {{-- Border system --}}
            <div class="border-outer"></div>
            <div class="border-mid"></div>
            <div class="border-inner"></div>

            {{-- Decorative bands --}}
            <div class="band-top"></div>
            <div class="band-bottom"></div>

            {{-- Side accent lines --}}
            <div class="side-line side-left"></div>
            <div class="side-line side-right"></div>

            {{-- Corner ornaments --}}
            <div class="corner tl">{!! $cornerSvg !!}</div>
            <div class="corner tr">{!! $cornerSvg !!}</div>
            <div class="corner bl">{!! $cornerSvg !!}</div>
            <div class="corner br">{!! $cornerSvg !!}</div>

            {{-- Staff photo (floated, optional) --}}
            @if ($photoStyle !== 'none' && isset($emp->image) && $emp->image)
                <div class="staff-photo-float">
                    <div class="staff-photo-frame {{ $photoStyle }}"
                        style="width:{{ $photoSize }}mm;height:{{ $photoSize }}mm;">
                        <img src="{{ asset('storage/' . $emp->image) }}" alt="Photo">
                    </div>
                </div>
            @endif

            {{-- Main content --}}
            <div class="cert-content">

                {{-- School header --}}
                <div class="school-header">
                    <div class="logo-wrap">
                        @if ($template->logo_image)
                            <img src="{{ asset('storage/' . $template->logo_image) }}" alt="Logo">
                        @else
                            <div class="logo-initials">
                                {{ strtoupper(substr(preg_replace('/[^A-Za-z\s]/', '', $schoolName), 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div class="school-info">
                        <div class="school-name">{{ $schoolName }}</div>
                        <div class="school-tagline">{{ $schoolTagline }}</div>
                    </div>
                </div>

                <div class="divider-gold"></div>

                {{-- Certificate title with ribbon lines --}}
                <div class="ribbon-wrap">
                    <div class="ribbon-line"></div>
                    <div class="cert-title">{{ $template->name }}</div>
                    <div class="ribbon-line right"></div>
                </div>
                <div class="cert-type-badge">
                    Presented in Recognition of Distinguished Service &nbsp;·&nbsp; {{ $printYear }}
                </div>

                <div class="divider-ornament">
                    <hr>
                    <div class="ornament-shape">
                        <svg width="10" height="10" viewBox="0 0 10 10">
                            <polygon points="5,0 6.5,3.5 10,3.8 7.5,6 8.2,9.5 5,7.8 1.8,9.5 2.5,6 0,3.8 3.5,3.5"
                                fill="#c9a84c" />
                        </svg>
                    </div>
                    <hr>
                </div>

                <div class="present-text">This is to proudly certify that</div>

                {{-- Staff name --}}
                <div class="staff-name">{{ $empFullName }}</div>

                {{-- Staff details row --}}
                <div class="staff-details">
                    @if (!empty($emp->designation))
                        <div class="staff-detail-pill">{{ $emp->designation }}</div>
                        @if (!empty($emp->department) || !empty($emp->staff_id))
                            <div class="staff-detail-sep">·</div>
                        @endif
                    @endif
                    @if (!empty($emp->department))
                        <div class="staff-detail-pill">{{ $emp->department }} Dept.</div>
                        @if (!empty($emp->staff_id))
                            <div class="staff-detail-sep">·</div>
                        @endif
                    @endif
                    @if (!empty($emp->staff_id))
                        <div class="staff-detail-pill">ID: {{ $emp->staff_id }}</div>
                    @endif
                </div>

                {{-- Certificate body --}}
                @if ($content && strip_tags($content) !== '')
                    <div class="body-text">{!! $content !!}</div>
                @else
                    <div class="body-text">
                        has served this institution with unwavering dedication, professional excellence,
                        and the highest standards of integrity. Their contributions have made a lasting
                        impact on the growth and success of <em>{{ $schoolName }}</em>, and this
                        certificate is awarded in sincere recognition of their distinguished service.
                    </div>
                @endif

                {{-- Service badge --}}
                <div class="service-badge">
                    <svg width="10" height="10" viewBox="0 0 10 10">
                        <polygon points="5,0 6.5,3.5 10,3.8 7.5,6 8.2,9.5 5,7.8 1.8,9.5 2.5,6 0,3.8 3.5,3.5"
                            fill="#b8972a" />
                    </svg>
                    <div class="badge-text">Issued with Honour &nbsp;·&nbsp; {{ $printDateFmt }}</div>
                    <svg width="10" height="10" viewBox="0 0 10 10">
                        <polygon points="5,0 6.5,3.5 10,3.8 7.5,6 8.2,9.5 5,7.8 1.8,9.5 2.5,6 0,3.8 3.5,3.5"
                            fill="#b8972a" />
                    </svg>
                </div>

                <div class="divider-gold"></div>

                {{-- Signatures — 4 columns for staff (HR + HOD + Seal + Principal) --}}
                <div class="sig-row">
                    <div class="sig-block">
                        <div style="height:7mm;"></div>
                        <div class="sig-line"></div>
                        <div class="sig-name">HR Manager</div>
                        <div class="sig-role">Human Resources</div>
                    </div>

                    <div class="sig-block">
                        <div style="height:7mm;"></div>
                        <div class="sig-line"></div>
                        <div class="sig-name">Head of Department</div>
                        <div class="sig-role">
                            {{ !empty($emp->department) ? $emp->department . ' Dept.' : 'Department Head' }}
                        </div>
                    </div>

                    {{-- Official seal --}}
                    <div class="seal-block">
                        <div class="seal-outer">
                            <svg width="13mm" height="13mm" viewBox="0 0 38 38">
                                <circle cx="19" cy="19" r="17" fill="none" stroke="#b8972a"
                                    stroke-width=".8" />
                                <circle cx="19" cy="19" r="13" fill="none" stroke="#c9a84c"
                                    stroke-width=".4" />
                                <polygon
                                    points="19,5 21.5,13.5 30.5,13.5 23,19 25.5,28 19,23 12.5,28 15,19 7.5,13.5 16.5,13.5"
                                    fill="#d4af37" opacity=".95" />
                            </svg>
                        </div>
                        <div class="seal-text">Official Seal</div>
                    </div>

                    <div class="sig-block">
                        @if ($template->signature_image)
                            <img src="{{ asset('storage/' . $template->signature_image) }}" class="sig-image"
                                alt="Signature">
                        @else
                            <div style="height:7mm;"></div>
                        @endif
                        <div class="sig-line"></div>
                        <div class="sig-name">Principal / Director</div>
                        <div class="sig-role">Authorized Signatory</div>
                    </div>
                </div>

            </div>

            {{-- Date stamp --}}
            <div class="date-stamp">
                <div class="stamp-label">Date of Issue</div>
                <div class="stamp-val">{{ $printDateFmt }}</div>
            </div>

            {{-- Certificate number --}}
            <div class="cert-no-stamp">
                <div class="stamp-label">Certificate No.</div>
                <div class="stamp-val">{{ $certNo }}</div>
            </div>

        </div>
    @endforeach

</body>

</html>
