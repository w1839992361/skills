<?php

namespace App\Http\Controllers;

use App\Models\Frame;
use App\Models\Photo;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    //

    function getMyPhotos(){
        $user = Auth::user();
        $photos = Photo::where("user_id",$user->id)->get();
        $data = [];
        foreach ($photos as $item){
            $data[]=[
                "id"=>$item->id,
                "edited_url"=>$item->edited_url,
                "original_url"=>$item->original_url,
                "framed_url"=>$item->framed_url,
                "status"=>$item->status,
            ];
        }
        return response()->json([
            "msg"=>"success",
            "data"=>$data
        ]);
    }

    function editPhotoById(Request $req,$id){
        $photo = Photo::find($id);
        if(!$photo) return;
        $file = url('/public/storage/'.$req->file('image')->storePublicly('/'));
        $photo->original_url = $file;
        $photo->save();
        return response()->json(["msg"=>"success"]);
    }


    function uploadPhoto(Request $req){
        $data = $req->only("image","size_id");
        $val = Validator::make($data,[
            "image"=>"required|image|mimes:jpg,png,jpeg",
            "size_id"=>"required"
        ]);

        if($val->fails()){
            return response()->json(["msg"=>"data cannot be processed"],422);
        }

        $size = Size::find($req->size_id);
        if(!$size) return response()->json(["msg"=>"not found"],404);
        $path = url('/public/storage/'.$req->file("image")->storePublicly('/'));
//        $filename = time().$image->getClientOriginalName();
//        $path = url($image->move("photo",$filename));

        $user_id = Auth::user()->id;
        $photo = Photo::create([
            "original_url"=>$path,
            "status"=>"uploaded",
            "size_id"=>$req->size_id,
            "user_id"=>$user_id,
        ]);

        return response()->json([
            "msg"=>"success",
            "data"=>[
                "original_url"=>$photo->original_url,
                "edited_url"=>null,
                "framed_url"=>null,
                "status"=>$photo->status
            ]
        ]);
    }

    function deletePhotoById($id){
        $photo = Photo::find($id);
        if(!$photo || $photo->status =="order"){
            return response()->json([
                "msg"=>"not found"
            ],404);
        }

        $photo->delete();

        return response()->json([
            "msg"=>"success",
        ]);
    }

    function  getMyPhotoBySizeId($id){
        $user = Auth::user();
        $photos = Photo::where("size_id",$id)->where("user_id",$user->id)->where("status","uploaded")->get();
        if(!count($photos)) return response()->json(["msg"=>"not found"],404);
        $data = [];
        foreach ($photos as $item){
            $data[]=[
                "id"=>$item->id,
                "edited_url"=>$item->edited_url,
                "original_url"=>$item->original_url,
                "framed_url"=>$item->framed_url,
                "status"=>$item->status,
            ];
        }
        return response()->json([
            "msg"=>"success",
            "data"=>$data,
        ]);
    }


    function setFrame($photo_id,$frame_id = null,Request $req){
        $data = $req->only("image");
        $val = Validator::make($data,[
            "image"=>"required|image|mimes:jpg,png,jpeg"
        ]);
        if($val->fails()){
            return response()->json([
                "msg"=>"data cannot be processed"
            ],422);
        }
        $photo = Photo::find($photo_id);
        $frame = Frame::find($frame_id);
        if(!$photo) return response()->json(["msg"=>"not found"],404);
        if (!$frame){
            return response()->json(["msg"=>"success","data"=>["id"=>1,"frame_url"=>null]]);
        }
        if ($photo->size->size === $frame->size->size){
            $photo->frame_id = $frame->id;
            $f= url('public/storage/'.$req->file("image")->storePublicly("/"));
            $photo->framed_url = $f;
            $photo->save();
            return $this->success(["id"=>rand(1,9),"frame_url"=>$f]);
        }else{
            return $this->success(["id"=>rand(1,9),"frame_url"=>null]);
        }
    }


    function getCart(){
        $user = Auth::user();

        $photos = Photo::where("user_id",$user->id)->where("status","cart")->get();

        foreach ($photos as $item){
            $size = Size::find($item->size_id);
            $item->print_price = $size->price/100;
            $item->size = $size->size;
            if($item->frame_id){
                $frame = Frame::find($item->frame_id);
                $item->frame_price = $frame->price/100;
                $item->frame_name = $frame->name;
            }else{
                $item->frame_price = 0;
                $item->frame_name = "no frame_name";
            }
        }
        unset($item);
        return response()->json([
            "msg"=>"success",
            "data"=>$photos
        ]);
    }



    function appendToCart(Request $req){
        $data =  $req->only("photo_id_list");
        $val = Validator::make($data,[
            "photo_id_list"=>"required|array"
        ]);
        if($val->fails()){
            return response()->json(["msg"=>"data cannot be processed"],422);
        }
        foreach ($req->photo_id_list as $item){
            $photo = Photo::find($item);
            if(!$photo || $photo->status !=='uploaded') return  response()->json(["msg"=>"not found"],404);
            $photo->status = "cart";
            $photo->save();
        }
        return response()->json([
            "msg"=>"success"
        ]);
    }

}
