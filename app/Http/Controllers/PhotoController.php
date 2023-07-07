<?php

namespace App\Http\Controllers;

use App\Models\Frame;
use App\Models\Photo;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    //

    function getMyPhotos()
    {
        $user = Auth::user();
        $photos = Photo::where("user_id", $user->id)->get(['id', 'edited_url', 'original_url', 'framed_url', 'status']);;
        return $this->successResponse($photos);
    }

    function editPhotoById(Request $req, $id)
    {
        $photo = Photo::find($id);
        if (!$photo) return;
        $file = url('/public/storage/' . $req->file('image')->storePublicly('/'));
        $photo->update(["original_url" => $file]);
        return $this->successResponse();
    }


    function uploadPhoto(Request $req)
    {
        $data = $req->only("image", "size_id");
        $val = Validator::make($data, [
            "image" => "required|image|mimes:jpg,png,jpeg",
            "size_id" => "required"
        ]);

        if ($val->fails()) {
            return $this->dataUnprocessedResponse();
        }

        $size = Size::find($req->size_id);
        if (!$size) return $this->notFoundResponse();
        $url = url('/public/storage/' . $req->file("image")->storePublicly('/'));

        $photo = Photo::create([
            "original_url" => $url,
            "status" => "uploaded",
            "size_id" => $req->size_id,
            "user_id" => Auth::user()->id,
        ]);

        return $this->successResponse([
            "original_url" => $photo->original_url,
            "edited_url" => null,
            "framed_url" => null,
            "status" => $photo->status
        ]);
    }

    function deletePhotoById($id)
    {
        $photo = Photo::find($id);
        if (!$photo || $photo->status == "order") {
            return $this->notFoundResponse();
        }

        $photo->delete();

        return $this->successResponse();
    }

    function getMyPhotoBySizeId($id)
    {
        $user = Auth::user();

        $photos = Photo::where("size_id", $id)->where("user_id", $user->id)->where("status", "uploaded")->get(["id", "edited_url", "original_url", "framed_url", "status"]);
        if (!count($photos)) return $this->notFoundResponse();

        return $this->successResponse($photos);
    }


    function setFrame($photo_id, $frame_id = null, Request $req)
    {
        // 验证上传的图片文件
        $val = Validator::make($req->only("image"), [
            "image" => "required|image|mimes:jpg,png,jpeg"
        ]);

        // 如果验证失败，则返回错误响应
        if ($val->fails()) {
            return $this->dataUnprocessedResponse();
        }
        $photo = Photo::find($photo_id);
        $frame = Frame::find($frame_id);

        // 如果照片不存在或状态为“order”，则返回“未找到”响应
        if (!$photo || $photo->status == 'order') {
            $this->notFoundResponse();
        }
        // 如果未指定相框ID，则返回成功响应，不设置相框
        if (!$frame_id) {
            $photo->framed_url = null;
            $photo->frame_id = null;
            $photo->save();
            return $this->successResponse(["id" => $photo->id, "frame_url" => null]);
        }

        // 如果照片和相框尺寸相同，则设置相框并返回成功响应
        if ($photo->size->size === $frame->size->size) {
            $url = url('public/storage/' . $req->file("image")->storePublicly("/"));
            $photo->framed_url = $url;
            $photo->frame_id = $frame->id;
            $photo->save();
            return $this->successResponse(["id" => $photo->id, "frame_url" => $photo->framed_url]);
        }

        // 如果照片和相框尺寸不同，则返回成功响应，不设置相框
        $photo->framed_url = null;
        $photo->frame_id = null;
        $photo->save();
        return $this->successResponse(["id" => $photo->id, "frame_url" => null]);
    }


    function getCart()
    {
        $user = Auth::user();

        $photos = Photo::where("user_id", $user->id)->where("status", "cart")->get()->map(function ($item) {
            $size = Size::find($item->size_id);
            $item->size = $size->size;
            $item->print_price = $size->price / 100;
            if ($item->frame_id) {
                $frame = Frame::find($item->frame_id);
                $item->frame_name = $frame->name;
                $item->frame_price = $frame->price / 100;
            } else {
                $item->frame_name = "no frame";
                $item->frame_price = 0;
            }
            return $item;
        });

        return $this->successResponse($photos);
    }


    function appendToCart(Request $req)
    {
        // 从请求数据中获取照片ID列表
        $data = $req->only("photo_id_list");
        // 使用 Laravel 自带的验证器验证数据格式是否正确
        $val = Validator::make($data, [
            "photo_id_list" => "required|array"
        ]);
        // 如果数据格式不正确，就返回一个未处理的响应
        if ($val->fails()) {
            return $this->dataUnprocessedResponse();
        }

        $photoIds = $req->photo_id_list;
        $photos = Photo::whereIn('id', $photoIds)->where('status', 'uploaded')->get();

        // 从数据库中查询所有上传状态的照片，并根据它们的数量和请求中的照片ID数量进行比较
        if (count($photos) !== count($photoIds)) {
            return $this->notFoundResponse();
        }
        // 遍历所有找到的照片，并将它们的状态更新为 "cart"
        $photos->each(function ($photo) {
            $photo->update(["status" => "cart"]);
        });
        // 返回一个成功的响应
        return $this->successResponse();
    }

}
