const conversationId = window.conversationId;
const currentUserId  = window.currentUserId;

const container = document.getElementById('messages');
if (container) {
    container.scrollTop = container.scrollHeight;
}

Echo.private(`conversation.${conversationId}`)
    .listen('MessageSent', (data) => {
        appendMessage(data.sender_name, data.body, data.sender_id === currentUserId, data.created_at);
    });

document.getElementById('message-input').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') sendMessage();
});

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