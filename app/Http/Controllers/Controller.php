<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // 成功响应
    function successResponse($data = null)
    {
        return response()->json($data ? ["msg" => "success", "data" => $data] : ["msg" => "success",]);
    }

    // 未找到错误响应
    function notFoundResponse()
    {
        return response()->json(["msg" => "not found"], 404);
    }

    // 参数错误响应
    function dataUnprocessedResponse()
    {
        return response()->json(["msg" => "data cannot be processed"], 422);
    }

    // 自定义响应和状态码
    function customResponse($msg, $status)
    {
        return response()->json(["msg" => $msg], $status);
    }

    function randPassword($count = 8)
    {
        $str = "qwertyuiopasdfghjklzxcvbnm123456789QWERTYUIOPASDFGHJKLZXCVBNM";
        $pwd = '0';
        for ($i = 0; $i < $count - 1; $i++) {
            $pwd .= $str[rand(0, strlen($str) - 1)];
        }
        return $pwd;
    }

}
