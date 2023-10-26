<?php

namespace App\Http\Controllers;

use App\Events\AddNewGroup;
use App\Models\Message;
use Illuminate\Support\Str;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Events\PrivateGroupSent;
use App\Notifications\NotifyMembers;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;

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
        $message->load('user');
        broadcast(new PrivateGroupSent($message))->toOthers();
        return response()->json($message);
    }

    public function AddNewGroup(Request $request)
    {
        $conversation =  Conversation::create(['name'=>$request->group['GroupName'],'uuid'=>Str::uuid(),'user_id'=>Auth::id()]);
        $conversation->users()->attach($request->group['Members']);
        $conversation = $conversation->load('lastMessage');
        broadcast(new AddNewGroup($conversation))->toOthers();
        if($conversation->id !=Auth::id()){
            foreach($conversation->users as $user){
                $user->notify(new NotifyMembers($conversation));
            }
        }
        return response()->json(['groups' => $conversation]);

    }
    public function deleteGroup(Request $request)
    {
        $Conversation =  Conversation::find($request->groupId);
        $conversationName = $Conversation->name;
        $Conversation->delete();
        return response()->json(['groupName' => $conversationName]);


    }

    public function getUnreadNotifications()
    {
        $notifications  = Auth::user()->unreadNotifications;
        return response()->json($notifications);

    }
}
