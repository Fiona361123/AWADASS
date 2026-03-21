<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 300px;
            background: #fff;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 20px;
            font-size: 18px;
            font-weight: 600;
            border-bottom: 1px solid #e0e0e0;
            color: #111;
        }

        .conversation-item {
            padding: 14px 20px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            display: block;
        }

        .conversation-item:hover { background: #f5f5f5; }
        .conversation-item.active { background: #e8f0fe; }

        .conv-name {
            font-weight: 600;
            font-size: 14px;
            color: #111;
        }

        .conv-preview {
            font-size: 12px;
            color: #888;
            margin-top: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Chat area */
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .chat-header {
            padding: 16px 24px;
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 600;
            font-size: 16px;
            color: #111;
        }

        /* Messages */
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .message-wrapper {
            display: flex;
            flex-direction: column;
        }

        .message-wrapper.mine { align-items: flex-end; }
        .message-wrapper.theirs { align-items: flex-start; }

        .bubble {
            max-width: 65%;
            padding: 10px 16px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.5;
            word-break: break-word;
        }

        .mine .bubble {
            background: #4f46e5;
            color: #fff;
            border-bottom-right-radius: 4px;
        }

        .theirs .bubble {
            background: #fff;
            color: #111;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .message-meta {
            font-size: 11px;
            color: #aaa;
            margin-top: 4px;
            padding: 0 4px;
        }

        .sender-name {
            font-size: 12px;
            color: #888;
            margin-bottom: 3px;
            padding: 0 4px;
        }

        /* Empty state */
        .empty-state {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: 15px;
            flex-direction: column;
            gap: 8px;
        }

        /* Input area */
        .input-area {
            padding: 16px 24px;
            background: #fff;
            border-top: 1px solid #e0e0e0;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .input-area input {
            flex: 1;
            padding: 10px 16px;
            border: 1px solid #ddd;
            border-radius: 24px;
            font-size: 14px;
            outline: none;
            transition: border 0.2s;
        }

        .input-area input:focus { border-color: #4f46e5; }

        .input-area button {
            padding: 10px 20px;
            background: #4f46e5;
            color: #fff;
            border: none;
            border-radius: 24px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
        }

        .input-area button:hover { background: #4338ca; }
        .input-area button:disabled { background: #aaa; cursor: not-allowed; }
    </style>
</head>
<body>

{{-- Sidebar --}}
<div class="sidebar">
    <div class="sidebar-header">💬 Messages</div>

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
                <div class="empty-state">
                    <span>👋</span>
                    <span>Say hello to start the conversation!</span>
                </div>
            @endforelse
        </div>

        <div class="input-area">
            <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off">
            <button id="send-btn">Send</button>
        </div>

    @else

        <div class="empty-state">
            <span>💬</span>
            <span>Select a conversation to start chatting</span>
        </div>

    @endif

</div>

@if(isset($conversation) && $conversation)
<script src="{{ asset('js/app.js') }}"></script>
<script>
const conversationId = {{ $conversation->id }};
const currentUserId  = {{ auth()->id() }};

// Scroll to bottom on load
const container = document.getElementById('messages');
container.scrollTop = container.scrollHeight;

// Listen for new messages
Echo.private(`conversation.${conversationId}`)
    .listen('MessageSent', (data) => {
        appendMessage(data.sender_name, data.body, data.sender_id === currentUserId, data.created_at);
    });

// Send on Enter key
document.getElementById('message-input').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') sendMessage();
});

// Send on button click
document.getElementById('send-btn').addEventListener('click', sendMessage);

async function sendMessage() {
    const input = document.getElementById('message-input');
    const body  = input.value.trim();
    if (!body) return;

    input.value = '';
    document.getElementById('send-btn').disabled = true;

    const res = await fetch(`/chat/${conversationId}/message`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ body }),
    });

    const data = await res.json();
    appendMessage('You', data.body, true, data.created_at);
    document.getElementById('send-btn').disabled = false;
}

function appendMessage(senderName, body, isMine, createdAt) {
    const time = createdAt ? new Date(createdAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '';

    const wrapper = document.createElement('div');
    wrapper.className = 'message-wrapper ' + (isMine ? 'mine' : 'theirs');
    wrapper.innerHTML = `
        ${!isMine ? `<div class="sender-name">${senderName}</div>` : ''}
        <div class="bubble">${body}</div>
        <div class="message-meta">${time}</div>
    `;

    container.appendChild(wrapper);
    container.scrollTop = container.scrollHeight;
}
</script>
@endif

</body>
</html>