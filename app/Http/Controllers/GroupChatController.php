<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Events\AddNewGroup;
use Illuminate\Support\Str;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Events\PushToSideBar;
use App\Events\PrivateGroupSent;
use App\Notifications\NotifyMembers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
        $date['attachment'] = null;
        if ($request->hasFile('audio') && !$request->image) {
            $audio = $request->file('audio');
            if ($audio->getSize() > 0) {
                $path = $audio->store('audio', 'public'); // Save the audio file to the 'public' disk under the 'audio' directory
                $data['attachment'] = '/storage/' . $path;
                $data['attachment'] = '/storage/' . $path;
                $data['mime_type'] = 'audio';
            }
        }

        if ($request->image) {
            $date['attachment'] = $request->image;
            $data['attachment'] = $request->image;
        }
        $data['body'] = $request->body;
        $data['user_id'] = Auth::id();
        $conversation = Conversation::find($request->conversation_id);
        $conversation->update(['last_message_at' => now()]);
        $message = $conversation->messages()->create($data);
        $conversation->users()->where('user_id', '!=', request()->user()->id)->update(['read_at' => null]);
        $message->load('user');
        broadcast(new PrivateGroupSent($message))->toOthers();
        broadcast(new PushToSideBar($message))->toOthers();
        return response()->json($message);
    }

    public function AddNewGroup(Request $request)
    {
        $conversation =  Conversation::create([
            'name' => $request->group['GroupName'],
            'uuid' => Str::uuid(),
            'user_id' => Auth::id()
        ]);
        $conversation->users()->attach($request->group['Members']);
        $conversation = $conversation->load('lastMessage');
        $conversationOwner = $conversation->user_id;
        $memberIds = $conversation->users->except($conversationOwner);
        foreach ($memberIds as $user) {
            $user->notify(new NotifyMembers($conversation));
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
        $notifications = Auth::user()->unreadNotifications()->paginate(5);
        return response()->json($notifications);
    }

    public function CreateLocalImage(Request $request)
    {
        if ($request->file('image')) {
            $image = $request->file('image');
            if ($image) {
                $path = $image->store('public/images');
                $url = Storage::url($path);
                return response()->json(['url' => $url, 'path' => $path], 200);
            }
        }
    }
    public function deleteLocalImage(Request $request)
    {

        $imagePath = 'public/images/qs9Ml5uWdEGi9ZkMezcM6B2WCzUMgFkkGnGu7Wv3.png';
        if (Storage::exists($request->image)) {
            Storage::delete($request->image);
            echo 'Image removed successfully.';
        } else {
            echo 'Image not found.';
        }
    }
}
