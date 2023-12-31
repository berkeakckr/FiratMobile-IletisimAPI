<?php

use App\Events\SendMessageEvent;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::post('register',[\App\Http\Controllers\ApiController::class,'create']); //Kullanıcı Oluşturma

Route::post('login',[\App\Http\Controllers\ApiController::class,'login']);  //Kullanıcı girişi
Route::middleware('auth:api')->group(function()
{
    Route::controller(UserController::class)->group(function (){
        Route::get('/user','index');//Anasayfa(kişiye ait derslerin görüntülendiği sayfa)
        Route::get('/user/groupchats','getGroupChats');//Ders Sohbetleri
        Route::get('/user/deneme','dersler');//Ders Sohbetleri
        Route::get('/user/singlechats','getsingleChats');//Tekli Sohbetler
        Route::get('/user/academics','academicsList');//Akademisyenler Listesi
        Route::get('/user/groupchat/{conversation_id}','messages');//Ders sohbeti İçerisindeki Mesajlar sayfası
        Route::get('/user/chatlist/{conversation_id}','getChatList');//Sohbet içerisindeki kişileri listelemek için
        Route::get('/user/userinfo/{user_id}','getUserInfo');//Sohbet içerisinde tıklanan kişiyi listelemek için
        Route::get('/user/singlechat/{user_id}','checkUsertoUserChat');//Kişiden kişiye mesaj kontrolü için gerekli conversation ve
        //Route::get('/user/danisman','getDanismanList');
        Route::get('/user/danisman','danisman');
        //user_conversation tablolarını oluşturmak ve o sohbet mesajlarını görüntülemek için
        Route::post('/user/message/{conversation_id}','messageCreate');//Grup veya Özel Sohbette mesaj oluşturmak için

        //Route::delete('/user/message/{conversation_id}/{id}','messageDelete');//Grup veya Özel Sohbet içerisindeki Mesajı Silmek için
        //Route::post('/user/update_send_message/{user_conversation_id}','updateSendMessage');
        Route::post('/user/update_everyone_chat/{conversation_id}','everyone_Chat');
        //sohbetteki kişinin mesaj atıp atmama durumunu güncellemek için oluşturulan route
    });

});
Route::post('/message', function (Request $request) {
    $message = $request->input('message');
    app('socketio')->emit('message', $message); // Socket.IO'ya mesajı iletiyoruz
    return response()->json(['success' => $message]);
});
/*Route::post('/message', function (Request $request) {
    $message = $request->input('message');
    event(new SendMessageEvent($message));
    return response()->json(['success' => $message]);

});*/
/*Route::post('/messagee/', function (Request $request) {
    $message = $request->input('message');

    // Mesajı Socket.IO sunucusuna göndermek için bir olay tetikleyin
    event(new SendMessageEvent($message));

    return response()->json(['success' => true]);
});*/
