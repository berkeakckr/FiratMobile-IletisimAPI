<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\User;
use App\Models\UserConversation;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Http\Request;
use mysql_xdevapi\Collection;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        //$user = Auth::user();
        $user= User::find($id);
        $user_conversations = UserConversation::whereIn('user_id', $user->get_userConversation()->pluck('user_id'))->get();
        $conversations = Conversation::whereIn('id',$user_conversations->pluck('conversation_id'))->get();
     if ($conversations == '[]'){
            return response()->json(['message'=>'Bu Kişiye Ait Sohbet Bulunamadı.']);
        }
      else{
          return response()->json($conversations);
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //$user = Auth::user();
        //$user= User::find(1);
        $request->validate([
           'title'=>'required|min:1|max:100',
           'description'=>'max:1000',
            'file' => 'mimes:jpeg,png,jpg|max:3072'
        ]);
        $conversation = new Conversation();
        $conversation->title = $request->title;
        $conversation->description = $request->description;
        $conversation->type = $request->type;
        if ($request->type == '1'){
            if($request->hasFile('file')){
                $imageName=time().rand(1,1000).'.'.$request->file->getClientOriginalExtension();
                $request->file->move(public_path('images'),$imageName);
                $conversation->file='images/'.$imageName;
            }
        }
        $conversation->everyone_chat = $request->everyone_chat;
        $conversation->save();

      /*  $userconversation = UserConversation::create([
            'user_id' => $user->id,
            'conversation_id' => $conversation->id
           // 'is_admin' => ,
           // 'send_message' =>
        ]);*/
        return response()->json(['message'=>'Sohbet Başarılı Bir Şekilde Oluşturuldu']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $conversation = Conversation::find($id);
        $messages = Message::where('conversation_id',$conversation->id)->get();
        if (!$conversation){
            return response()->json([
                'status' => 401,
                'message' => 'Sohbet Bulunamadı.'
            ]);
        }
        $messagescount = $messages->count();
        return [$messages,$messagescount];
    }
    public function Get_Users($id)
    {
        $conversation = Conversation::find($id);
        $user_conversations = UserConversation::whereIn('conversation_id', $conversation->user_conversation()->pluck('conversation_id'))->get();
        $users = User::whereIn('id',$user_conversations->pluck('user_id'))->get();
        if (!$conversation){
            return response()->json([
                'status' => 401,
                'message' => 'Sohbet Bulunamadı.'
            ]);
        }
        //$users = User::whereIn('user_id', $user_conversations->userss)->get();;
        return response()->json($users);

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
        $request->validate([
            'title'=>'min:1|max:100',
            'description'=>'max:1000',
            'file' => 'mimes:jpeg,png,jpg|max:3072'
        ]);
        $conversation = Conversation::findOrFail($id);
        $conversation->description=$request->description;
        $conversation->type=$request->type;
        $conversation->everyone_chat = $request->everyone_chat;
        $conversation->save();
        return response()->json(['message'=>'Sohbet Başarı İle Güncellendi']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $conversation = Conversation::destroy($id);
        if (!$conversation){
            return response()->json([
                'status'=>'400',
                'message'=>'Sohbet Bulunamadı'
            ]);
        }
        return response()->json(['message'=>'Sohbet Başarılı bir şekilde silindi']);
    }
}
