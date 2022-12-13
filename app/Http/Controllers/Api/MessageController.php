<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
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
        $user = Auth::user();
        //post metodu
        $message = new Message();
        $message->text = $request->text;
        if($request->hasFile('file')){
            $imageName=time().rand(1,1000).'.'.$request->file->getClientOriginalExtension();
            $request->file->move(public_path('images'),$imageName);
            $message->file='images/'.$imageName;
        }
        $message->user_id = $request->user_id;  //$user->id
        $message->conversation_id = $request->conversation_id;
        $message->save();
        return response()->json(['message'=>'Mesaj Başarılı Bir Şekilde Oluşturuldu']);
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
