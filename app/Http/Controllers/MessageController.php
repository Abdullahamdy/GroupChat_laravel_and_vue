<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Block;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Events\PrivateMessageSent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function fetchMessage()
    {
        return  Message::with('user')->get();
    }
    public function sendMessage(Request $request)
    {
        $message =  auth()->user()->messages()->create(['body' => $request->message]);
        broadcast(new MessageSent(auth()->user(), $message->load('user')))->toOthers();
        return response(['status' => 'message Send Successfully', 'message' => $message]);
    }

    public function privateChat()
    {
        return view('privavte');
    }

    public function getusers()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return response(['users' => $users]);
    }


    public function getUserMessage(Request $request, $FriendId)
    {
        $senderId = Auth::id();
        $user = User::find($FriendId);
        $block = $this->checkIsUserBlocked($FriendId);
        $messages = Message::whereConversationId(Null)->with('user')->where(function ($query) use ($senderId, $FriendId) {
            $query->where('user_id', $senderId)
                ->where('receiver_id', $FriendId);
        })
            ->orWhere(function ($query) use ($senderId, $FriendId) {
                $query->where('user_id', $FriendId)
                    ->where('receiver_id', $senderId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
        return response(['messages' => $messages, 'currentUser' => $user, 'isBlock' => $block]);
    }

    public function sendPrivateMessage(Request $request, $id)
    {
        $input['body'] = $request->message;
        $input['receiver_id'] = $id;
        $message =  auth()->user()->messages()->create($input);
        broadcast(new PrivateMessageSent($message->load('user')))->toOthers();
        return response(['status' => 'message Send Successfully', 'message' => $message]);
    }

    public function blockUser(User $user)
    {
        $isBlock = false;
        $senderId = Auth::id();
        $userblocked = Block::where('user_id', $user->id)
            ->where('blocked_user_id', $senderId)
            ->first();

        if ($userblocked) {
            $userblocked->delete();
            $isBlock = false;
        } else {
           $userblocked = Block::create([
                'user_id' => $user->id,
                'blocked_user_id' => $senderId
            ]);
            $isBlock = true;
        }
        $userblocked ? $userblocked->user : $userblocked;
        return response(['isBlock' => $isBlock, 'blockFriend' => $userblocked]);
    }



    private function checkIsUserBlocked($FriendId)
    {
        $isBlock = false;
        $senderId = Auth::id();
        $userblocked = Block::where('user_id', $FriendId)
            ->where('blocked_user_id', $senderId)
            ->first();

        if ($userblocked) {
            $isBlock = true;
        } else {
            $isBlock = false;
        }
       $userblocked =  $userblocked ? $userblocked->user : null;
        return ['isBlock'=>$isBlock,'blockFriend'=>$userblocked];
    }
}
