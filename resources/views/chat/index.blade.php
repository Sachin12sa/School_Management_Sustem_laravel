@php
    // Build the base URL depending on the logged-in user's role
$baseUrl = match (Auth::user()->user_type) {
    2 => 'teacher/chat',
    3 => 'student/chat',
    4 => 'parent/chat',
    5 => 'accountant/chat',
    6 => 'librarian/chat',
    default => 'admin/chat',
    };
@endphp

@extends('layouts.app')

@section('style')
    <style>
        /* ── Layout ──────────────────────────────────────────────────────────── */
        .chat-container {
            height: calc(100vh - 120px);
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            display: flex;
        }

        /* ── Sidebar ─────────────────────────────────────────────────────────── */
        .chat-sidebar {
            width: 320px;
            min-width: 280px;
            border-right: 1px solid #edf2f6;
            background: #fafbfc;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .chat-sidebar-header {
            padding: 18px 16px 14px;
            border-bottom: 1px solid #edf2f6;
            background: #fff;
        }

        .chat-users-list {
            flex: 1;
            overflow-y: auto;
        }

        .chat-users-list::-webkit-scrollbar {
            width: 5px;
        }

        .chat-users-list::-webkit-scrollbar-thumb {
            background: #d1d9e0;
            border-radius: 10px;
        }

        /* contact row */
        .chat-contact {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-bottom: 1px solid #f0f4f8;
            transition: background 0.15s;
            text-decoration: none;
            color: inherit;
        }

        .chat-contact:hover {
            background: #f0f5f1;
        }

        .chat-contact.active {
            background: #198754;
            color: #fff;
        }

        .chat-contact.active .contact-name {
            color: #fff !important;
        }

        .chat-contact.active .contact-meta {
            color: rgba(255, 255, 255, .65) !important;
        }

        .chat-contact.active .contact-time {
            color: rgba(255, 255, 255, .55) !important;
        }

        .contact-avatar {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .12);
            flex-shrink: 0;
        }

        .contact-info {
            flex: 1;
            min-width: 0;
            margin-left: 12px;
        }

        .contact-name {
            font-weight: 600;
            font-size: .88rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #1a2332;
        }

        .contact-meta {
            font-size: .78rem;
            color: #8a9bb0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 2px;
        }

        .contact-time {
            font-size: .72rem;
            color: #b0bec5;
            white-space: nowrap;
        }

        /* search */
        .sidebar-search {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            font-size: .85rem;
            background: #f5f7f9;
            transition: border-color .2s, box-shadow .2s;
        }

        .sidebar-search:focus {
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, .15);
            background: #fff;
            outline: none;
        }

        /* ── Main chat area ───────────────────────────────────────────────────── */
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: #fff;
        }

        .chat-header {
            padding: 14px 20px;
            border-bottom: 1px solid #edf2f6;
            background: #fff;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .04);
            z-index: 1;
        }

        .chat-header-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e9ecef;
        }

        .online-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: #198754;
            border-radius: 50%;
            margin-right: 4px;
        }

        /* ── Messages ─────────────────────────────────────────────────────────── */
        .chat-box {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f8fafb;
        }

        .chat-box::-webkit-scrollbar {
            width: 5px;
        }

        .chat-box::-webkit-scrollbar-thumb {
            background: #d1d9e0;
            border-radius: 10px;
        }

        /* Bubbles */
        .bubble-out .bubble-body {
            background: #198754;
            color: #fff;
            border-radius: 18px 18px 4px 18px;
        }

        .bubble-out .bubble-body p {
            color: #fff !important;
        }

        .bubble-out .bubble-body a {
            color: rgba(255, 255, 255, .85) !important;
        }

        .bubble-in .bubble-body {
            background: #fff;
            border: 1px solid #e8edf2;
            border-radius: 18px 18px 18px 4px;
        }

        .bubble-body {
            padding: 10px 14px;
            max-width: 480px;
            display: inline-block;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }

        .bubble-time {
            font-size: .72rem;
            color: #a0aec0;
            margin-top: 4px;
        }

        /* ── Footer / Input ────────────────────────────────────────────────────── */
        .chat-footer {
            padding: 14px 20px;
            border-top: 1px solid #edf2f6;
            background: #fff;
        }

        .chat-input {
            border-radius: 24px;
            padding: 10px 18px;
            border: 1px solid #e2e8f0;
            resize: none;
            font-size: .9rem;
            transition: border-color .2s, box-shadow .2s;
        }

        .chat-input:focus {
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, .18);
            outline: none;
        }

        .btn-send {
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: transform .15s;
        }

        .btn-send:hover {
            transform: scale(1.08);
        }

        .btn-attach {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            cursor: pointer;
            border: 1px solid #e2e8f0;
            background: #f5f7f9;
            color: #6c757d;
            transition: background .15s;
        }

        .btn-attach:hover {
            background: #e9ecef;
        }

        /* file preview badge */
        #file-preview-badge {
            display: none;
            background: #e8f5e9;
            border: 1px solid #c8e6c9;
            border-radius: 8px;
            padding: 4px 10px;
            font-size: .78rem;
            color: #2e7d32;
            align-items: center;
            gap: 6px;
        }

        #file-preview-badge .remove-file {
            cursor: pointer;
            color: #c62828;
            font-weight: bold;
        }

        /* ── Empty state ──────────────────────────────────────────────────────── */
        .empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px;
            background: #f8fafb;
        }

        .empty-icon-wrap {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(25, 135, 84, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        /* ── Responsive ───────────────────────────────────────────────────────── */
        @media (max-width: 768px) {
            .chat-container {
                flex-direction: column;
                height: auto;
            }

            .chat-sidebar {
                width: 100%;
                min-width: unset;
                max-height: 260px;
            }
        }
    </style>
@endsection

@section('content')
    <main class="app-main">
        <div class="app-content-header py-3">
            <div class="container-fluid">
                <h4 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-chat-dots-fill text-success me-2"></i>Messages
                </h4>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <div class="chat-container">

                    {{-- ── Sidebar ─────────────────────────────────────────── --}}
                    <div class="chat-sidebar">
                        <div class="chat-sidebar-header">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="fw-bold mb-0 text-dark">Conversations</h6>
                                <button type="button" class="btn btn-sm btn-success rounded-circle shadow-sm"
                                    data-bs-toggle="modal" data-bs-target="#newChatModal" title="New Chat"
                                    style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </div>
                            <input type="text" id="search_user" class="form-control sidebar-search"
                                placeholder="&#128269; Search contacts…">
                        </div>

                        <div class="chat-users-list" id="getSearchUserDynamic">
                            @include('chat._user_list')
                        </div>
                    </div>

                    {{-- ── Main area ───────────────────────────────────────── --}}
                    <div class="chat-main">
                        @if (!empty($getReceiver))
                            {{-- Header --}}
                            <div class="chat-header">
                                <img src="{{ $getReceiver->getProfile() }}" class="chat-header-avatar"
                                    alt="{{ $getReceiver->name }}">
                                <div>
                                    <div class="fw-bold text-dark d-flex align-items-center" style="font-size:.95rem; gap:6px;">
                                        <span>{{ $getReceiver->name }} {{ $getReceiver->last_name }}</span>
                                        <span class="badge bg-secondary" style="font-size:0.6rem; font-weight:normal;">
                                            {{ match($getReceiver->user_type) { 1=>'Admin', 2=>'Teacher', 3=>'Student', 4=>'Parent', 5=>'Accountant', 6=>'Librarian', default=>'User' } }}
                                        </span>
                                    </div>
                                    <small class="text-success">
                                        <span class="online-dot"></span>Online
                                    </small>
                                </div>
                            </div>

                            {{-- Messages --}}
                            <div class="chat-box" id="AppendMessage">
                                @include('chat._messages')
                            </div>

                            {{-- Footer --}}
                            <div class="chat-footer">
                                {{-- File preview --}}
                                <div id="file-preview-badge" class="mb-2 d-inline-flex">
                                    <i class="bi bi-paperclip"></i>
                                    <span id="file-name-text"></span>
                                    <span class="remove-file" onclick="clearFile()">✕</span>
                                </div>

                                <form id="submit_message" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="receiver_id" id="receiver_id" value="{{ $receiver_id }}">

                                    <div class="d-flex align-items-center gap-2">
                                        {{-- Attach button --}}
                                        <label for="chat_file" class="btn-attach mb-0" title="Attach file">
                                            <i class="bi bi-paperclip"></i>
                                        </label>
                                        <input type="file" id="chat_file" name="file_name" style="display:none;"
                                            onchange="showFilePreview(this)">

                                        {{-- Text input --}}
                                        <input type="text" id="clearMessage" name="message"
                                            class="form-control chat-input flex-grow-1" placeholder="Type a message…"
                                            autocomplete="off">

                                        {{-- Send button --}}
                                        <button type="submit" class="btn btn-success btn-send shadow-sm"
                                            id="btn_submit_message" title="Send">
                                            <i class="bi bi-send-fill"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            {{-- Empty state --}}
                            <div class="empty-state">
                                <div class="empty-icon-wrap">
                                    <i class="bi bi-chat-dots text-success" style="font-size:2.8rem;"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-2">Welcome to Messenger</h5>
                                <p class="text-muted" style="max-width:320px;">
                                    Pick a conversation from the left, or search for a contact to start chatting.
                                </p>
                            </div>
                        @endif
                    </div>{{-- .chat-main --}}

                </div>{{-- .chat-container --}}
            </div>
        </div>
        <!-- New Chat Modal -->
        <div class="modal fade" id="newChatModal" tabindex="-1" aria-labelledby="newChatModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold" id="newChatModalLabel">Start New Conversation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="modal_search_user" class="form-control sidebar-search mb-3"
                            placeholder="&#128269; Search by name or email…">
                        <div id="modal_user_results">
                            <p class="text-muted text-center small mt-4">Type a name to search</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script>
        var receiver_id = $('#receiver_id').val();
        var baseUrl = '{{ url($baseUrl) }}';

        /* ── Scroll to bottom ─────────────────────────────────────────────── */
        function scrollDown(animate) {
            var $box = $('#AppendMessage');
            if (!$box.length) return;
            var target = $box.prop('scrollHeight');
            animate ? $box.animate({
                scrollTop: target
            }, 400) : $box.scrollTop(target);
        }

        /* ── File preview badge ───────────────────────────────────────────── */
        function showFilePreview(input) {
            if (input.files && input.files[0]) {
                $('#file-name-text').text(input.files[0].name);
                $('#file-preview-badge').css('display', 'inline-flex');
            }
        }

        function clearFile() {
            $('#chat_file').val('');
            $('#file-preview-badge').hide();
            $('#file-name-text').text('');
        }

        $(document).ready(function() {
            scrollDown(false);

            /* ── Send message ─────────────────────────────────────────────── */
            $('#submit_message').on('submit', function(e) {
                e.preventDefault();

                var msg = $('#clearMessage').val().trim();
                var file = $('#chat_file')[0].files.length;

                if (!msg && !file) return; // nothing to send

                var formData = new FormData(this);
                var $btn = $('#btn_submit_message');
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/submit',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(res) {
                        if (res.status) {
                            $('#AppendMessage').append(res.success);
                            $('#clearMessage').val('');
                            clearFile();
                            scrollDown(true);
                        }
                    },
                    error: function(xhr) {
                        var msg = xhr.responseJSON?.message ||
                            'Error sending message. Please try again.';
                        alert(msg);
                    },
                    complete: function() {
                        $btn.prop('disabled', false).html('<i class="bi bi-send-fill"></i>');
                    }
                });
            });

            /* ── Press Enter to send (Shift+Enter = new line) ─────────────── */
            $('#clearMessage').on('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    $('#submit_message').trigger('submit');
                }
            });

            /* ── Search contacts ──────────────────────────────────────────── */
            var searchTimer;
            $('#search_user').on('input', function() {
                clearTimeout(searchTimer);
                var search = $(this).val();
                searchTimer = setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: baseUrl + '/search_user',
                        data: {
                            _token: '{{ csrf_token() }}',
                            search: search,
                            receiver_id: receiver_id
                        },
                        dataType: 'json',
                        success: function(res) {
                            $('#getSearchUserDynamic').html(res.success);
                        }
                    });
                }, 300); // debounce
            });

            /* ── Poll for new messages every 5 s ─────────────────────────── */
            if (receiver_id) {
                var lastScrollHeight = 0;

                setInterval(function() {
                    $.ajax({
                        type: 'POST',
                        url: baseUrl + '/get_messages',
                        data: {
                            _token: '{{ csrf_token() }}',
                            receiver_id: receiver_id
                        },
                        dataType: 'json',
                        success: function(res) {
                            var $box = $('#AppendMessage');
                            var atBottom = $box.scrollTop() + $box.innerHeight() >= $box.prop(
                                'scrollHeight') - 40;
                            $box.html(res.success);
                            // Only auto-scroll if already near the bottom
                            if (atBottom) scrollDown(false);
                        }
                    });
                }, 5000);
            }
        });
        var modalTimer;
        $('#modal_search_user').on('input', function() {
            clearTimeout(modalTimer);
            var search = $(this).val().trim();
            if (search.length < 1) {
                $('#modal_user_results').html(
                    '<p class="text-muted text-center small mt-4">Type a name to search</p>');
                return;
            }
            modalTimer = setTimeout(function() {
                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/search_all_users',
                    data: {
                        _token: '{{ csrf_token() }}',
                        search: search
                    },
                    dataType: 'json',
                    success: function(res) {
                        $('#modal_user_results').html(res.success);
                    }
                });
            }, 300);
        });
    </script>
@endsection
