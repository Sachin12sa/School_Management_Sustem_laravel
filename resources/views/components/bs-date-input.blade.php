{{--
╔══════════════════════════════════════════════════════════════════╗
║  B.S. Date Input Component — powered by Nepali Datepicker v5    ║
║  File: resources/views/components/bs-date-input.blade.php       ║
║                                                                  ║
║  REQUIRES: nepali.datepicker.v5.0.6 loaded in layouts/app.blade ║
║                                                                  ║
║  USAGE:                                                          ║
║    <x-bs-date-input name="due_date" :value="$issue->due_date" /> ║
║                                                                  ║
║    With label and required:                                      ║
║    <x-bs-date-input                                              ║
║        name="due_date"                                           ║
║        label="Due Date"                                          ║
║        :value="$issue->due_date ?? ''"                           ║
║        :required="true" />                                       ║
║                                                                  ║
║  HOW IT WORKS:                                                   ║
║    1. User picks from calendar popup → onChange fires → converts ║
║    2. User types manually → blur fires → converts                ║
║    3. Either way, hidden field always has AD before form submits ║
╚══════════════════════════════════════════════════════════════════╝
--}}

@props([
    'name',
    'label' => '',
    'value' => null,
    'required' => false,
    'readonly' => false,
    'minDate' => '',
    'maxDate' => '',
    'helpText' => '',
    'container' => '',
])

@php
    $uid = 'bsdp_' . preg_replace('/[^a-zA-Z0-9]/', '_', $name) . '_' . substr(md5(uniqid()), 0, 6);
    $hiddenId = 'ad_' . $uid;

    $bsDisplay = '';
    $adValue = '';

    if ($value && trim((string) $value) !== '' && $value !== '0000-00-00') {
        $adClean = substr((string) $value, 0, 10);
        $adValue = $adClean;
        $bs = \App\Helpers\NepaliCalendar::adToBs($adClean);
        $bsDisplay = sprintf('%d-%02d-%02d', $bs['year'], $bs['month'], $bs['day']);
    }
@endphp

<div class="bs-date-wrapper">

    @if ($label)
        <label for="{{ $uid }}" class="form-label fw-semibold small">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
            <span class="text-muted fw-normal" style="font-size:.68rem;">&nbsp;(B.S.)</span>
        </label>
    @endif

    <div class="input-group">
        <input type="text" id="{{ $uid }}" class="form-control" placeholder="YYYY-MM-DD"
            value="{{ $bsDisplay }}" autocomplete="off" {{ $readonly ? 'readonly' : '' }}
            data-ad-target="{{ $hiddenId }}" data-required="{{ $required ? 'true' : 'false' }}">
        @if (!$readonly)
            <span class="input-group-text" style="cursor:pointer;"
                onclick="document.getElementById('{{ $uid }}').focus()">
                <i class="bi bi-calendar3"></i>
            </span>
        @endif
    </div>

    {{-- Hidden AD field submitted to controller --}}
    <input type="hidden" id="{{ $hiddenId }}" name="{{ $name }}" value="{{ $adValue }}">

    {{-- Hidden BS string field — exact BS the user typed/picked.
         Use this for display so PHP does NOT need to re-convert AD→BS,
         which avoids the PHP/JS table mismatch that causes a 19-day display drift. --}}
    <input type="hidden" id="{{ $hiddenId }}_bs" name="{{ $name }}_bs_raw" value="{{ $bsDisplay }}">

    {{-- Feedback line --}}
    <div id="{{ $uid }}_info" style="font-size:.72rem;min-height:1.1rem;margin-top:.25rem;">
        @if ($adValue)
            <span class="text-success">
                <i class="bi bi-check-circle me-1"></i>AD: {{ $adValue }}
            </span>
        @endif
    </div>

    @if ($helpText)
        <div class="form-text">{{ $helpText }}</div>
    @endif
</div>

<script>
    (function() {
        var UID = '{{ $uid }}';
        var HIDDEN_ID = '{{ $hiddenId }}';
        var REQUIRED = {{ $required ? 'true' : 'false' }};

        // ── Core conversion ────────────────────────────────────────────
        // Correct API (from official docs):
        //   NepaliFunctions.BS2AD("2082-01-15", "YYYY-MM-DD") → "2025-04-28"
        //   NepaliFunctions.BS.ValidateDate("2082-01-15", "YYYY-MM-DD") → true/false
        function convertAndStore(bsStr) {
            var el = document.getElementById(UID);
            var hidden = document.getElementById(HIDDEN_ID);
            var info = document.getElementById(UID + '_info');

            bsStr = (bsStr || '').trim();

            if (!bsStr) {
                hidden.value = '';
                var bsClear = document.getElementById(HIDDEN_ID + '_bs');
                if (bsClear) bsClear.value = '';
                el.classList.remove('is-valid', 'is-invalid');
                if (info) info.innerHTML = '';
                return;
            }

            // Normalise separators
            bsStr = bsStr.replace(/\//g, '-');

            if (!/^\d{4}-\d{2}-\d{2}$/.test(bsStr)) {
                hidden.value = '';
                el.classList.add('is-invalid');
                el.classList.remove('is-valid');
                if (info) info.innerHTML =
                    '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>Format: YYYY-MM-DD (e.g. 2082-01-15)</span>';
                return;
            }

            // Use library's own validator — eliminates the need for a manual range check
            if (!NepaliFunctions.BS.ValidateDate(bsStr, 'YYYY-MM-DD')) {
                hidden.value = '';
                el.classList.add('is-invalid');
                el.classList.remove('is-valid');
                if (info) info.innerHTML =
                    '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>Invalid B.S. date</span>';
                return;
            }

            try {
                // Correct API: pass string + format → returns AD string in same format
                var adStr = NepaliFunctions.BS2AD(bsStr, 'YYYY-MM-DD');

                if (!adStr || typeof adStr !== 'string') throw new Error('No result');

                hidden.value = adStr.substring(0, 10);

                // Also store the original BS string so PHP display matches exactly
                var bsHidden = document.getElementById(HIDDEN_ID + '_bs');
                if (bsHidden) bsHidden.value = bsStr;

                el.classList.add('is-valid');
                el.classList.remove('is-invalid');
                if (info) info.innerHTML =
                    '<span class="text-success"><i class="bi bi-check-circle me-1"></i>AD: ' + hidden.value +
                    '</span>';
            } catch (e) {
                hidden.value = '';
                el.classList.add('is-invalid');
                el.classList.remove('is-valid');
                if (info) info.innerHTML =
                    '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>Could not convert — check date</span>';
            }
        }

        // ── Pre-submit guard ───────────────────────────────────────────
        // If the hidden field is empty when the form submits, block it.
        function attachSubmitGuard(el) {
            var form = el.closest('form');
            if (!form || form._bsDateGuarded) return;
            form._bsDateGuarded = true;

            form.addEventListener('submit', function(e) {
                // Find all bs-date-wrapper required hidden fields in this form
                var allHidden = form.querySelectorAll('input[type="hidden"][id^="ad_bsdp_"]');
                var hasError = false;

                allHidden.forEach(function(h) {
                    // Find the matching visible input
                    var visId = h.id.replace(/^ad_/, '');
                    var visEl = document.getElementById(visId);
                    var isReq = visEl && visEl.getAttribute('data-required') === 'true';

                    if (isReq && !h.value) {
                        hasError = true;
                        if (visEl) {
                            visEl.classList.add('is-invalid');
                            // Try to convert whatever is in the visible input right now
                            convertAndStore(visEl.value);
                            // If still empty after conversion, mark invalid
                            if (!h.value) {
                                var info = document.getElementById(visId + '_info');
                                if (info) info.innerHTML =
                                    '<span class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Please select or type a valid B.S. date</span>';
                            }
                        }
                    }
                });

                if (hasError) {
                    e.preventDefault();
                    e.stopPropagation();
                    // Scroll to first invalid date input
                    var first = form.querySelector('.bs-date-wrapper .is-invalid');
                    if (first) first.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            });
        }

        // ── Initialise ─────────────────────────────────────────────────
        function initPicker() {
            var el = document.getElementById(UID);
            if (!el) return;

            var opts = {
                // Fires when user picks from the calendar popup
                onChange: function(bsDateStr) {
                    convertAndStore(bsDateStr);
                },
            };

            @if ($minDate)
                opts.minDate = '{{ $minDate }}';
            @endif
            @if ($maxDate)
                opts.maxDate = '{{ $maxDate }}';
            @endif
            @if ($container)
                opts.container = '{{ $container }}';
            @endif

            el.NepaliDatePicker(opts);

            // Also handle manual typing — convert on blur (when user leaves the field)
            el.addEventListener('blur', function() {
                convertAndStore(this.value);
            });

            // Real-time format hint while typing (no conversion until blur)
            el.addEventListener('input', function() {
                var v = this.value.trim();
                var info = document.getElementById(UID + '_info');
                if (!v) {
                    document.getElementById(HIDDEN_ID).value = '';
                    this.classList.remove('is-valid', 'is-invalid');
                    if (info) info.innerHTML = '';
                } else if (v.length === 10 && /^\d{4}-\d{2}-\d{2}$/.test(v)) {
                    // Full date typed — convert immediately
                    convertAndStore(v);
                }
            });

            // Pre-fill: if a BS value is already in the visible input on load, convert it
            @if ($bsDisplay)
                convertAndStore(el.value);
            @endif

            // Attach submit guard to the parent form
            attachSubmitGuard(el);
        }

        if (typeof NepaliFunctions !== 'undefined') {
            initPicker();
        } else {
            window.addEventListener('load', initPicker);
        }
    })();
</script>
