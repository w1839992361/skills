<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function success($data){
        return response()->json(["msg"=>"success","data"=>$data]);
    }

    function randPassword($count = 8){
        $str = "qwertyuiopasdfghjklzxcvbnm123456789QWERTYUIOPASDFGHJKLZXCVBNM";
        $pwd = '0';
        for ($i=0;$i<$count-1;$i++){
            $pwd .= $str[rand(0,strlen($str)-1)];
        }
        return $pwd;
    }
}
