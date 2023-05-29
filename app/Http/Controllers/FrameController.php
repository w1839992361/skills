<?php

namespace App\Http\Controllers;

use App\Models\Frame;
use App\Models\Size;
use Illuminate\Http\Request;

class FrameController extends Controller
{

    // getall
    function getAllFrame(){
//        //        $frames = Frame::join("sizes", "frames.size_id", "=", "sizes.id")
////            ->select("frames.id", "frames.url", "frames.price", "frames.name", "sizes.size")
////            ->get();
////        $frames = Frame::with('size')->get();
//        $frames = Frame::all();
//        foreach ($frames as $item){
//            $item->size = Size::find($item->size_id)->size;
//            $item->price = $item->price/100;
//        }
//        unset($item);
        $frames = Frame::join("sizes","frames.size_id","=","sizes.id")->select("frames.id","frames.url","frames.price","frames.name","sizes.size")->get()->map(function ($item){
            $item->price /=100;
            return $item;
        });
      return  response()->json([
          "msg"=>"success",
          "data"=>$frames
      ]);
    }

    // update
    function updateFrameById(Request $req,$id){
        $frame = Frame::find($id);
        if(!$frame) return response()->json(["msg"=>"not found"],404);
        $frame->update(["price"=>$req->get("price")*100,"name"=>$req->get("frame_name")]);
        return response()->json(["msg"=>"success","data"=>$frame]);
    }
}
