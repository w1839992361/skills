<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Frame;
use App\Models\Order;
use App\Models\Photo;
use App\Models\Size;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//         User::factory(10)->create();
        Admin::create([
           "email"=>"admin@eaphoto.com",
            "full_name"=>"admin",
            "password"=>Hash::make("admin"),
            "create_time"=>date("Y-m-d h:m")
        ]);



        Size::create([
            "size"=>"1 Inch",
            "width"=>2.5,
            "height"=>3.6,
            "price"=>10
        ]);

        Size::create([
            "size"=>"2 Inch",
            "width"=>3.4,
            "height"=>5.2,
            "price"=>15
        ]);

        Size::create([
            "size"=>"3 Inch",
            "width"=>5.5,
            "height"=>8.4,
            "price"=>60
        ]);

        Size::create([
            "size"=>"5 Inch",
            "width"=>8.9,
            "height"=>12.7,
            "price"=>70
        ]);

        Size::create([
            "size"=>"6 Inch",
            "width"=>10.2,
            "height"=>15.2,
            "price"=>100
        ]);

        Size::create([
            "size"=>"7 Inch",
            "width"=>12.7,
            "height"=>17.8,
            "price"=>120
        ]);

        Size::create([
            "size"=>"8 Inch",
            "width"=>15.2,
            "height"=>20.3,
            "price"=>120
        ]);


        Frame::create([
            "url"=>"http://127.0.0.1/laravel/public/storage/frame_1.jpg",
            "price"=>550,
            "name"=>"black",
            "size_id"=>1,
        ]);

        Frame::create([
            "url"=>"http://127.0.0.1/laravel/public/storage/frame_1.jpg",
            "price"=>550,
            "name"=>"red",
            "size_id"=>1,
        ]);

        Frame::create([
            "url"=>"http://127.0.0.1/laravel/public/storage/frame_1.jpg",
            "price"=>980,
            "name"=>"black",
            "size_id"=>2,
        ]);

        Frame::create([
            "url"=>"http://127.0.0.1/laravel/public/storage/frame_1.jpg",
            "price"=>850,
            "name"=>"red",
            "size_id"=>2,
        ]);

        Frame::create([
            "url"=>"http://127.0.0.1/laravel/public/storage/frame_1.jpg",
            "price"=>520,
            "name"=>"black",
            "size_id"=>3,
        ]);

        Frame::create([
            "url"=>"http://127.0.0.1/laravel/public/storage/frame_1.jpg",
            "price"=>1150,
            "name"=>"red",
            "size_id"=>3,
        ]);

        Frame::create([
            "url"=>"http://127.0.0.1/laravel/public/storage/frame_1.jpg",
            "price"=>450,
            "name"=>"red",
            "size_id"=>4,
        ]);

        Frame::create([
            "url"=>"http://127.0.0.1/laravel/public/storage/frame_1.jpg",
            "price"=>5506,
            "name"=>"white",
            "size_id"=>4,
        ]);

        Frame::create([
            "url"=>"http://127.0.0.1/laravel/public/storage/frame_1.jpg",
            "price"=>2501,
            "name"=>"red",
            "size_id"=>5,
        ]);

        Frame::create([
            "url"=>"http://127.0.0.1/laravel/public/storage/frame_1.jpg",
            "price"=>5.5,
            "name"=>"green",
            "size_id"=>5,
        ]);

        Frame::create([
            "url"=>"http://127.0.0.1/laravel/public/storage/frame_1.jpg",
            "price"=>2220,
            "name"=>"blue",
            "size_id"=>6,
        ]);

        Frame::create([
            "url"=>"http://127.0.0.1/laravel/public/storage/frame_1.jpg",
            "price"=>1999,
            "name"=>"yellow",
            "size_id"=>6,
        ]);

        Frame::create([
            "url"=>"http://127.0.0.1/laravel/public/storage/frame_1.jpg",
            "price"=>771,
            "name"=>"red",
            "size_id"=>7,
        ]);
        Frame::create([
            "url"=>"http://127.0.0.1/laravel/public/storage/frame_1.jpg",
            "price"=>2888,
            "name"=>"pink",
            "size_id"=>7,
        ]);



        Order::create([
           "full_name"=>"Matthew",
           "phone_number"=>"10001000",
           "shipping_address"=>"Where",
           "card_number"=>"3223222222",
           "name_on_card"=>"Matthew XXX",
           "exp_date"=>'2023-05-16',
            "cvv"=>"246",
            "total"=>0,
            "order_placed"=>'2023-05-16 13:58',
            "status"=>"Valid"
        ]);

        Order::create([
            "full_name"=>"Matthew",
            "phone_number"=>"10001000",
            "shipping_address"=>"Where",
            "card_number"=>"3223222222",
            "name_on_card"=>"Matthew XXX",
            "exp_date"=>'2023-05-16',
            "cvv"=>"246",
            "total"=>0,
            "order_placed"=>'2023-05-16 13:58',
            "status"=>"Valid",
        ]);

        Photo::create([
            "edited_url"=>null,
            "original_url"=>"http://127.0.0.1/laravel/public/storage/VqSXFxX6svX0ftga8GXhp2Wj83ahAfCklNhhz8C5.jpg",
            "framed_url"=>"http://127.0.0.1/laravel/public/storage/q7DQnDLIlBIYMFhIIW6CJZObTxoAcdROii1sFCpz.png",
            "status"=>"uploaded",
            "frame_id"=>2,
            "size_id"=>2,
            "user_id"=>1
        ]);
        Photo::create([
            "edited_url"=>null,
            "original_url"=>"http://127.0.0.1/laravel/public/storage/VqSXFxX6svX0ftga8GXhp2Wj83ahAfCklNhhz8C5.jpg",
            "framed_url"=>"http://127.0.0.1/laravel/public/storage/q7DQnDLIlBIYMFhIIW6CJZObTxoAcdROii1sFCpz.png",
            "status"=>"uploaded",
            "frame_id"=>2,
            "size_id"=>2,
            "user_id"=>1
        ]);

        Photo::create([
            "edited_url"=>null,
            "original_url"=>"http://127.0.0.1/laravel/public/storage/VqSXFxX6svX0ftga8GXhp2Wj83ahAfCklNhhz8C5.jpg",
            "framed_url"=>null,
            "status"=>"uploaded",
            "frame_id"=>null,
            "size_id"=>1,
            "user_id"=>1
        ]);

        Photo::create([
            "edited_url"=>null,
            "original_url"=>"http://127.0.0.1/laravel/public/storage/VqSXFxX6svX0ftga8GXhp2Wj83ahAfCklNhhz8C5.jpg",
            "framed_url"=>"http://127.0.0.1/laravel/public/storage/q7DQnDLIlBIYMFhIIW6CJZObTxoAcdROii1sFCpz.png",
            "status"=>"order",
            "order_id"=>1,
            "frame_id"=>2,
            "size_id"=>2,
            "user_id"=>1
        ]);

        Photo::create([
            "edited_url"=>null,
            "original_url"=>"http://127.0.0.1/laravel/public/storage/VqSXFxX6svX0ftga8GXhp2Wj83ahAfCklNhhz8C5.jpg",
            "framed_url"=>"http://127.0.0.1/laravel/public/storage/q7DQnDLIlBIYMFhIIW6CJZObTxoAcdROii1sFCpz.png",
            "status"=>"order",
            "order_id"=>2,
            "frame_id"=>2,
            "size_id"=>2,
            "user_id"=>1
        ]);

        Photo::create([
            "edited_url"=>null,
            "original_url"=>"http://127.0.0.1/laravel/public/storage/VqSXFxX6svX0ftga8GXhp2Wj83ahAfCklNhhz8C5.jpg",
            "framed_url"=>"http://127.0.0.1/laravel/public/storage/q7DQnDLIlBIYMFhIIW6CJZObTxoAcdROii1sFCpz.png",
            "status"=>"uploaded",
            "frame_id"=>2,
            "size_id"=>2,
            "user_id"=>1
        ]);

        Photo::create([
            "edited_url"=>null,
            "original_url"=>"http://127.0.0.1/laravel/public/storage/VqSXFxX6svX0ftga8GXhp2Wj83ahAfCklNhhz8C5.jpg",
            "framed_url"=>"http://127.0.0.1/laravel/public/storage/q7DQnDLIlBIYMFhIIW6CJZObTxoAcdROii1sFCpz.png",
            "status"=>"order",
            "order_id"=>2,
            "frame_id"=>2,
            "size_id"=>2,
            "user_id"=>1
        ]);



        Photo::create([
            "edited_url"=>null,
            "original_url"=>"http://127.0.0.1/laravel/public/storage/VqSXFxX6svX0ftga8GXhp2Wj83ahAfCklNhhz8C5.jpg",
            "framed_url"=>"http://127.0.0.1/laravel/public/storage/q7DQnDLIlBIYMFhIIW6CJZObTxoAcdROii1sFCpz.png",
            "status"=>"order",
            "order_id"=>1,
            "frame_id"=>2,
            "size_id"=>2,
            "user_id"=>1,
        ]);




        Photo::create([
            "edited_url"=>null,
            "original_url"=>"http://127.0.0.1/laravel/public/storage/CI9EaS3fw9wma43xTtqPFnrvSskQhv6S9YNsxbpc.png",
            "framed_url"=>null,
            "status"=>"uploaded",
            "size_id"=>1,
            "frame_id"=>1,
            "order_id"=>1,
            "user_id"=>1
        ]);

        Photo::create([
            "edited_url"=>null,
            "original_url"=>"http://127.0.0.1/laravel/public/storage/CI9EaS3fw9wma43xTtqPFnrvSskQhv6S9YNsxbpc.png",
            "framed_url"=>null,
            "status"=>"cart",
            "size_id"=>1,
            "frame_id"=>1,
            "order_id"=>1,
            "user_id"=>1
        ]);



        User::create([
            "email"=>"user@eaphoto.com",
            "username"=>"sample_user",
            "password"=>Hash::make("user"),
            "cart_total"=>300,
            "create_time"=>"2023-05-16 11:12",
        ]);

        User::create([
            "email"=>"us3er@eaphoto.com",
            "username"=>"sample_user",
            "password"=>Hash::make("user"),
            "cart_total"=>300,
            "create_time"=>"2023-05-16 11:12",
        ]);

    }
}
