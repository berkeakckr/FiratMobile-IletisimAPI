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
    public static function dersleriEkle()
    {
        $dersler = Ders::all();
        foreach ($dersler as $ders) {
            if (!Conversation::where('ders_id', $ders->id)->first()) {
                $conversation = Conversation::create([
                    'title' => $ders->ders_adi,
                    'description' => $ders->ders_adi . ' ' . "Açıklaması",
                    'type' => 0,
                    'everyone_chat' => 0,
                    'ders_id' => $ders->id
                ]);
                //dd($ders->id);
            }
        }
    }
    public static function check()
        {
        $user_dersler = UserDers::where('user_id',Auth::id())->get()->pluck('ders_id');
        foreach ($user_dersler as $user_ders ){
            if(!UserConversation::where('user_id',Auth::id())->where('conversation_id',Conversation::where('ders_id',$user_ders)->first()->id)->first()){
                //Öğrenci eğer dersinin conversationuna dahil edilmediyse onu dahil et
                //önce conversationun firstini alıyoruz(örn:dağıtık dersinin bir kaydı var ve onu alıyoruz
                //sonra giriş yapan kişinin user conversationlarındaki o conversationun firstini alıyoruz(zaten bir grupta birden fazla
                //bulunamayız,sonra en başa ! koyuyoruz ve eğer böyle bir kayıt yoksa demiş oluyoruz.Daha sonra o user conversationu
                //oluşturuyoruz.
                $user_conversation = UserConversation::create([
                    'user_id' => Auth::id(),
                    'conversation_id' => $user_ders,
                    'send_message' => 0,
                    'status' => 0
                ]);
            }
        }
    }
}
