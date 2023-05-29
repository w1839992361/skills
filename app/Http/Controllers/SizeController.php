<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    //
    function getAllSize(){
        $size = Size::all();
        foreach ($size as $item){
            $item->price = $item->price/100;
        }
        unset($item);
        return response()->json([
            "msg"=>"success",
            "data"=> $size
        ]);
    }

    function updateSize(Request $req,$id){
        $size = Size::find($id);
        if (!$size) return response()->json(["msg"=>"not found"],404);
        if($req->get("price") <0) return response()->json([],422);
        $size->update(["price"=>$req->get("price")*100]);
        return response()->json([
            "msg"=>"success",
            "data"=>$size
        ]);
    }
}
