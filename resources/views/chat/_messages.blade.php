{{--
    Partial: chat/_messages.blade.php
    Variable: $getChat  (Collection of ChatModel instances)
--}}
@forelse($getChat as $value)
    @include('chat._single', ['value' => $value])
@empty
    <div class="text-center text-muted py-5">
        <i class="bi bi-chat-square-dots" style="font-size:2rem; opacity:.35;"></i>
        <p class="mt-2 small">No messages yet. Say hello!</p>
    </div>
@endforelse
