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
    public static function checkUsertoUserChat($user_id){
        $user = Auth::user();
        $receiver= User::find($user_id);

        $logined_user_conv_id=UserConversation::where('user_id', $user->id)->get()->pluck('conversation_id')->toArray(); //userid 1  conversation id 3,5,6 conv id 6 olan tekli sohbet onu almamız lazım
        $receiver_user_conv_id=UserConversation::where('user_id', $user_id)->get()->pluck('conversation_id')->toArray(); //userid 2  conversation id 1,5,6

        $same_ids= array_intersect($logined_user_conv_id,$receiver_user_conv_id);

        $logined_user=Conversation::where('id',[$same_ids])->where('ders_id',null)->first();
        if($user->type==0 && $receiver->type==0)
        {
            return response()->json([
                'message' => 'Öğrenci Öğrenciye Mesaj Atamaz.'
            ]);
        }
        if($logined_user==null)
        {
            $conversation = Conversation::create([
                'title' => $user->name . ' ve ' . $receiver->name,
                'description' => $user->name . ' ve ' . $receiver->name.''." Sohbeti",
                'type' => 1,
                'everyone_chat' => 0,
            ]);
            $user_conversation_1=UserConversation::create([
                'user_id' => $user->id,
                'conversation_id' => $conversation->id,
                'send_message' => 0,
            ]);
            $user_conversation_2=UserConversation::create([
                'user_id' => $receiver->id,
                'conversation_id' => $conversation->id,
                'send_message' => 0,
            ]);
        }
        else{
            return response()->json([
                'message' => 'Böyle bir sohbet mevcut'
            ]);
        }
        return response()->json($logined_user);

    }

}
