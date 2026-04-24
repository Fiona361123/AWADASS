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

    <div class="chat-page-wrapper">
        <header class="chat-top-nav">
            @if(auth()->user()->role === 'employer')
                <a href="{{ route('dashboard') }}" class="back-link">
                    &larr; Back
                </a>
            @else
                <a href="{{ route('jobs.index') }}" class="back-link">
                    &larr; Back
                </a>
            @endif
            <h1 class="chat-title">Inbox</h1>
        </header>

        <div class="chat-main-body">
            <aside class="sidebar">
                <div class="sidebar-header">Recent Chats</div>

                <div id="conversation-list">
                    @forelse($conversations as $conv)
                        @php
                            $other = $conv->getOtherParticipant(auth()->user());
                            $last = $conv->messages->last();
                        @endphp
                        <a href="{{ route('chat.show', $conv) }}"
                            class="conversation-item {{ (isset($conversation) && $conversation->id === $conv->id) ? 'active' : '' }}">
                            <div class="conv-name">{{ $other->name }}</div>
                            <div class="conv-preview">
                                {{ $last ? Str::limit($last->body, 30) : 'No messages yet' }}
                            </div>
                        </a>
                    @empty
                        <div style="padding: 20px; color: #aaa; font-size: 13px;">No conversations yet.</div>
                    @endforelse
                </div>
            </aside>

            <main class="chat-area">
                @if(isset($conversation))
                    <div class="chat-header">
                        {{ $conversation->getOtherParticipant(auth()->user())->name }}
                    </div>

                    <div class="messages-container" id="messages">
                        @foreach($messages as $msg)
                            @php $isMe = $msg->sender_id === auth()->id(); @endphp
                            <div class="message-wrapper {{ $isMe ? 'sent' : 'received' }}">
                                @if(!$isMe)
                                    <div class="sender-name">{{ $msg->sender->name }}</div>
                                @endif
                                <div class="bubble">{{ $msg->body }}</div>
                                <div class="message-meta">{{ $msg->created_at->format('h:i A') }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="input-area">
                        <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off">
                        <button id="send-btn">Send</button>
                    </div>
                @else
                    <div class="empty-state">
                        <span>Select a chat to start messaging</span>
                    </div>
                @endif
            </main>
        </div>
    </div>

    @if(isset($conversation))
        <script>
            window.conversationId = {{ $conversation->id ?? 'null' }};
            window.currentUserId = {{ auth()->id() }};
            window.targetEmployerId = {{ $conversation->employer_id }};
        </script>
    @endif

</body>

</html>