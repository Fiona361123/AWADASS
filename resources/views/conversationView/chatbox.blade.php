<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat</title>
    <link rel="stylesheet" href="{{ mix('css/chat.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/chat.js') }}" defer></script>
</head>
<body>

{{-- Sidebar --}}
<div class="sidebar">
    <div class="sidebar-header">Messages</div>

    @forelse($conversations as $conv)
        @php
            $other = $conv->seeker_id === auth()->id() ? $conv->employer : $conv->seeker;
            $last  = $conv->messages->first();
        @endphp
        <a href="{{ route('chat.show', $conv) }}"
           class="conversation-item {{ isset($conversation) && $conversation->id === $conv->id ? 'active' : '' }}">
            <div class="conv-name">{{ $other->name }}</div>
            <div class="conv-preview">{{ $last ? $last->body : 'No messages yet' }}</div>
        </a>
    @empty
        <div style="padding:20px; color:#aaa; font-size:13px;">No conversations yet.</div>
    @endforelse
</div>

{{-- Chat area --}}
<div class="chat-area">

    @if(isset($conversation) && $conversation)

        <div class="chat-header">
            {{ $conversation->seeker_id === auth()->id()
                ? $conversation->employer->name
                : $conversation->seeker->name }}
        </div>

        <div class="messages-container" id="messages">
            @forelse($messages as $msg)
                @php $isMine = $msg->sender_id === auth()->id(); @endphp
                <div class="message-wrapper {{ $isMine ? 'mine' : 'theirs' }}">
                    @if(!$isMine)
                        <div class="sender-name">{{ $msg->sender->name }}</div>
                    @endif
                    <div class="bubble">{{ $msg->body }}</div>
                    <div class="message-meta">{{ $msg->created_at->format('h:i A') }}</div>
                </div>
                @empty
            @endforelse
        </div>

        <div class="input-area">
            <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off">
            <button id="send-btn">Send</button>
        </div>

    @else

        <div class="empty-state">
            <span>Select a conversation to start chatting</span>
        </div>

    @endif

</div>

{{-- Pass PHP variables to chat.js --}}
@if(isset($conversation) && $conversation)
<script>
    window.conversationId = {{ $conversation->id }};
    window.currentUserId  = {{ auth()->id() }};
</script>
@endif

</body>
</html>