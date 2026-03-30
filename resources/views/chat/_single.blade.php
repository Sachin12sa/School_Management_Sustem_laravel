{{--
    Partial: chat/_single.blade.php
    Variable: $value  (ChatModel instance with sender & receiver loaded)
--}}
@if ($value->sender_id == Auth::user()->id)

    {{-- ── Outgoing bubble ───────────────────────────────────────── --}}
    <div class="d-flex flex-row justify-content-end mb-3 bubble-out">
        <div style="max-width: 70%;">
            <div class="bubble-body">
                @if (!empty($value->message))
                    <p class="small mb-0" style="word-break: break-word;">{{ $value->message }}</p>
                @endif
                @if (!empty($value->file))
                    @php $fileUrl = $value->getFile(); @endphp
                    @if ($fileUrl)
                        <div class="{{ !empty($value->message) ? 'mt-2' : '' }}">
                            <a href="{{ $fileUrl }}" target="_blank" class="text-white-50 text-decoration-none small">
                                <i class="bi bi-paperclip me-1"></i>Attachment
                            </a>
                        </div>
                    @endif
                @endif
            </div>
            <p class="bubble-time text-end">
                {{ \Carbon\Carbon::parse($value->created_at)->format('h:i A') }}
            </p>
        </div>
    </div>
@else
    {{-- ── Incoming bubble ───────────────────────────────────────── --}}
    <div class="d-flex flex-row justify-content-start mb-3 bubble-in">
        <img src="{{ $value->sender->getProfile() }}" alt="{{ $value->sender->name }}"
            class="rounded-circle me-2 mt-1 flex-shrink-0"
            style="width:36px; height:36px; object-fit:cover; border:2px solid #e9ecef;">
        <div style="max-width: 70%;">
            <div class="bubble-body">
                @if (!empty($value->message))
                    <p class="small mb-0 text-dark" style="word-break: break-word;">{{ $value->message }}</p>
                @endif
                @if (!empty($value->file))
                    @php $fileUrl = $value->getFile(); @endphp
                    @if ($fileUrl)
                        <div class="{{ !empty($value->message) ? 'mt-2' : '' }}">
                            <a href="{{ $fileUrl }}" target="_blank"
                                class="text-primary text-decoration-none small">
                                <i class="bi bi-paperclip me-1"></i>Attachment
                            </a>
                        </div>
                    @endif
                @endif
            </div>
            <p class="bubble-time">
                {{ \Carbon\Carbon::parse($value->created_at)->format('h:i A') }}
            </p>
        </div>
    </div>

@endif
