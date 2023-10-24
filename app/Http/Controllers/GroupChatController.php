<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class GroupChatController extends Controller
{
    public function groupChat()
    {
        return view('groupchat');
    }

    public function getGroups(Request $request)
    {
         $groups  = $request->user()->conversations->load('lastMessage');
         return response()->json(['groups'=>$groups]);
        }

        public function getMessages($GroupId)
        {
            $groupMessages =  Message::with(['user'])->where('conversation_id',$GroupId)->get();
            return response()->json(['groupMessages'=>$groupMessages]);


    }
}
