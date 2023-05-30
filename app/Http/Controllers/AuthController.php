<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // login
    function login(Request $req){
        $data = $req->only("email","password");

        $val = Validator::make($data,[
            "email"=>"required|email",
            "password"=>"required"
        ]);

        if($val->fails()){
            return $this->dataUnprocessedResponse();
        }

        if(Auth::attempt($data)){
            $user = Auth::user();
            $user->token = md5($user->email);
            $user->save();

            return $this->successResponse([
                "id"=>$user->id,
                "email"=>$user->email,
                "full_name"=>$user->full_name,
                "token"=>$user->token,
                "created_at"=>$user->created_at,
//              "create_time"=>$user->create_time
            ]);
        }

        return $this->customResponse("user credentials are invalid",401);
    }

    // logout
    function logout(){
        $user = Auth::user();
        $user->token = null;
        $user->save();
        return $this->successResponse();
    }


}
