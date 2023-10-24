<?php

namespace App\Http\Controllers;

use App\Events\PrivateGroupSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupChatController extends Controller
{
    public function groupChat()
    {
        return view('groupchat');
    }

    public function getGroups(Request $request)
    {
        $groups  = $request->user()->conversations->load('lastMessage');
        return response()->json(['groups' => $groups]);
    }

    public function getMessages($GroupId)
    {
        $groupMessages =  Message::with(['user'])->where('conversation_id', $GroupId)->get();
        return response()->json(['groupMessages' => $groupMessages]);
    }

    public function sendMessage(Request $request)
    {
        $data = [];
        $data['body'] = $request->body;
        $data['user_id'] = Auth::id();
        $conversation = Conversation::find($request->conversation_id);
        $message = $conversation->messages()->create($data);
        broadcast(new PrivateGroupSent($message))->toOthers();
        return response()->json($message);
    }
}
