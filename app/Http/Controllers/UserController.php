<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // 用户登录
    function login(Request $req){
        $data = $req->only("email","password");

        $val = Validator::make($data,[
            "email"=>"required|email",
            "password"=>"required"
        ]);

        if($val->fails()){
            return $this->dataUnprocessedResponse();
        }

        if(Auth::guard("user_web")->attempt($data)){
            $user = Auth::guard("user_web")->user();
            $user->token = md5($user->email);
            $user->save();
            return $this->successResponse([
                "id"=>$user->id,
                "email"=>$user->email,
                "username"=>$user->username,
                "token"=>$user->token,
                "create_time"=>$user->create_time,
            ]);
        }

        return $this->customResponse("user credentials are invalid",401);
    }

    // 注册用户
    function register(Request $req){
        $data = $req->only("email","username","password","repeat_password");

        $val = Validator::make($data,[
            "email"=>"required|email|unique:users",
            "username"=>"required",
            "password"=>"required",
            "repeat_password"=>"required|same:password"
        ]);

        if($val->fails()){
            $errorMsg = $val->errors()->first();
            if($errorMsg == 'The email has already been taken.'){
                return $this->customResponse("email has already been used",422);
            }else{
                return $this->dataUnprocessedResponse();
            }
        }

        $row = User::create([
            "email"=>$req->email,
            "username"=>$req->username,
            "password"=> Hash::make($req->password),
            "create_time"=>date("Y-m-d h:m")
        ]);

        if($row){
            return $this->successResponse([
                "id"=>$row->id,
                "email"=>$row->email,
                "full_name"=>$row->full_name,
                "create_time"=>$row->create_time
            ]);
        }
    }

    // 用户退出
    function logout(){
        $user = Auth::user();
        $user->token = null;
        $user->save();
        return $this->successResponse();
    }

    // 重置密码
    function resetPassword(Request $req){
        $user = Auth::user();
        $data = $req->only("original_password","new_password","repeat_password");
        $val = Validator::make($data,[
            "original_password"=>"required",
            "new_password"=>"required",
            "repeat_password"=>"required|same:new_password",
        ]);
        // Hash check 检查原密码是否正确 题目无要求 只是做一下拓展这样更加合理
//        if($val->fails() || !Hash::check($req->original_password, $user->password)){
        if($val->fails()){
            return $this->dataUnprocessedResponse();
        }
        $user->update(["password"=>Hash::make($req->new_password)]);

        return $this->successResponse([
            "id"=>$user->id,
            "email"=>$user->email,
            "username"=>$user->username,
            "create_time"=>$user->create_time,
        ]);
    }

    // 管理员获取所有用户
    function getAllUsers(){
        $users = User::all()->map(function ($item){
            Photo::where("user_id",$item->id)->where('status',"cart")->get()->map(function ($photo) use ($item){
                $item->cart_total += $photo->size->price/100 + ($photo->frame ? $photo->frame->price/100 : 0);
            });
            if($item->cart_total >0){
                return $item;
            }else{
                return [];
            }
        })->filter();
        return $this->successResponse($users);
    }

    // 管理员获取所有用户的购物车
    function getAllUsersCart(){
        $user = User::all();
        $data = [];
        foreach ($user as $item){
            $photos = Photo::where("user_id",$item->id)->where("status","cart")->get();
            $item->cart_total = 0;
            foreach ($photos as $child){
                $child->frame_price = $child->frame ?$child->frame->price/100 :0;
                $child->print_price = $child->size->price/100;
                $item->cart_total+= $child->frame_price+$child->print_price;
            }

            if($item->cart_total >0){
                array_push($data,$item);
            }
        }
        unset($item);
        return $this->successResponse($data);
    }

    // 管理员根据id中指密码
    function resetUserById($id){
        $user = User::find($id);
        if(!$user) return $this->notFoundResponse();

        $pwd = $this->randPassword(8);
        $user->update(["password"=> Hash::make($pwd)]);

        return $this->successResponse([
            "id"=>$user->id,
            "password"=>$pwd
        ]);
    }

    // 管理员根据id删除用户
    function deleteUserById($id){
        $user = User::find($id);
        if(!$user) return $this->notFoundResponse();
        $user->delete();
        return $this->successResponse();
    }

    // 管理员重置用户购物车
    function resetUserCartById($id){
        $user = User::find($id);
        if(!$user) return $this->notFoundResponse();
        $user->update(['cart_total' => null]);
        Photo::where("user_id",$id)->where("status","cart")->delete();
        return $this->successResponse();
    }

}
