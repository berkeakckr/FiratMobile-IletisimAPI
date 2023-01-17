<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Ders;
use App\Models\Message;
use App\Models\User;
use App\Models\UserConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){
        $user = Auth::user();
        $messages = Message::where('user_id',$user->id)->orderBy('created_at','desc')->get();
        //$conversation
        if (!$messages){
            return response()->json([
                'status' => 401,
                'message' => $user->name.' Kişisine Ait Mesaj Bulunamadı.'
            ]);
        }
        $ders = Ders::where('akademisyen_id',$user->id)->orderBy('created_at','desc')->get();
        $user_conversations = UserConversation::whereIn('user_id', $user->get_userConversation()->pluck('user_id'))->get();
        $conversations = Conversation::whereIn('id',$user_conversations->pluck('conversation_id'))->get();
        $messagecount = $messages->count();
        return response()->json([
            'user' => $user->name.'('.$user->email.')'.' Kişisine Ait Mesaj Kutusu.',
            'ders' => $ders,
            'userconversation' => $conversations,
            'messagecount' => $messagecount.' Mesaj Bulunmakta.',
            'message' => $messages

        ]);
    }

    public function message($conversation_id){
        $user = Auth::user();
        $conversations = Conversation::find($conversation_id);
        $messages = Message::where('user_id',$user->id)->where('conversation_id',$conversations->id)->orderBy('created_at','desc')->get();

        return response()->json([
            'user' => $user->name.'('.$user->email.')'.' Kişisine Ait Mesaj Kutusu.',
            'userconversation' => $conversations->title.' Mesaj Kutusundasınız',
            'message' => $messages
        ]);
    }

}
