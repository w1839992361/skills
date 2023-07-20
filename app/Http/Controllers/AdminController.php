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

    function getAllAdmins()
    {
        return $this->successResponse(
            Admin::all("id", "email", "full_name", "created_at")
        );
    }

    function createAdmin(Request $req)
    {
        // 获取请求中的数据
        $data = $req->only("email", "full_name", "password", "repeat_password");
        // 使用 Laravel 自带的 Validator 类进行验证
        $val = Validator::make($data, [
            "email" => "required|email|unique:admins",
            "full_name" => "required",
            "password" => "required",
            "repeat_password" => "required|same:password",
        ]);
        if ($val->fails()) {
            $errorMsg = $val->errors()->first() == 'The email has already been taken.' ? "email has already been used" : "data cannot be processed";
            return $this->customResponse($errorMsg, 422);
        }
        $row = Admin::create([
            "email" => $req->email,
            "full_name" => $req->full_name,
            "password" => Hash::make($req->password), // 进行hash加密
            "create_time" => date("Y-m-d H:i")
        ]);
        if ($row) { // 如果创建成功返回
            return $this->successResponse([
                "id" => $row->id,
                "email" => $row->email,
                "full_name" => $row->full_name,
                "create_time" => $row->create_time
            ]);
        }
    }

    function resetAdminPasswordById($id)
    {
        // 根据id查询管理员
        $admin = Admin::find($id);
        // 如果不存在返回404
        if (!$admin) return $this->notFoundResponse();
        // 调用Controller里面的随机密码方法
        $pwd = $this->randPassword();
        // 更新
        $admin->update(["password" => Hash::make($pwd)]);
        // 返回成功信息和新密码
        return $this->successResponse([
            "id" => $admin->id,
            "password" => $pwd,
        ]);
    }

    function deleteAdminById($id)
    {
        $admin = Admin::find($id);
        if (!$admin) return $this->notFoundResponse();
        $admin->delete();
        return $this->successResponse();
    }

}
