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
    function  getAllOrders(){
        $orders = Order::with("photos")->get();
        foreach ($orders as $item){
            $total = 0;
            foreach ($item->photos as $child){
                $child->frame_price = $child->frame ? $child->frame->price/100 : 0;
                $child->frame_name = $child->frame ?  $child->frame->name : "no frame";
                $size = Size::find($child->size_id);
                $child->size = $size->size;
                $child->print_price = $size->price/100;
                $total += $child->print_price + $child->frame_price;
            }
            $item->total = $total;
        }
        unset($item);
        return  $this->successResponse($orders);
    }

    // 管理员端通过id取消订单
    function cancelOrderById($id){
        $order = Order::where("status","Valid")->where("id",$id)->get();
        if(!count($order)) return $this->notFoundResponse();
        $order[0]->update(["status"=>"Invalid"]);
        return $this->successResponse();
    }

    // 管理员端通过id完成订单
    function completeOrderById($id){
        $order = Order::where("status","Valid")->where("id",$id)->get();
        if(!count($order)) return $this->notFoundResponse();
        $order[0]->update(["status"=>"Completed"]);
        return  $this->successResponse();
    }

    // 客户端获取用户自己的订单
    function getMyOrder(){
        // 获取当前登录用户
        $user = Auth::user();
        $userId = $user->id;
        // 模型里面定义的一对多  一个订单有很多照片 hasMany
        $orders = Order::with("photos")->get();
        // 定义一个空数组，用于存储符合条件的订单
        $data = [];
        foreach ($orders as $order){
            // 定义一个变量，用于判断当前订单中是否有当前登录用户的照片
            $isOwn = false;
            $order->total = 0;
            foreach ($order->photos as $photo){
                if($photo->user_id == $userId){
                    // 如果当前照片属于当前登录用户，则将isOwn设为true
                    $isOwn = true;
                    break;
                }
            }
            // 如果当前订单中有当前登录用户的照片，则计算订单总价并将订单加入data数组中
            if($isOwn){
                foreach ($order->photos as $photo){
                    $size = Size::find($photo->size_id);
                    $photo->size = $size->size;
                    $photo->print_price = $size->price/100;
                    if($photo->frame_id){
                        $frame = Frame::find($photo->frame_id);
                        $photo->frame_name = $frame->name;
                        $photo->frame_price = $frame->price/100;
                    }else{
                        $photo->frame_name = "no frame";
                        $photo->frame_price = 0;
                    }
                    $order->total += $photo->print_price + $photo->frame_price;
                }
                // 将当前订单加入data数组中
                array_push($data, $order);
            }
        }
        return $this->successResponse($data);
    }

    // 客户端创建订单
    function createOrder(Request $req){
        $data = $req->only("full_name","phone_number","shipping_address","card_number","name_on_card","exp_date","cvv","photo_id_list");

        // 验证请求数据的有效性
        $val = Validator::make($data,[
            "full_name"=>"required",
            "phone_number"=>"required",
            "shipping_address"=>"required",
            "card_number"=>"required",
            "name_on_card"=>"required",
            "exp_date"=>"required|date_format:Y-m-d",
            "cvv"=>"required|size:3",
            "photo_id_list"=>"required|array"
        ]);

        if($val->fails()){
            return $this->dataUnprocessedResponse();
        }

        // 获取请求中的照片 ID 列表，并查询出对应的照片对象
        $photoIds = $req->photo_id_list;
        $photos = Photo::whereIn("id",$photoIds)->where("status","cart")->get();
        if(count($photoIds) !== count($photos)){
            return $this->notFoundResponse();
        }


        // 创建订单
        $order = Order::create([
            "full_name"=>$req->full_name,
            "phone_number"=>$req->phone_number,
            "shipping_address"=>$req->shipping_address,
            "card_number"=>$req->card_number,
            "name_on_card"=>$req->name_on_card,
            "exp_date"=>$req->exp_date,
            "cvv"=>$req->cvv,
            "total"=>count($req->photo_id_list),
            "order_placed"=>date("Y-m-d H:i"),
            "status"=>"Valid",
        ]);

        // 将照片与订单关联，并更新照片状态为已下单
        foreach ($photos as $photo){
            /*
             * 这里可以手动赋值外键 也可以使用关联方法 两种方法都可
             * */
            // $photo->order_id = $order->id;
            $photo->order()->associate($order);
            $photo->update(["status"=>"order"]);
        }

        // 如果订单创建成功，则返回成功响应，并包含订单及其照片的详细信息
       if($order){
           $res = Order::with("photos")->get();
           $data = [];
           foreach ($res as $item){
               if($item->id == $order->id){
                   $item->total = 0;
                   foreach ($item->photos as $photo){
                       $size =  Size::find($photo->size_id);
                       $photo->size = $size->size;
                       $photo->print_price = $size->price/100;

                       if($photo->frame_size){
                           $frame = Frame::find($photo->frame_id);
                           $photo->frame_name = $frame->name;
                           $photo->frame_price = $frame->price/100;
                       }else{
                           $photo->frame_name = "no frame";
                           $photo->frame_price = 0;
                       }
                       $item->total += $photo->frame_price +  $photo->print_price;
                   }
                    array_push($data,$item);
               }
           }
           unset($item);
           return $this->successResponse($data);
       }
    }

    // 客户端取消
    function cancelOrder($id){
        $order = Order::find($id);
        $photo = Photo::where("order_id",$id)->get();
        if(!$order || !$photo) return $this->notFoundResponse();
        $photo[0]->update(["status"=>"Invalid"]);
        return $this->successResponse();
    }

}
