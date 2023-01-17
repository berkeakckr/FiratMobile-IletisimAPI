<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Api\DatabaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Passport\Client;
use App\Models\UserConversation;
use App\Models\UserDers;
use App\Models\Conversation;
use App\Models\Ders;

class ApiController extends Controller
{
public function login(Request $request)
{
   // $email = $request->json()->email;
    $email = $request->email;
    $password= $request->password;


    if(Auth::attempt(['email'=>$email,'password'=>$password]))
    {
        $user = Auth::user();
        $success['token']=$user->createToken("Login")->accessToken;
        DatabaseController::check();
        return response()->json([

            'success'=>$success
        ], 200);
    }
    return response()->json([
        'error'=>'Unauthorized'
    ],401);
}

  public function create(Request $request)
    {
        $isExist=User::whereEmail($request->email)->first();
        if($isExist){
            return response()->json([
                'message'=>'Mail başkası tarafından kullanılmakta'
            ]);
        }
        $valid = validator($request->only('email', 'name', 'password','type'), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:16',
            'type' => 'required|boolean',
        ]);

        if ($valid->fails()) {
            $jsonError=response()->json($valid->errors()->all(), 400);

            return response()->json($jsonError,[])
                ;
            //return \Response::json($jsonError);

        }

        $data = request()->only('email','name','password','type');

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'type' => $data['type']
        ]);

        $client = Client::where('password_client', 1)->first();

        $request->request->add([
            'grant_type'    => 'password',
            'client_id'     => $client->id,
            'client_secret' => $client->secret,
            'username'      => $data['email'],
            'password'      => $data['password'],
            'scope'         => null,
        ]);
        $success['token']=$user->createToken("Login")->accessToken;

        return response()->json([
            'success'=>$success,'message'=>$user->name.' kişisi eklendi'
        ], 200);
    }

}
