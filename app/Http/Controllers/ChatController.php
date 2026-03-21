<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\User;    
use Illuminate\Http\Request;


class ChatController extends Controller
{
    // Start or resume a conversation
    public function startOrGet(Request $request)
    {
        // only seekers can start a conversation
        if (!auth()->user()->isSeeker()) {
            abort(403, 'Only job seekers can start conversations.');
        }

        $request->validate(['employer_id' => 'required|exists:users,id']);

        // make sure the target user is actually an employer
        $employer = User::findOrFail($request->employer_id);
        if (!$employer->isEmployer()) {
            abort(403, 'You can only chat with employers.');
        }

        $conversation = Conversation::firstOrCreate([
            'seeker_id'   => auth()->id(),
            'employer_id' => $request->employer_id,
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    // Show conversation view
    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        $userId = auth()->id();

        // load sidebar conversations
        $conversations = Conversation::where('seeker_id', $userId)
            ->orWhere('employer_id', $userId)
            ->with(['seeker', 'employer', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->orderByDesc('last_message_at')
            ->get();

        $messages = $conversation->messages()->with('sender')->get();

        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('conversationView.chatbox', compact('conversation', 'messages', 'conversations'));
    }
    // List all conversations for current user
    public function index()
    {
        $userId = auth()->id();

        $conversations = Conversation::where('seeker_id', $userId)
            ->orWhere('employer_id', $userId)
            ->with(['seeker', 'employer', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->orderByDesc('last_message_at')
            ->get();

        return view('conversationView.chatbox', [
            'conversations' => $conversations,
            'messages'      => collect(),  // ← empty collection
            'conversation'  => null,       // ← null when no conversation selected
        ]);
    }

    // Send a message
    public function sendMessage(Request $request, Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $request->validate(['body' => 'required|string|max:2000']);

        $message = $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'body'      => $request->body,
        ]);

        $conversation->update(['last_message_at' => now()]);

        broadcast(new MessageSent($message->load('sender')))->toOthers();

        return response()->json([
            'id'          => $message->id,
            'body'        => $message->body,
            'sender_id'   => $message->sender_id,
            'sender_name' => $message->sender->name,
            'created_at'  => $message->created_at->toISOString(),
        ]);
    }
}