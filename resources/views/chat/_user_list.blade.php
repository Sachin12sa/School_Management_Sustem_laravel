{{--
    Partial: chat/_user_list.blade.php
    Variables:
      $getChatUsers   – Collection of ChatModel (latest message per contact)
      $receiver_id    – Currently active chat user ID
--}}
@php
    $baseUrl = match (Auth::user()->user_type) {
        2 => 'teacher/chat',
        3 => 'student/chat',
        4 => 'parent/chat',
        5 => 'accountant/chat',
        6 => 'librarian/chat',
        default => 'admin/chat',
    };
@endphp

@forelse($getChatUsers as $chatRow)
    @php
        // Determine the other person in this conversation
        $chatUser = $chatRow->sender_id == Auth::user()->id ? $chatRow->receiver : $chatRow->sender;
    @endphp

    @if (!empty($chatUser))
        <a href="{{ url($baseUrl) }}?receiver_id={{ $chatUser->id }}"
            class="chat-contact {{ $receiver_id == $chatUser->id ? 'active' : '' }}">

            <img src="{{ $chatUser->getProfile() }}" alt="{{ $chatUser->name }}" class="contact-avatar">

            <div class="contact-info">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="contact-name">
                        {{ $chatUser->name }} {{ $chatUser->last_name }}
                        <span class="badge bg-secondary ms-1" style="font-size:0.6rem; font-weight:normal;">
                            {{ match($chatUser->user_type) { 1=>'Admin', 2=>'Teacher', 3=>'Student', 4=>'Parent', 5=>'Accountant', 6=>'Librarian', default=>'User' } }}
                        </span>
                    </span>
                    <span class="contact-time ms-2">
                        {{ \Carbon\Carbon::parse($chatRow->created_at)->diffForHumans(null, true) }}
                    </span>
                </div>
                <div class="contact-meta">
                    @if ($chatRow->sender_id == Auth::user()->id)
                        <i class="bi bi-check2-all me-1"></i>
                    @endif
                    {{ $chatRow->message ? \Illuminate\Support\Str::limit($chatRow->message, 35) : 'Attachment sent' }}
                </div>
            </div>
        </a>
    @endif

@empty
    <div class="text-center text-muted py-5 px-3">
        <i class="bi bi-people" style="font-size:1.8rem; opacity:.35;"></i>
        <p class="mt-2 small">No conversations yet.</p>
    </div>
@endforelse
