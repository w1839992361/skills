<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    //

    function getAllAdmins(){
        return response()->json([
           "msg"=>"success",
           "data"=>Admin::all("id","email","full_name","created_at")
        ]);
    }

    function createAdmin(Request $req){
        $data = $req->only("email","full_name","password","repeat_password");
        $val = Validator::make($data,[
            "email"=>"required|email|unique:admins",
            "full_name"=>"required",
            "password"=>"required",
            "repeat_password"=>"required|same:password",
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
        $row = Admin::create([
           "email"=>$req->email,
           "full_name"=>$req->full_name,
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

    function resetAdminPasswordById($id){
        $admin = Admin::find($id);
         if(!$admin) return response()->json(["msg"=>"not found"],404);
         $pwd = Str::random(8);
         $pwd = str_replace(substr($pwd,0,1),rand(0,9),$pwd);
         $admin->update(["password"=>Hash::make($pwd)]);
         return response()->json([
             "msg"=>"success",
             "data"=>[
                 "id"=>$admin->id,
                 "password"=>$pwd,
             ]
         ]);
    }

    function deleteAdminById($id){
        $admin = Admin::find($id);
        if(!$admin) return response()->json(["msg"=>"not found"],404);
        $admin->delete();
        return response()->json(["msg"=>"success"]);
    }

}
