<?php

namespace App\Http\Controllers;

use App\Helper\StatusCodes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class TestController extends BaseController
{
    public function index(){

        $veriler = [
            "name" => "Tuncay",
            "surname" => "Forma",
        ];
        $bulunamadı = "Veri bulunamadı";
      //  return view('asdda',compact('veriler'));
        if(4 == 5){
            $this->sendError(
                "Not Found",
            "Bu Mesaj Bulunamadı",
            StatusCodes::NOT_FOUND
            );


        }else{
            $this->sendResponse($veriler,"SDADAS");

        }
    }

    public function post(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=> 'required|string|between:2,100',
            'email'=> 'required|email|unique:users',
        ]);

        return response()->json("Test");

    }
}
