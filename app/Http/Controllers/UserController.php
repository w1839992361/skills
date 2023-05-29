<?php

namespace App\Http\Controllers;

use App\Models\Frame;
use App\Models\Photo;
use App\Models\Size;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{

    function login(Request $req){
        $data = $req->only("email","password");

        $val = Validator::make($data,[
            "email"=>"required|email",
            "password"=>"required"
        ]);

        if($val->fails()){
            return response()->json([
                "msg"=>"data cannot be processed"
            ],422);
        }

        if(Auth::guard("user_web")->attempt($data)){
            $user = Auth::guard("user_web")->user();
            $user->token = md5($req->email);
            $user->save();
            return response()->json([
             "msg"=>"success",
                "data"=>[
                    "id"=>$user->id,
                    "email"=>$user->email,
                    "username"=>$user->username,
                    "token"=>$user->token,
                    "create_time"=>$user->create_time,
                ]
            ]);
        }

        return response()->json([
            "msg"=>"user credentials are invalid"
        ],401);
    }

    function register(Request $req){
        $data = $req->only("email","username","password","repeat_password");

        $val = Validator::make($data,[
            "email"=>"required|email|unique:users",
            "username"=>"required",
            "password"=>"required",
            "repeat_password"=>"required|same:password"
        ]);

        if($val->fails()){
            if($val->errors()->first() == 'The email has already been taken.'){
                return response()->json([
                    "msg"=>"email has already been used"
                ],422);
            }else{
                return response()->json([
                    "msg"=>"data cannot be processed"
                ],422);
            }
        }

        $row = User::create([
            "email"=>$req->email,
            "username"=>$req->username,
            "password"=> Hash::make($req->password),
            "create_time"=>date("Y-m-d h:m")
        ]);

        if($row){
            return response()->json([
                "msg"=>"success",
                "data"=>[
                    "id"=>$row->id,
                    "email"=>$row->email,
                    "full_name"=>$row->full_name,
                    "create_time"=>$row->create_time
                ]
            ]);
        }
    }

    function logout(){
        $user = Auth::user();
        $user->token = null;
        $user->save();
        return response()->json([
            "msg"=>"success"
        ]);
    }

    function resetPassword(Request $req){
        $user = Auth::user();
        $data =  $req->only("original_password","new_password","repeat_password");
//        $da = [
//            "email"=>$user->email,
//            "password"=>$req->original_password
//        ];
        $val = Validator::make($data,[
            "original_password"=>"required",
            "new_password"=>"required",
            "repeat_password"=>"required|same:new_password",
        ]);
//        if($val->fails() || !Auth::guard("user_web")->attempt($da)){
        if($val->fails()){
            return response()->json([
                "msg"=>"data cannot be processed"
            ],422);
        }
        $user->password = Hash::make($req->new_password);
        $user->save();
        return response()->json([
            "msg"=>"success",
            "data"=>[
                "id"=>$user->id,
                "email"=>$user->email,
                "username"=>$user->username,
                "create_time"=>$user->create_time,
            ],
        ]);
    }


    //
    function getAllUsers(){
        $users = User::all();
        return response()->json([
            "msg"=>"success",
            "data"=> $users
        ]);
    }

    function getAllUsersCart(){
        $user = User::all();
        $data = [];
        foreach ($user as $item){
            $photos = Photo::where("user_id",$item->id)->where("status","cart")->get();
            foreach ($photos as $child){
                if($child->frame_id){
                    $frame = Frame::find($child->frame_id);
                    $child->frame_price = $frame->price/100;
                    $child->frame_name = $frame->name;
                }else{
                    $child->frame_price = 0;
                    $child->frame_name = "no frame";
                }
                $size = Size::find($child->size_id);
                $child->size = $size->size;
                $child->print_price = $size->price/100;
            }
            $total = 0;
            foreach ($photos as $child){
                $total+= $child->frame_price+$child->print_price;
            }
            $item->cart_total =$total;
            if($total >0){
                array_push($data,$item);
            }
        }
        unset($item);
        return response()->json([
            "msg"=>"success",
            "data"=> $data
        ]);
    }

    function resetUserById($id){
        $user = User::find($id);
        if(!$user) return response()->json(["msg"=>"not found"],404);
        $pwd = $this->randPassword(8);
        $user->update(["password"=> Hash::make($pwd)]);
        return response()->json([
            "msg"=>"success",
            "data"=>[
                "id"=>$user->id,
                "password"=>$pwd
            ]
        ]);
    }

    function deleteUserById($id){
        $user = User::find($id);
        if(!$user) return response()->json(["msg"=>"not found"],404);
        $user->delete();
        return  response()->json(["msg"=>"success"]);
    }

    function resetUserCartById($id){
        $user = User::find($id);
        if(!$user) return response()->json(["msg"=>"not found"],404);
        $user->update(['cart_total' => null]);
        Photo::where("user_id",$id)->where("status","cart")->delete();
        return response()->json([
            "msg"=>"success"
        ]);
    }
}
