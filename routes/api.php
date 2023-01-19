<?php

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
        Route::get('/user','index');//Anasayfa
        Route::get('/user/academics','academicsList');//Akademisyenler Listesi
        Route::get('/user/message/{conversation_id}','message');//Grup İçerisindeki Mesajlar sayfası
        Route::post('/user/message/{conversation_id}','messageCreate');//Grup İçerisinde mesaj oluşturmak için
        //Route::delete('/user/message/{conversation_id}/{id}','messageDelete');//Grup içerisindeki Mesajı Silmek için
        Route::get('/user/check/{user_id}','checkUsertoUserChat');//Kişiden kişiye mesaj kontrolü için gerekli conversation ve
                                                                            //user_conversation tablolarını oluşturmak için
    });
});

