<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $conversation = Conversation::all();
        if ($conversation == '[]'){
            return response()->json(['message'=>'Sohbet Bulunamadı']);
        }
        return $conversation;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        if (!$conversation){
            return response()->json([
                'status' => 401,
                'message' => 'Sohbet Bulunamadı.'
            ]);
        }
        return $conversation;
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
        $conversation = Conversation::findOrFail($id);
        $conversation->title=$request->title;
        $conversation->description=$request->description;
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
