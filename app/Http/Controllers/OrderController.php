<?php

namespace App\Http\Controllers;

use App\Models\Frame;
use App\Models\Order;
use App\Models\Photo;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use function Illuminate\Events\queueable;

class OrderController extends Controller
{
    // 管理员端获取所有订单信息
    function getAllOrders()
    {
        $orders = Order::with("photos")->get();
        $data = $orders->map(function ($order) {
            $order->total = $order->photos->reduce(function ($carry, $photo) {
                $frame_price = $photo->frame ? $photo->frame->price / 100 : 0;
                $frame_name = $photo->frame ? $photo->frame->name : "no frame";
                $size = Size::find($photo->size_id);
                $photo->size = $size->size;
                $photo->print_price = $size->price / 100;
                $photo->frame_price = $frame_price;
                $photo->frame_name = $frame_name;
                return $carry + $photo->print_price + $frame_price;
            }, 0);
            return $order;
        });
        return $this->successResponse($data);
    }

    // 管理员端通过id取消订单
    function cancelOrderById($id)
    {
        $order = Order::where("status", "Valid")->where("id", $id)->get();
        if (!count($order)) return $this->notFoundResponse();
        $order[0]->update(["status" => "Invalid"]);
        return $this->successResponse();
    }

    // 管理员端通过id完成订单
    function completeOrderById($id)
    {
        $order = Order::where("status", "Valid")->where("id", $id)->get();
        if (!count($order)) return $this->notFoundResponse();
        $order[0]->update(["status" => "Completed"]);
        return $this->successResponse();
    }

    // 客户端获取用户自己的订单
    function getMyOrder()
    {
        // 获取当前登录用户
        $user = Auth::user();
        $userId = $user->id;
        // 模型里面定义的一对多  一个订单有很多照片 hasMany
        $orders = Order::with("photos")->whereHas("photos", function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();
        $data = $orders->map(function ($order) {
            $order->total = $order->photos->reduce(function ($carry, $photo) use ($order) {
                $frame_price = $photo->frame ? $photo->frame->price / 100 : 0;
                $frame_name = $photo->frame ? $photo->frame->name : "no frame";
                $size = Size::find($photo->size_id);
                $photo->size = $size->size;
                $photo->print_price = $size->price / 100;
                $photo->frame_price = $frame_price;
                $photo->frame_name = $frame_name;
                return $carry + $photo->print_price + $frame_price;
            }, 0);
            return $order;
        });
        return $this->successResponse($data);
    }

    // 客户端创建订单
    function createOrder(Request $req)
    {
        $data = $req->only("full_name", "phone_number", "shipping_address", "card_number", "name_on_card", "exp_date", "cvv", "photo_id_list");

        // 验证请求数据的有效性
        $val = Validator::make($data, [
            "full_name" => "required",
            "phone_number" => "required",
            "shipping_address" => "required",
            "card_number" => "required",
            "name_on_card" => "required",
            "exp_date" => "required|date_format:Y-m-d",
            "cvv" => "required|size:3",
            "photo_id_list" => "required|array"
        ]);

        if ($val->fails()) {
            return $this->dataUnprocessedResponse();
        }

        // 获取请求中的照片 ID 列表，并查询出对应的照片对象
        $photoIds = $req->photo_id_list;
        $photos = Photo::whereIn("id", $photoIds)->where("status", "cart")->get();
        if (count($photoIds) !== count($photos)) {
            return $this->notFoundResponse();
        }


        // 创建订单
        $order = Order::create([
            "full_name" => $req->full_name,
            "phone_number" => $req->phone_number,
            "shipping_address" => $req->shipping_address,
            "card_number" => $req->card_number,
            "name_on_card" => $req->name_on_card,
            "exp_date" => $req->exp_date,
            "cvv" => $req->cvv,
//            "total" => count($req->photo_id_list),
            "order_placed" => date("Y-m-d H:i"),
//            "status" => "Valid",
        ]);

        // 将照片与订单关联，并更新照片状态为已下单
        foreach ($photos as $photo) {
            /*
             * 这里可以手动赋值外键 也可以使用关联方法 两种方法都可
             * */
            // $photo->order_id = $order->id;
            $photo->order()->associate($order);
            $photo->update(["status" => "order"]);
        }
        // 如果订单创建成功，则返回成功响应，并包含订单及其照片的详细信息
        if ($order) {
            $res = Order::with("photos")->where("id", $order->id)->get();
            $data = $res->map(function ($order) {
                $order->total = $order->photos->reduce(function ($carry, $photo) use ($order) {
                    $frame_price = $photo->frame ? $photo->frame->price / 100 : 0;
                    $frame_name = $photo->frame ? $photo->frame->name : "no frame";
                    $size = Size::find($photo->size_id);
                    $photo->size = $size->size;
                    $photo->print_price = $size->price / 100;
                    $photo->frame_price = $frame_price;
                    $photo->frame_name = $frame_name;
                    return $carry + $photo->print_price + $frame_price;
                }, 0);
                return $order;
            });
            return $this->successResponse($data);
        }
    }

    // 客户端取消
    function cancelOrder($id)
    {
        $order = Order::find($id);
        $photo = Photo::where("order_id", $id)->get();
        if (!$order || !$photo) return $this->notFoundResponse();
        $photo[0]->update(["status" => "Invalid"]);
        return $this->successResponse();
    }

}
