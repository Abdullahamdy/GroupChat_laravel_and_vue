<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\AddNewGroup;
use Illuminate\Support\Str;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Events\PushToSideBar;
use App\Events\PrivateGroupSent;
use App\Notifications\NotifyMembers;
use Illuminate\Support\Facades\Auth;

class GroupChatController extends Controller
{
    public function groupChat()
    {
        return view('groupchat');
    }

    public function getGroups(Request $request)
    {
        $groups  = $request->user()->conversations()->with('lastMessage')->get();
        $groups->each(function ($group) use ($request) {
            $group->hasRead = $request->user()->hasRead($group);
        });
        return response()->json(['groups' => $groups]);
    }

    public function getMessages($GroupId)
    {
        $groupMessages =  Message::with(['user'])->where('conversation_id', $GroupId)->get();
        $conversation = Conversation::find($GroupId);
        $conversation->users()->updateExistingPivot(request()->user(), ['read_at' => now()]);
        return response()->json(['groupMessages' => $groupMessages]);
    }

    public function sendMessage(Request $request)
    {
        $data = [];
        $data['body'] = $request->body;
        $data['user_id'] = Auth::id();
        $conversation = Conversation::find($request->conversation_id);
        $conversation->update(['last_message_at'=>now()]);
        $message = $conversation->messages()->create($data);
        $conversation->users()->where('user_id', '!=', request()->user()->id)->update(['read_at' => null]);

        $message->load('user');
        broadcast(new PrivateGroupSent($message))->toOthers();
        broadcast(new PushToSideBar($message))->toOthers();
        return response()->json($message);
    }

    public function AddNewGroup(Request $request)
    {
        $conversation =  Conversation::create(['name' => $request->group['GroupName'], 'uuid' => Str::uuid(), 'user_id' => Auth::id()]);
        $conversation->users()->attach($request->group['Members']);
        $conversation = $conversation->load('lastMessage');
        $conversationOwner = $conversation->user_id;
        foreach ($conversation->users as $user) {
            if ($conversationOwner != $user->id) {
                $user->notify(new NotifyMembers($conversation));
            }
        }
        broadcast(new AddNewGroup($conversation))->toOthers();
        return response()->json(['groups' => $conversation]);
    }
    public function deleteGroup(Request $request)
    {
        $Conversation =  Conversation::find($request->groupId);
        $conversationName = $Conversation->name;
        $Conversation->delete();
        return response()->json(['groupName' => $conversationName]);
    }
    public function pushgroup($conversation)
    {
        broadcast(new AddNewGroup($conversation));
    }
    public function getUnreadNotifications()
    {
        $notifications  = Auth::user()->unreadNotifications;
        return response()->json($notifications);
    }
}
