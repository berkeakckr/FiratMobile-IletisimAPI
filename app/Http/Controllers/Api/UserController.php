<?php

namespace App\Http\Controllers\Api;

use App\Helper\EnumClass;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Notification;
use App\Models\UserBolum;
use App\Models\Ders;
use App\Models\Message;
use App\Models\User;
use App\Models\UserConversation;
use App\Models\UserDers;
use Database\Seeders\UserDersSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(){//Giriş Yapan Kişinin Tüm Sohbetlerini Görüntüleme
        $user = Auth::user();
        $messages = Message::where('user_id',$user->id)->orderBy('created_at','desc')->get();
        //mesajları azalan oluşturma sırasına göre getir
        //$conversation
        if (!$messages){
            return response()->json([
                'status' => 401,
                'message' => $user->name.' Kişisine Ait Mesaj Bulunamadı.'
            ]);
        }
        $user_conversations = UserConversation::where('user_id', $user->id)->get()->pluck('conversation_id');
        //Giriş yapan kişi hangi derslerde veya tekli sohbette varsa o rowların idsini aldık.

        $conversations = Conversation::whereIn('id',$user_conversations)->get();
        //Giriş yapan kişinin sohbetlerini görüntülemek için yukarıda aldığımız idsi olan dersleri getirdik.
        $messagecount = $messages->count();
        return response()->json([
            'user' => $user->name.'('.$user->email.')'.' Kişisine Ait Hesaptasınız.',
            'user_id'=>$user->id,
            'logined_user_name'=>$user->name,
            'conversations' => $conversations,
            'messagecount' => $messagecount.' Mesaj Bulunmakta.',
            'message' => $messages

        ]);
    }
    public function getGroupChats()//Giriş Yapan Kişinin Sadece Ders Sohbetlerini Getirme
    {
        $user = Auth::user();
        //$messages = Message::where('user_id',$user->id)->orderBy('created_at','desc')->get();
        //$conversation

        $user_conversations = UserConversation::where('user_id', $user->id)->get()->pluck('conversation_id');//Giriş yapan kişi hangi derslerde veya tekli sohbette varsa o rowların idsini aldık.
        //Akademisyen Tarafından alınan derslerin aktif gruplarını yayınlar
        $conversations = Conversation::whereIn('id',$user_conversations)->where('ders_id','!=',null)->get();//Giriş yapan kişinin derslerini görüntülemek için ders idsi boş olmayan dersleri getirdik.
        //$messagecount = $messages->count();
        return response()->json([
            'user' => $user->name.'('.$user->email.')'.' Kişisine Ait Hesaptasınız.',
            'user_id'=>$user->id,
            'logined_user_name'=>$user->name,
            'conversations' => $conversations,
            //'messagecount' => $messagecount.' Mesaj Bulunmakta.',
            //'message' => $messages

        ]);
    }
    public function getSingleChats()//Giriş Yapan Kişinin Sadece Özel Sohbetlerini Getirme
    {
        $user = Auth::user();
        //$messages = Message::where('user_id',$user->id)->orderBy('created_at','desc')->get();


        $user_conversations = UserConversation::where('user_id', $user->id)->get()->pluck('conversation_id');
        //Giriş yapan kişi hangi derslerde veya tekli sohbette varsa o rowların conv_idsini aldık.

        $conversations = Conversation::whereIn('id',$user_conversations)->where('ders_id',null)->get();
        //Giriş yapan kişinin tekli sohbetlerini görüntülemek için ders idsi boş olan sohbetleri getirdik.
        $conversations_id = Conversation::whereIn('id',$user_conversations)->where('ders_id',null)->get()->pluck('id');
        //Giriş yapan kişinin tekli sohbetlerini görüntülemek için ders idsi boş olan sohbetleri getirdik.
        $receiver_conversation_ids = UserConversation::where('user_id', '!=', $user->id)->whereIn('conversation_id', $conversations_id)->get()->pluck('user_id');
        $receiver_conversation_names=User::whereIn('id',$receiver_conversation_ids)->get()->pluck('name');
        //dd($receiver_conversation_names);tekli sohbet ekranında sadece alıcı isminin yazılması için yazılan sorgular..
        //normal halinde hem gönderici ve hem alıcı isimleri var.

        //$messagecount = $messages->count();

        if (!$conversations){
            return response()->json([
                'status' => 401,
                'message' => $user->name.' Kişisine Ait Tekli Sohbet Bulunamadı.'
            ]);
        }

        return response()->json([
            'user' => $user->name.'('.$user->email.')'.' Kişisine Ait Hesaptasınız.',
            'user_id'=>$user->id,
            'logined_user_name'=>$user->name,
            'conversations' => $conversations,
            'conversations_id'=>$conversations_id
            //'messagecount' => $messagecount.' Mesaj Bulunmakta.',
            //'message' => $messages

        ]);
    }

    public function academicsList(){//Giriş Yapan Kişinin Bölümündeki Akademisyenleri Listeleme
        $user = Auth::user();
        $user_bolum_id = UserBolum::where('user_id',$user->id)->get()->pluck('bolum_id');
        //dd($user_bolum_id);
        $user_ids=UserBolum::where('bolum_id',$user_bolum_id)->get()->pluck('user_id');
        //dd($user_ids);
        $academics=User::whereIn('id',$user_ids)->where('type',1)->get(['id','name','email']);
        //Giriş Yapan Kişinin Bölümünde olan ve akademisyen(type==1) olanları getir

        if (!$academics){
            return response()->json([
                'status' => 401,
                'message' => $user->name.' Kişisinin Bölümüne Ait Akademisyen Bulunamadı.'
            ]);
        }
        return response()->json([
            'user' => $user->name.'('.$user->email.')'.' Kişisine Ait Hesaptasınız.',
            'user_id'=>$user->id,
            'logined_user_name'=>$user->name,
            'akademisyenler' => $academics,

        ]);
    }

    public function getChatList($conversation_id){
        $conversation=Conversation::find($conversation_id);
        $user_conversations=UserConversation::where('conversation_id',$conversation->id)->get()->pluck('user_id');
        return response()->json([
            'list'=>User::whereIn('id',$user_conversations)->get(['id','name'])
        ]);
    }

    public function getDanismanList(){
        $user = Auth::user();
        return response()->json([
            'list'=>User::where('id',$user->danisman_id)->first([
                'id',
                'name',
                'email',
            ])
        ]);
    }

    public function messages($conversation_id){
        $user = Auth::user();
        $user_conversation = UserConversation::where('user_id', $user->id)->where('conversation_id',$conversation_id)->first();
        //giriş yapan kişinin parametre olarak gönderilen id değerine ait user_convunu  bul
        $conversation = Conversation::find($conversation_id); //parametre olarak gönderilen id değerine ait dersi bul
        $messages = Message::where('conversation_id',$conversation->id)->join('users', 'messages.user_id', '=', 'users.id')->orderBy('messages.created_at','desc')->get(['users.id','name','text']);


        //mesajları azalan oluşturma sırasına göre getir
        //Conversation idsine ait tüm  mesajları görüntüle



        return response()->json([
                'user' => $user->name.'('.$user->email.')'.' Kişisine Ait Hesaptasınız.',
                'user_id'=>$user->id,
                'logined_user_name'=>$user->name,
                'conversation' => $conversation->title.' Mesaj Kutusundasınız',
                'send_message'=>$user_conversation->send_message,
                'messages' => $messages,
            ]);


    }


    public function messageCreate(Request $request,$id)//Mesaj gönderme fonksiyonu
    {
        $user = Auth::user();
        $user_type =$user->type;
        $conversation= Conversation::find($id);
        $logined_user_conversation=UserConversation::where('conversation_id',$conversation->id)->where('user_id',$user->id)->first();
        //dd($logined_user_conversation);
        //Giriş yapan kişinin mesaj atılacak sohbette yetkisi var mı yokmu kontrolü için userconv rowunu getirdik

        if(!$logined_user_conversation)
        {
            return response()->json([
                'error'=>'Bu Kişi Bu Sohbette Yok.'
            ],401);
        }
        if($logined_user_conversation->send_message==0)// eğer giriş yapan kişi  akademisyen veya öğrenciyse
            // ve bu sohbette msj atma yetkisi varsa mesaj at
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
            $message->conversation_id = $id;
            $message->save();
            $users_to_message=UserConversation::where('conversation_id',$id)->where('user_id','!=',Auth::id())->get()->pluck('user_id');
            //dd($users_to_message->first());
            foreach($users_to_message as $users)
            {
                $notification =new Notification();
                $notification->message_id =$message->id;
                $notification->user_id =$users;

                $notification->save();
                //dd(User::where('id',$users)->get()->pluck('name').'Kişisi Mesaj Gönderdi','-',User::where('id',$users)->get()->pluck('device_mac_adress'));

                EnumClass::sendNotification(User::where('id',$users)->get()->pluck('name').'Kişisi Mesaj Gönderdi','-',User::where('id',$users)->get()->pluck('device_mac_adress'));
            }

            return response()->json([
                'message_owner_id'=>$message->user_id,
                'message_owner_name'=>User::where('id',$message->user_id)->pluck('name')->first(),
                'message'=>'Mesaj Başarılı Bir Şekilde Oluşturuldu',
            ]);
        }


        else{
            return response()->json([
                'error'=>'Mesaj Gönderilme Durumu Kapalı.'
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


    public function checkUsertoUserChat($user_id)//Tekli Sohbetler için gerekli conversation ve user_convların oluşturulması
        //ve o sohbetin görüntülenmesi
    {
        if($user_id==Auth::id())
        {
            return response()->json([
                'error'=>'Kendinize Mesaj Atamazsınız.'
            ],401);
        }

        $user = Auth::user();
        $receiver = User::find($user_id);

        $logined_user_conv_id = UserConversation::where('user_id', $user->id)->get()->pluck('conversation_id')->toArray();//Giriş Yapan Kişinin tüm user convlarını aldık
        //userid 1  conversation id 3,5,6 conv id 6 olan tekli sohbet onu almamız lazım
        $receiver_user_conv_id = UserConversation::where('user_id', $user_id)->get()->pluck('conversation_id')->toArray();//Alıcı kişinin tüm user convlarını aldık.
        //userid 2  conversation id 1,5,6


        $same_ids = array_intersect($logined_user_conv_id,$receiver_user_conv_id);
        //burada iki kişininde ortak olduğu user_convların idlerini,o ikili arasında daha önceden sohbet varmı yokmu karşılaştırmak
        //için birleştirdik.

        //dd($same_ids);

        $single_chat = Conversation::whereIn('id', $same_ids)->where('ders_id',null)->first();
        //eğer bu ikilinin bir arada olduğu bir conversation varsa ve bu sohbetin ders_idsi nullsa(yani tekli sohbetse)
        //o sohbeti getirdik.

        //dd($single_chat);

        if ($user->type == 0 && $receiver->type == 0) {
            return response()->json([
                'message' => 'Öğrenci Öğrenciye Mesaj Atamaz.'
            ]);
        }
        if ($single_chat == null)//Eğer daha önceden bu ikili arasında bir sohbet yoksa, o sohbetin convunu ve userconvlarını
            //oluştur.
        {
            $conversation = Conversation::create([
                'title' => $user->name . ' ve ' . $receiver->name,
                'description' => $user->name . ' ve ' . $receiver->name . '' . " Sohbeti",
                'type' => 1,
                'everyone_chat' => 0,
            ]);
            $user_conversation_1 = UserConversation::create([
                'user_id' => $user->id,
                'conversation_id' => $conversation->id,
                'send_message' => 0,
            ]);
            $user_conversation_2 = UserConversation::create([
                'user_id' => $receiver->id,
                'conversation_id' => $conversation->id,
                'send_message' => 0,
            ]);
            $messages = Message::where('conversation_id', $conversation->id)->orderBy('created_at', 'desc')->get();
            //mesajları azalan oluşturma sırasına göre getir

            return response()->json([
                'user' => $user->name . '(' . $user->email . ')' . ' Kişisine Ait Hesaptasınız.',
                'user_id' => $user->id,
                'receiver_id' => User::where('id',$user_conversation_2->user_id)->first()->id,
                'logined_user_name' => $user->name,
                'receiver_name' => User::where('id', $user_conversation_2->user_id)->first()->name,
                'conversation' => $conversation->title . ' Mesaj Kutusundasınız',
                'conversation_id' => $conversation->id,
                'send_message' => $user_conversation_1->send_message,
                'messages' => $messages
            ]);
        } else {
            $user_conversation = UserConversation::where('user_id', $user->id)->where('conversation_id', $single_chat->id)->first();
            //Giriş Yapan kişinin tekli sohbette mesaj atma yetkisi varmı yokmu ona bakmak için çağırdık.
            $receiver_conversation = UserConversation::where('user_id', '!=', $user->id)->where('conversation_id', $single_chat->id)->pluck('user_id')->first();
            //Giriş yapan kişinin tekli sohbette adının gözükmemesi,
            //Örn:normalde tabloda (Berke Akçakır ve Hakan Güler Sohbeti yazıyor),
            //biz burada o sohbete ait ve giriş yapmayan kişinin adını alıp sohbete yazdırıyoruz.

            $messages = Message::where('conversation_id', $single_chat->id)->orderBy('created_at', 'desc')->get();
            //mesajları azalan oluşturma sırasına göre getir

            return response()->json([
                'user' => $user->name . '(' . $user->email . ')' . ' Kişisine Ait Hesaptasınız.',
                'user_id' => $user->id,
                'user_name'=>$user->name,
                'receiver_id' => User::where('id',$receiver_conversation)->first()->id,
                'receiver_name' => User::where('id', $receiver_conversation)->first()->name,
                'conversation' => $single_chat->title . ' Mesaj Kutusundasınız',
                'conversation_id'=>$single_chat->id,
                'send_message' => $user_conversation->send_message,
                'messages' => $messages
            ]);
        }

    }
}
