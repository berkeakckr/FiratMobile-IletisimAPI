<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\UserConversation;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get() metodu
        $message = Message::all();
        if ($message == '[]'){
            return response()->json(['message'=>'Mesaj Bulunamadı']);
        }
        return $message;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
           'file' => 'mimes:jpeg,png,jpg|max:3072'
        ]);
      $user_type =UserConversation::where('user_id',$request->user_id)->where('conversation_id',$request->conversation_id)->first()->status;
      $conversation= Conversation::where('id',$request->conversation_id)->first();
      $user_conversation=UserConversation::where('conversation_id',$request->conversation_id->pluck('user_id'))->get();
        //$user = Auth::user();
        $user= User::find($request->user_id);
    if($user_type=='is_akademisyen')
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
        $message->user_id = $request->user_id;  //$user->id
        $message->conversation_id = $request->conversation_id;
        $conversation= Conversation::find($request->conversation_id);

        $message->save();

        $notification = Notification::create([
            'message_id' => $message->id,
            'user_id' => $user->id,
            //'reached' => $data['email'],
            // 'readed' => $data['type']
        ]);

        return response()->json(['message'=>'Mesaj Başarılı Bir Şekilde Oluşturuldu']);
    }
    if($user_type=='is_ogrenci'&& $conversation->type==0 && $user_conversation->status=='is_akademisyen'
        && Auth::id()!=$user_conversation->user_id)
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
        $message->user_id = $request->user_id;  //$user->id
        $message->conversation_id = $request->conversation_id;
        $conversation= Conversation::find($request->conversation_id);

        $message->save();

        $notification = Notification::create([
            'message_id' => $message->id,
            'user_id' => $user->id,
            //'reached' => $data['email'],
            // 'readed' => $data['type']
        ]);

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get()
        $message = Message::find($id);
        if (!$message){
            return response()->json([
                'status' => 401,
                'message' => 'Mesaj Bulunamadı.'
            ]);
        }
        return $message;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //put()
        $message = Message::findOrFail($id);
        $message->text = $request->text;
        if($request->hasFile('file')){
            $imageName=time().rand(1,1000).'.'.$request->file->getClientOriginalExtension();
            $request->file->move(public_path('images'),$imageName);
            $message->file='images/'.$imageName;
        }
        $message->user_id = $request->user_id;  //$user->id
        $message->conversation_id = $request->conversation_id;
        $message->save();
        return response()->json(['message'=>'Mesaj Başarılı Bir Şekilde Güncellendi']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete()
        $message = Message::destroy($id);
        if (!$message){
            return response()->json([
                'status'=>'400',
                'message'=>'Mesaj Bulunamadı'
            ]);
        }
        return response()->json(['message'=>'Mesaj Başarılı bir şekilde silindi']);
    }
}
