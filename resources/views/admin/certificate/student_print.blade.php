<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Certificates</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=EB+Garamond:ital,wght@0,400;0,500;1,400&family=Cinzel+Decorative:wght@400;700&display=swap"
        rel="stylesheet">
    <style>
        /* ── Reset ── */
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

        /* ── One certificate per A4 landscape page ── */
        .certificate-page {
            width: 297mm;
            height: 210mm;
            position: relative;
            overflow: hidden;
            background: #faf6ee;
            page-break-after: always;
            display: flex;
            align-items: center;
            justify-content: center;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        /* ── Background watermark seal ── */
        .bg-seal {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 140mm;
            height: 140mm;
            opacity: .04;
            pointer-events: none;
        }

        /* ── Border frame ── */
        .border-outer {
            position: absolute;
            inset: 8mm;
            border: 1.5pt solid #b8972a;
            pointer-events: none;
        }

        .border-inner {
            position: absolute;
            inset: 11mm;
            border: .7pt solid #c9a84c;
            pointer-events: none;
        }

        .border-inner2 {
            position: absolute;
            inset: 13mm;
            border: .3pt solid #d4af3755;
            pointer-events: none;
        }

        /* ── Corner ornaments ── */
        .corner {
            position: absolute;
            width: 18mm;
            height: 18mm;
        }

        .corner svg {
            width: 100%;
            height: 100%;
        }

        .tl {
            top: 4mm;
            left: 4mm;
        }

        .tr {
            top: 4mm;
            right: 4mm;
            transform: scaleX(-1);
        }

        .bl {
            bottom: 4mm;
            left: 4mm;
            transform: scaleY(-1);
        }

        .br {
            bottom: 4mm;
            right: 4mm;
            transform: scale(-1, -1);
        }

        /* ── Content area ── */
        .cert-content {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 18mm 22mm 16mm;
            text-align: center;
            gap: 0;
        }

        /* ── School header ── */
        .school-logo-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 4mm;
            margin-bottom: 1.5mm;
        }

        .logo-wrap {
            width: 14mm;
            height: 14mm;
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
            font-size: 5.5pt;
            font-weight: 700;
            color: #6b4a10;
        }

        .school-name-block {
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
            font-size: 5.5pt;
            color: #7a5c3a;
            letter-spacing: .08em;
            font-style: italic;
            margin-top: .5mm;
        }

        /* ── Gold dividers ── */
        .divider-gold {
            width: 85%;
            height: .4pt;
            background: linear-gradient(90deg, transparent, #b8972a 15%, #d4af37 50%, #b8972a 85%, transparent);
            margin: 1.5mm 0;
        }

        .divider-mid {
            display: flex;
            align-items: center;
            gap: 3mm;
            width: 60%;
            margin: 1.2mm 0;
        }

        .divider-mid hr {
            flex: 1;
            border: none;
            border-top: .4pt solid #c9a84c88;
        }

        .divider-mid .diamond {
            width: 2mm;
            height: 2mm;
            background: #c9a84c;
            transform: rotate(45deg);
            flex-shrink: 0;
        }

        /* ── Certificate title ── */
        .cert-title {
            font-family: 'Cinzel Decorative', serif;
            font-size: 16pt;
            font-weight: 700;
            color: #8b6914;
            letter-spacing: .1em;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .cert-subtitle {
            font-family: 'Cinzel', serif;
            font-size: 5.5pt;
            color: #9a7840;
            letter-spacing: .28em;
            text-transform: uppercase;
            margin-top: 1mm;
        }

        /* ── Present text ── */
        .present-text {
            font-family: 'EB Garamond', serif;
            font-size: 6.5pt;
            color: #5a4020;
            letter-spacing: .15em;
            text-transform: uppercase;
            font-style: italic;
            margin: 1.5mm 0 .8mm;
        }

        /* ── Student name ── */
        .student-name {
            font-family: 'Cinzel', serif;
            font-size: 18pt;
            font-weight: 600;
            color: #2d1f0e;
            letter-spacing: .05em;
            border-bottom: .6pt solid #c9a84c88;
            padding-bottom: 1mm;
            margin-bottom: 1mm;
            min-width: 80mm;
        }

        .admission-no {
            font-family: 'EB Garamond', serif;
            font-size: 5.5pt;
            color: #7a6040;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        /* ── Body text ── */
        .body-text {
            font-family: 'EB Garamond', serif;
            font-size: 7.5pt;
            color: #3d2b10;
            line-height: 1.65;
            max-width: 85%;
            margin: 2mm 0 1.5mm;
        }

        /* ── Award badge ── */
        .award-badge {
            display: inline-flex;
            align-items: center;
            gap: 2mm;
            background: linear-gradient(135deg, #f9f0d8, #f0e0b0);
            border: .7pt solid #c9a84c;
            border-radius: 1mm;
            padding: 1mm 5mm;
            margin: 1mm 0;
        }

        .award-badge .badge-text {
            font-family: 'Cinzel', serif;
            font-size: 6.5pt;
            font-weight: 700;
            color: #7a5210;
            letter-spacing: .1em;
        }

        /* ── Grade pills ── */
        .grade-row {
            display: flex;
            gap: 4mm;
            justify-content: center;
            margin: .8mm 0 1.5mm;
        }

        .grade-pill {
            font-family: 'EB Garamond', serif;
            font-size: 5.5pt;
            color: #5a4020;
            border: .3pt solid #c9a84c88;
            border-radius: .5mm;
            padding: .5mm 2.5mm;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        /* ── Signatures ── */
        .sig-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            width: 90%;
            gap: 5mm;
            margin-top: 1mm;
        }

        .sig-block {
            text-align: center;
            flex: 1;
        }

        .sig-image {
            max-height: 8mm;
            max-width: 22mm;
            object-fit: contain;
            margin-bottom: 1mm;
        }

        .sig-line {
            width: 100%;
            height: .4pt;
            background: #b8972a88;
            margin-bottom: 1mm;
        }

        .sig-name {
            font-family: 'Cinzel', serif;
            font-size: 5.5pt;
            color: #4a3520;
            letter-spacing: .08em;
            text-transform: uppercase;
            font-weight: 600;
        }

        .sig-role {
            font-family: 'EB Garamond', serif;
            font-size: 5pt;
            color: #7a6040;
            font-style: italic;
            margin-top: .3mm;
        }

        /* ── Seal center ── */
        .seal-block {
            text-align: center;
            flex: 0 0 24mm;
        }

        .seal-ring {
            width: 18mm;
            height: 18mm;
            border-radius: 50%;
            border: 1pt solid #b8972a;
            background: linear-gradient(135deg, #f9f0d8, #f0e0b0);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1mm;
        }

        .seal-text {
            font-family: 'Cinzel', serif;
            font-size: 4pt;
            color: #7a5210;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        /* ── Bottom stamps ── */
        .date-stamp {
            position: absolute;
            bottom: 14mm;
            right: 22mm;
            text-align: right;
        }

        .cert-no-stamp {
            position: absolute;
            bottom: 14mm;
            left: 22mm;
            text-align: left;
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
            letter-spacing: .08em;
        }

        /* ── Side decorative lines ── */
        .side-line {
            position: absolute;
            top: 18mm;
            bottom: 18mm;
            width: .4pt;
            background: linear-gradient(to bottom, transparent, #c9a84c55 20%, #c9a84c55 80%, transparent);
        }

        .side-left {
            left: 28mm;
        }

        .side-right {
            right: 28mm;
        }

        /* ── Print control (screen only) ── */
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
                box-shadow: 0 8px 40px rgba(0, 0, 0, .7), 0 0 0 1px rgba(212, 175, 55, .2);
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
                page-break-after: always;
            }
        }
    </style>
</head>

<body>

    <!-- Print bar -->
    <div class="print-bar">
        <span>
            ✦ <span class="count">{{ $students->count() }}</span> Certificate{{ $students->count() !== 1 ? 's' : '' }}
            Ready
        </span>
        <div>
            <button class="btn-print" onclick="window.print()">⎙ &nbsp;Print / Save PDF</button>
            <button class="btn-close2" onclick="window.close()">✕ Close</button>
        </div>
    </div>

    @php
        $layout = $template->page_layout ?? 'A4_landscape';
        $padTop = $template->top_space ?? 0;
        $padBottom = $template->bottom_space ?? 0;
        $padRight = $template->right_space ?? 0;
        $padLeft = $template->left_space ?? 0;
        $photoSize = $template->photo_size ?? 100;
        $photoStyle = $template->photo_style ?? 'square';
        $printDate = $request->print_date ?? now()->toDateString();
        $printDateFmt = \Carbon\Carbon::parse($printDate)->format('d F Y');

        // School name from config — fallback to env or generic
        $schoolName = config('app.school_name', env('SCHOOL_NAME', 'Your School Name'));
        $schoolTagline = config('app.school_tagline', env('SCHOOL_TAGLINE', 'Knowledge · Character · Excellence'));

        // Corner SVG path (reusable)
        $cornerSvg = '<svg viewBox="0 0 60 60"><g stroke="#b8972a" stroke-width=".8" fill="none">
        <path d="M2 2 L28 2 L28 6 L6 6 L6 28 L2 28 Z" fill="#d4af3715"/>
        <path d="M10 10 L22 10 L22 14 L14 14 L14 22 L10 22 Z" stroke-width=".5"/>
        <circle cx="28" cy="28" r="4" fill="#d4af3730"/>
        <circle cx="28" cy="28" r="2" fill="#b8972a" stroke="none"/>
        <line x1="32" y1="28" x2="50" y2="28" stroke-width=".4"/>
        <line x1="28" y1="32" x2="28" y2="50" stroke-width=".4"/>
        <circle cx="4" cy="4" r="2" fill="#b8972a" stroke="none"/>
    </g></svg>';
    @endphp

    @foreach ($students as $student)
        @php
            // Build personalised content from template
            $content = $template->content;
            $content = str_replace('{name}', trim($student->name . ' ' . $student->last_name), $content);
            $content = str_replace('{first_name}', $student->name ?? '', $content);
            $content = str_replace('{last_name}', $student->last_name ?? '', $content);
            $content = str_replace('{class}', $student->class_name ?? '', $content);
            $content = str_replace('{section}', $student->section_name ?? '', $content);
            $content = str_replace('{roll}', $student->roll_number ?? '', $content);
            $content = str_replace('{admission_no}', $student->admission_number ?? '', $content);
            $content = str_replace('{gender}', ucfirst($student->gender ?? ''), $content);
            $content = str_replace('{mobile}', $student->mobileno ?? '', $content);
            $content = str_replace(
                '{father_name}',
                trim(($student->father_name ?? '') . ' ' . ($student->father_last_name ?? '')),
                $content,
            );
            $content = str_replace('{date}', $printDateFmt, $content);
            $content = str_replace('{print_date}', $printDateFmt, $content);
            $content = str_replace('{school_name}', $schoolName, $content);

            $studentFullName = trim($student->name . ' ' . $student->last_name);
        @endphp

        <div class="certificate-page">

            <!-- Background seal watermark -->
            <svg class="bg-seal" viewBox="0 0 200 200">
                <circle cx="100" cy="100" r="95" fill="none" stroke="#8b6914" stroke-width="2" />
                <circle cx="100" cy="100" r="85" fill="none" stroke="#8b6914" stroke-width="1" />
                <circle cx="100" cy="100" r="68" fill="none" stroke="#8b6914" stroke-width=".5" />
                <g stroke="#8b6914" stroke-width=".8">
                    <line x1="100" y1="10" x2="100" y2="32" />
                    <line x1="100" y1="168" x2="100" y2="190" />
                    <line x1="10" y1="100" x2="32" y2="100" />
                    <line x1="168" y1="100" x2="190" y2="100" />
                    <line x1="29" y1="29" x2="44" y2="44" />
                    <line x1="156" y1="156" x2="171" y2="171" />
                    <line x1="171" y1="29" x2="156" y2="44" />
                    <line x1="29" y1="171" x2="44" y2="156" />
                </g>
                <polygon points="100,28 108,86 162,86 117,118 133,174 100,142 67,174 83,118 38,86 92,86" fill="none"
                    stroke="#8b6914" stroke-width="1.2" />
                <text x="100" y="106" text-anchor="middle" font-family="Cinzel,serif" font-size="9" fill="#8b6914"
                    letter-spacing="3">EXCELLENCE</text>
                <text x="100" y="118" text-anchor="middle" font-family="Cinzel,serif" font-size="6" fill="#8b6914"
                    letter-spacing="2">INTEGRITY</text>
            </svg>

            <!-- Borders -->
            <div class="border-outer"></div>
            <div class="border-inner"></div>
            <div class="border-inner2"></div>

            <!-- Side decorative lines -->
            <div class="side-line side-left"></div>
            <div class="side-line side-right"></div>

            <!-- Corner ornaments -->
            <div class="corner tl">{!! $cornerSvg !!}</div>
            <div class="corner tr">{!! $cornerSvg !!}</div>
            <div class="corner bl">{!! $cornerSvg !!}</div>
            <div class="corner br">{!! $cornerSvg !!}</div>

            <!-- Main content -->
            <div class="cert-content"
                style="padding-top:{{ 18 + $padTop }}mm;padding-bottom:{{ 16 + $padBottom }}mm;padding-left:{{ 22 + $padLeft }}mm;padding-right:{{ 22 + $padRight }}mm;">

                {{-- School header --}}
                <div class="school-logo-row">
                    <div class="logo-wrap">
                        @if ($template->logo_image)
                            <img src="{{ asset('storage/' . $template->logo_image) }}" alt="Logo">
                        @else
                            <div class="logo-initials">{{ strtoupper(substr($schoolName, 0, 2)) }}</div>
                        @endif
                    </div>
                    <div class="school-name-block">
                        <div class="school-name">{{ $schoolName }}</div>
                        <div class="school-tagline">{{ $schoolTagline }}</div>
                    </div>
                </div>

                <div class="divider-gold"></div>

                {{-- Certificate title (from template name, or use content) --}}
                <div class="cert-title">{{ $template->name }}</div>
                <div class="cert-subtitle">Academic Excellence &nbsp;·&nbsp;
                    {{ \Carbon\Carbon::parse($printDate)->format('Y') }}</div>

                <div class="divider-mid">
                    <hr>
                    <div class="diamond"></div>
                    <hr>
                </div>

                <div class="present-text">This is to proudly certify that</div>

                {{-- Student name --}}
                <div class="student-name">{{ $studentFullName }}</div>
                <div class="admission-no">
                    @if ($student->admission_number)
                        Admission No: {{ $student->admission_number }} &nbsp;·&nbsp;
                    @endif
                    Class: {{ $student->class_name ?? '' }}
                    @if ($student->section_name)
                        &nbsp;·&nbsp; Section: {{ $student->section_name }}
                    @endif
                </div>

                {{-- Certificate body (from Summernote editor) --}}
                @if ($content && strip_tags($content) !== '')
                    <div class="body-text">{!! $content !!}</div>
                @else
                    <div class="body-text">
                        has successfully completed the academic year
                        <em>{{ \Carbon\Carbon::parse($printDate)->format('Y') }}</em>
                        with outstanding dedication and scholarly merit, consistently demonstrating
                        exceptional performance across all subjects and upholding the values of this
                        institution with distinction.
                    </div>
                @endif

                <div class="divider-gold"></div>

                {{-- Signatures --}}
                <div class="sig-row">
                    <div class="sig-block">
                        @if ($template->signature_image)
                            <img src="{{ asset('storage/' . $template->signature_image) }}" class="sig-image"
                                alt="Signature">
                        @else
                            <div style="height:8mm;"></div>
                        @endif
                        <div class="sig-line"></div>
                        <div class="sig-name">Class Teacher</div>
                        <div class="sig-role">Signature &amp; Stamp</div>
                    </div>

                    {{-- Center seal --}}
                    <div class="seal-block">
                        <div class="seal-ring">
                            <svg width="12mm" height="12mm" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15" fill="none" stroke="#b8972a"
                                    stroke-width="1" />
                                <circle cx="18" cy="18" r="11" fill="none" stroke="#c9a84c"
                                    stroke-width=".5" />
                                <polygon points="18,5 20.5,13 29,13 22,18.5 24.5,27 18,22 11.5,27 14,18.5 7,13 15.5,13"
                                    fill="#d4af37" opacity=".9" />
                            </svg>
                        </div>
                        <div class="seal-text">Official Seal</div>
                    </div>

                    <div class="sig-block">
                        <div style="height:8mm;"></div>
                        <div class="sig-line"></div>
                        <div class="sig-name">Principal</div>
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
                <div class="stamp-val">
                    {{ strtoupper(substr($schoolName, 0, 3)) }}/{{ \Carbon\Carbon::parse($printDate)->format('Y') }}/{{ str_pad($student->id, 4, '0', STR_PAD_LEFT) }}
                </div>
            </div>

        </div>
    @endforeach

</body>

</html>
