<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\UserBolum;
use App\Models\Ders;
use App\Models\Message;
use App\Models\User;
use App\Models\UserConversation;
use Illuminate\Database\Eloquent\Model;
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
        //$ders = Ders::where('akademisyen_id',$user->id)->orderBy('created_at','desc')->get();//giriş yapan kişinin dersleri son eklenen en başta olarak görüntülenir
        $user_conversations = UserConversation::whereIn('user_id', $user->get_userConversation()->pluck('user_id'))->get();//Akademisyen Tarafından alınan derslerin aktif gruplarını yayınlar
        $conversations = Conversation::whereIn('id',$user_conversations->pluck('conversation_id'))->get();
        $messagecount = $messages->count();
        return response()->json([
            'user' => $user->name.'('.$user->email.')'.' Kişisine Ait Hesaptasınız.',
            //'ders' => $ders,
            'conversations' => $conversations,
            'messagecount' => $messagecount.' Mesaj Bulunmakta.',
            'message' => $messages

        ]);
    }

    public function academicsList(){
        $user = Auth::user();
        $user_bolum_id = UserBolum::where('user_id',$user->id)->get()->pluck('bolum_id');
        //dd($user_bolum_id);
        $user_ids=UserBolum::where('bolum_id',$user_bolum_id)->get()->pluck('user_id');
        //dd($user_ids);
        $academics=User::whereIn('id',$user_ids)->where('type',1)->get();
       // dd($academics);
        //$conversation
        if (!$academics){
            return response()->json([
                'status' => 401,
                'message' => $user->name.' Kişisinin Bölümüne Ait Akademisyen Bulunamadı.'
            ]);
        }
        return response()->json([
            'user' => $user->name.'('.$user->email.')'.' Kişisine Ait Hesaptasınız.',
            'akademisyen_ismi' => $academics->name,
            'akademisyen_mail' => $academics->mail,

        ]);
    }

    public function message($conversation_id){
        $user = Auth::user();
        $conversations = Conversation::find($conversation_id); //Link ile giriş Yapılan sayfayı id değerine göre bul
        $messages = Message::where('user_id',$user->id)->where('conversation_id',$conversations->id)->orderBy('created_at','desc')->get();//Conversation idsine ait mesajları görüntüle

        return response()->json([
            'user' => $user->name.'('.$user->email.')'.' Kişisine Ait Hesaptasınız.',
            'userconversation' => $conversations->title.' Mesaj Kutusundasınız',
            'message' => $messages
        ]);
    }


    public function messageCreate(Request $request,$id)
    {
        $user = Auth::user();
        $user_type =$user->type;
        $conversation= Conversation::find($id);
        $logined_user_conversation=UserConversation::where('conversation_id',$conversation->id)->where('user_id',$user->id)->first();
        if($user_type==1 &&$logined_user_conversation->send_message==0)// eğer type 1 yani akademisyense ve  giriş yapan kişinin
            // bu grupta msj atma yetkisi varsa mesaj at

        {
            //post metodu
            $message = new Message();
            $message->text = $request->text;
            if($request->hasFile('file')){
                $imageName=time().rand(1,1000).'.'.$request->file->getClientOriginalExtension();
                $request->file->move(public_path('images'),$imageName);
                $message->file='images/'.$imageName;
            }
            $message->user_id = $user->id;
            $message->conversation_id = $conversation->id;
            $message->save();
            /*  $notification = Notification::create([
                  'message_id' => $message->id,
                  'user_id' => $user->id,
                  //'reached' => $data['email'],
                  // 'readed' => $data['type']
              ]);*/
            return response()->json(['message'=>'Mesaj Başarılı Bir Şekilde Oluşturuldu']);
        }

        if($user_type==0&&$logined_user_conversation->send_message==0)
        //eğer mesaj atan öğrenciyse ve mesaj atılan yer ders grubuysa ve giriş yapan kişinin bu grupta msj atma yetkisi varsa
            //mesaj at
        {
            //post metodu
            $message = new Message();
            $conversation = new Conversation();
            $message->text = $request->text;
            if($request->hasFile('file')){
                $imageName=time().rand(1,1000).'.'.$request->file->getClientOriginalExtension();
                $request->file->move(public_path('images'),$imageName);
                $message->file='images/'.$imageName;
            }
            $message->user_id = $user->id;  //$user->id
            $message->conversation_id = $request->conversation_id;
            $conversation= Conversation::find($request->conversation_id);

            $message->save();

            /*  $notification = Notification::create([
                  'message_id' => $message->id,
                  'user_id' => $user->id,
                  //'reached' => $data['email'],
                  // 'readed' => $data['type']
              ]);*/

            return response()->json(['message'=>'Mesaj Başarılı Bir Şekilde Oluşturuldu']);
        }
        else{
            return response()->json([
                'error'=>'Bir öğrenci başka bir öğrenciye mesaj atamaz.'
            ],401);
        }




        // $conversation->id = $request->conversation_id;
        /*  if($conversation->type==0 && $conversation->id==1 && $user->type==1)
          {
              $message->save();
              return response()->json(['message'=>'Mesaj Başarılı Bir Şekilde Oluşturuldu']);
          }


              return response()->json([
                  'error'=>'Bir öğrenci başka bir öğrenciye mesaj atamaz.'
              ],401);*/

    }


    public function messageDelete($conversation_id,$message_id)
    {
        //delete()
        Conversation::find($conversation_id)->messages()->whereId($message_id);
        $message = Message::destroy($message_id);
        if (!$message){
            return response()->json([
                'status'=>'400',
                'message'=>'Mesaj Bulunamadı'
            ]);
        }
        return response()->json(['message'=>'Mesaj Başarılı bir şekilde silindi']);
    }


    public static function checkUsertoUserChat($user_id){
        $user = Auth::user();
        $receiver= User::find($user_id);

        $logined_user_conv_id=UserConversation::where('user_id', $user->id)->get()->pluck('conversation_id')->toArray(); //userid 1  conversation id 3,5,6 conv id 6 olan tekli sohbet onu almamız lazım
        $receiver_user_conv_id=UserConversation::where('user_id', $user_id)->get()->pluck('conversation_id')->toArray(); //userid 2  conversation id 1,5,6

        $same_ids= array_intersect($logined_user_conv_id,$receiver_user_conv_id);

        $single_chat=Conversation::where('id',[$same_ids])->where('ders_id',null)->first();
        if($user->type==0 && $receiver->type==0)
        {
            return response()->json([
                'message' => 'Öğrenci Öğrenciye Mesaj Atamaz.'
            ]);
        }
        if($single_chat==null)
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
        return response()->json($single_chat);

    }

}
