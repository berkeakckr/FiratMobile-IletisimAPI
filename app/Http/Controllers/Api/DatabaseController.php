<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Ders;
use App\Models\UserConversation;
use App\Models\UserDers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatabaseController extends Controller
{
    public static function check()
    {
        $dersler = Ders::all();
        foreach ($dersler as $ders){
            if (!Conversation::where('ders_id',$ders->id)->first()){
                $conversation = Conversation::create([
                    'title' => $ders->ders_adi,
                    'description' => $ders->ders_adi.' '."Açıklaması",
                    'type' => 0,
                    'everyone_chat' => 0,
                    'ders_id' => $ders->id
                ]);
            }
        }
        $user_dersler = UserDers::where('user_id',Auth::id())->get();
        foreach ($user_dersler as $user_ders ){
            if(!UserConversation::where('user_id',Auth::id())->where('conversation_id',Conversation::where('ders_id',$user_ders->id))->first()){
                $user_conversation = UserConversation::create([
                    'user_id' => $user_ders->user_id,
                    'conversation_id' => $user_ders->id,
                    'send_message' => 0,
                    'status' => 0
                ]);
            }
        }
    }
}
