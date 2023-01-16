<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Ders;
use App\Models\UserConversation;
use App\Models\UserDers;
use Illuminate\Http\Request;

class DatabaseController extends Controller
{
    public static function check()
    {
        $dersler = Ders::all();
        $conversations = Conversation::all();
        $user_dersler = UserDers::all();
        $user_conversations = UserConversation::all();
        if($conversations->isEmpty())
        {
            foreach($dersler as $ders)
            {
                $conversation = Conversation::create([
                    'title' => $ders->ders_adi,
                    'description' => $ders->ders_adi.' '."Açıklaması",
                    'type' => 0,
                    'everyone_chat' => 0
                ]);
            }
        }
        else{
            foreach($conversations as $conversation) {
                foreach ($dersler as $ders) {
                    if($ders->id==$conversation->id)
                    {
                        continue;
                    }
                    else{
                        $conversation = Conversation::create([
                            'title' => $ders->ders_adi,
                            'description' => $ders->ders_adi . ' ' . "Açıklaması",
                            'type' => 0,
                            'everyone_chat' => 0
                        ]);
                    }
                }
            }
        }
        if($user_conversations->isEmpty())
        {
            foreach($user_dersler as $user_ders)
            {
                $user_conversation = UserConversation::create([
                    'user_id' => $user_ders->user_id,
                    'conversation_id' => $user_ders->id,
                    'send_message' => 0,
                    'status' => 0
                ]);
            }
        }
        else{
            foreach($user_conversations as $user_conversation) {
                foreach ($user_dersler as $user_ders) {
                    if($user_ders->id==$user_conversation->id)
                    {
                        continue;
                    }
                    else{
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
    }
}
