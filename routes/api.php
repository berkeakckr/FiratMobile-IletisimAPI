<?php

use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ConversationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::post('register',[\App\Http\Controllers\ApiController::class,'create']);
Route::post('login',[\App\Http\Controllers\ApiController::class,'login']);


Route::middleware('auth:api')->group(function()
{

    Route::get('/user',function()
    {
        return \Illuminate\Support\Facades\Auth::user();
    });

});

Route::controller(MessageController::class)->group(function (){
    Route::get('/messages','index');
    Route::post('/message','store');
    Route::get('/message/{id}','show');
    Route::put('/message/{id}','update');
    Route::delete('/message/{id}','destroy');
});
Route::controller(ConversationController::class)->group(function (){
    Route::get('/conversations','index');
    Route::post('/conversation','store');
    Route::get('/conversation/{id}','show');
    Route::get('/conversation/users/{id}','Get_Users');
    Route::put('/conversation/{id}','update');
    Route::delete('/conversation/{id}','destroy');
});

//Route::get('test',[\App\Http\Controllers\TestController::class,'index']);
//Route::post('post',[\App\Http\Controllers\TestController::class,'post']);
//=======
//Route::controller(MessageController::class)->group(function (){
//    Route::get('/messages','index');
//    Route::post('/message','store');
//    Route::get('/message/{id}','show');
//    Route::put('/message/{id}','update');
//    Route::delete('/message/{id}','destroy');
//});
// 607f074dd56df2e2ad90ef79ae809416995cdc4b
