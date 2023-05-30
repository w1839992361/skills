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
        $order[0]->update(["status"=>"Cancel"]);
        return $this->successResponse();
    }

    // 管理员端通过id完成订单
    function completeOrderById($id){
        $order = Order::where("status","Valid")->where("id",$id)->get();
        if(!count($order)) return $this->notFoundResponse();
        $order[0]->update(["status"=>"Complete"]);
        return  $this->successResponse();
    }

    // 客户端获取用户自己的订单
    function getMyOrder(){
        $user = Auth::user();
        $userId = $user->id;
        $orders = Order::with("photos")->get();
        $data = [];
        foreach ($orders as $order){
            $isOwn = false;
            $order->total = 0;
            foreach ($order->photos as $photo){
                if($photo->user_id == $userId){
                    $isOwn = true;
                    break;
                }
            }
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
                array_push($data, $order);
            }
        }
        return $this->successResponse($data);
    }

    // 客户端创建订单
    function createOrder(Request $req){
        $data = $req->only("full_name","phone_number","shipping_address","card_number","name_on_card","exp_date","cvv","photo_id_list");

        $val = Validator::make($data,[
            "full_name"=>"required",
            "phone_number"=>"required",
            "shipping_address"=>"required",
            "card_number"=>"required",
            "name_on_card"=>"required",
            "exp_date"=>"required",
            "cvv"=>"required",
            "photo_id_list"=>"required|array"
        ]);

        if($val->fails()){
            return response()->json(["msg"=>"data cannot be processed"],422);
        }

        foreach ($req->photo_id_list as $id){
            $photo = Photo::find($id);
            if(!$photo || $photo->status !=='cart') return response()->json(["msg"=>"not found"],404);
        }


        $order = Order::create([
            "full_name"=>$req->full_name,
            "phone_number"=>$req->phone_number,
            "shipping_address"=>$req->shipping_address,
            "card_number"=>$req->card_number,
            "name_on_card"=>$req->name_on_card,
            "exp_date"=>$req->exp_date,
            "cvv"=>$req->cvv,
            "total"=>count($req->photo_id_list),
            "order_placed"=>date("Y-m-d h:m"),
            "status"=>"Valid",
        ]);

        foreach ($req->photo_id_list as $id){
            $photo = Photo::find($id);
            $photo->order()->associate($order);
            $photo->update(["status"=>"order"]);
        }
       if($order){
           $res = Order::with("photos")->get();
           $data = [];
           foreach ($res as $item){
               if($item->id == $order->id){
                   foreach ($item->photos as $photo){
                       $size =  Size::find($photo->size_id);
                       $photo->size = $size->size;
                       $photo->print_price = $size->price;

                       if($photo->frame_size){
                           $frame = Frame::find($photo->frame_id);
                           $photo->frame_name = $frame->name;
                           $photo->frame_price = $frame->price;
                       }else{
                           $photo->frame_name = "no frame";
                           $photo->frame_price = 0;
                       }
                   }

                    array_push($data,$item);
               }
           }
           unset($item);
           return response()->json(["msg"=>"success","data"=>$data]);
       }
    }

    // 客户端取消
    function cancelOrder($id){
        $order = Order::find($id);
        $photos = Photo::where("order_id",$id)->get();
        if(!$order || !$photos) return $this->notFoundResponse();
        $photos->delete();
        $order->delete();
        return $this->successResponse();
    }

}
