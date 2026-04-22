<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\User;    
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function startOrGet(Request $request)
    {
        if (!auth()->user()->isSeeker()) {
            abort(403, 'Only job seekers can start conversations.');
        }

        $request->validate(['employer_id' => 'required|exists:users,id']);
        $employer = User::findOrFail($request->employer_id);

        $conversation = Conversation::where('seeker_id', auth()->id())
            ->where('employer_id', $employer->id)
            ->first();

        if ($conversation) {
            return redirect()->route('chat.show', $conversation);
        }

        return redirect()->route('chat.index', ['new_chat_with' => $employer->id]);
    }

    // List conversations and start a new one
    public function index(Request $request)
    {
        $userId = auth()->id();
        
        $conversations = Conversation::where(function($q) use ($userId) {
                $q->where('seeker_id', $userId)->orWhere('employer_id', $userId);
            })
            ->has('messages') 
            ->with(['seeker', 'employer', 'messages'])
            ->orderByDesc('last_message_at')
            ->get();

        $conversation = null;
        $messages = collect();

        if ($request->has('new_chat_with')) {
            $employer = User::find($request->new_chat_with);
            if ($employer) {
                $conversation = new Conversation([
                    'employer_id' => $employer->id,
                    'seeker_id' => auth()->id()
                ]);
                $conversation->setRelation('employer', $employer);
            }
        }

        return view('conversationView.chatbox', compact('conversations', 'messages', 'conversation'));
    }

    // Show conversation and mark messages as read
    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);
        $userId = auth()->id();

        $conversations = Conversation::where('seeker_id', $userId)
            ->orWhere('employer_id', $userId)
            ->has('messages')
            ->with(['seeker', 'employer', 'messages'])
            ->orderByDesc('last_message_at')
            ->get();

        $messages = $conversation->messages()->with('sender')->get();

        $conversation->messages()
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('conversationView.chatbox', compact('conversation', 'messages', 'conversations'));
    }

    //create new message
    public function store(Request $request, $id = null)
    {
        $request->validate([
            'body' => 'required|string',
            'employer_id' => 'required_without:id' 
        ]);

        if ($id && $id !== '0' && $id !== 'undefined' && $id !== 'null') {
            $conversation = Conversation::findOrFail($id);
        } else {
            $conversation = Conversation::firstOrCreate([
                'seeker_id'   => auth()->id(),
                'employer_id' => $request->employer_id,
            ]);
        }

        $message = $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'body'      => $request->body,
        ]);

        $conversation->update(['last_message_at' => now()]);

        broadcast(new MessageSent($message->load('sender')))->toOthers();

        return response()->json([
            'id'              => $message->id,
            'conversation_id' => $conversation->id,
            'body'            => $message->body,
            'time'            => $message->created_at->format('h:i A'),
        ]);
    }
}