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
    //
    function  getAllOrders(){
        $orders = Order::with("photos")->get();
        foreach ($orders as $item){
            $total = 0;
            foreach ($item->photos as $child){
                if($child->frame_id){
                    $frame = Frame::find($child->frame_id);
                    $child->frame_price = $frame->price/100;
                    $child->frame_name = $frame->name;
                }else{
                    $child->frame_price = 0;
                    $child->frame_name = "no frame";
                }
                $size = Size::find($child->size_id);
                $child->size = $size->size;
                $child->print_price = $size->price/100;
                $total += $child->print_price + $child->frame_price;
            }
            $item->total = $total;
        }
        unset($item);
        return  response()->json([
            "msg"=>"success",
            "data"=>$orders
        ]);
    }

    function cancelOrderById($id){
        $order = Order::where("status","Valid")->where("id",$id)->get();
        if(!count($order)) return response()->json(["msg"=>"not found"],404);
        $order[0]->update(["status"=>"Cancel"]);
        return  response()->json(["msg"=>"success"]);
    }

    function completeOrderById($id){
        $order = Order::where("status","Valid")->where("id",$id)->get();
        if(!count($order)) return response()->json(["msg"=>"not found"],404);
        $order[0]->update(["status"=>"Complete"]);
        return  response()->json(["msg"=>"success"]);
    }


    function getMyOrder(){
        $user = Auth::user();
        $userId = $user->id;
        $order = Order::with("photos")->get();
        foreach ($order as $item) {
            foreach ($item->photos as $child) {
                $size = Size::find($child->size_id);
                $child->size = $size->size;
                $child->print_price = $size->price / 100;

                if ($child->frame_id) {
                    $frame = Frame::find($child->frame_id);
                    $child->frame_price = $frame->price / 100;
                    $child->frame_name = $frame->name;
                } else {
                    $child->frame_price = 0;
                    $child->frame_name = "no frame";
                }
                $total = $child->print_price + $child->frame_price;
                $item->total = $total;
            }
        }
//        $data = [];
////            foreach ($item->photos as $child){
////                if($child->user_id == $userId){
////                    array_push($data,$item);
////                    break;
////                }
////            }
////            foreach ($item->photos as $key=>$child2){
////                $size = Size::find($child2->size_id);
////                $child2->size = $size->size;
////                $child2->print_price = $size->price/100;
////                if($child2->frame_id){
////                    $frame = Frame::find($child2->frame_id);
////                    $child2->frame_price = $frame->price/100;
////                    $child2->frame_name = $frame->name;
////
////                }else{
////                    $child2->frame_price = 0;
////                    $child2->frame_name = "no frame";
////                }
////                $total = $child2->print_price + $child2->frame_price;
////                $item->total = $total;
////                if($child2->status !='order'){
////                    unset($item->photos[$key]);
////                }
////
////            }
//        }
        unset($item);
        return response()->json(["msg"=>"success","data"=>$order]);
    }

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


    function cancelOrder($id){
        $order = Order::find($id);
        $photos = Photo::where("order_id",$id)->get();
        if(!$order || !$photos) return response()->json(["msg"=>"not found"],404);
        foreach ($photos as $photo){
            $photo->delete();
        }
        $order->delete();
        return response()->json(['msg'=>"success"]);
    }

}
