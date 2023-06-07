<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!

*/


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix("v1")->group(function (){

   Route::prefix("admin")->group(function (){
       Route::post('login',[\App\Http\Controllers\AuthController::class,"login"]);

       Route::middleware("auth:api")->group(function (){
           Route::post("logout",[\App\Http\Controllers\AuthController::class,"logout"]);

           Route::get("size",[\App\Http\Controllers\SizeController::class,"getAllSize"]);
           Route::patch("size/{size_id}",[\App\Http\Controllers\SizeController::class,"updateSize"]);

           Route::get("frame",[\App\Http\Controllers\FrameController::class,"getAllFrame"]);
           Route::patch("frame/{frame_id}",[\App\Http\Controllers\FrameController::class,"updateFrameById"]);

           Route::get("order",[\App\Http\Controllers\OrderController::class,"getAllOrders"]);
           Route::post("order/cancel/{order_id}",[\App\Http\Controllers\OrderController::class,"cancelOrderById"]);
           Route::post("order/complete/{order_id}",[\App\Http\Controllers\OrderController::class,"completeOrderById"]);


           Route::get("user",[\App\Http\Controllers\UserController::class,"getAllUsers"]);
           Route::post("user/reset/{user_id}",[\App\Http\Controllers\UserController::class,"resetUserById"]);
           Route::delete("user/{user_id}",[\App\Http\Controllers\UserController::class,"deleteUserById"]);

           Route::post("cart/reset/{user_id}",[\App\Http\Controllers\UserController::class,"resetUserCartById"]);

           Route::get("admin",[\App\Http\Controllers\AdminController::class,"getAllAdmins"]);
           Route::post("admin",[\App\Http\Controllers\AdminController::class,"createAdmin"]);
           Route::post("admin/reset/{admin_id}",[\App\Http\Controllers\AdminController::class,"resetAdminPasswordById"]);
           Route::delete("admin/{admin_id}",[\App\Http\Controllers\AdminController::class,"deleteAdminById"]);
       });

   });

   Route::prefix("client")->group(function (){
       Route::post('login',[\App\Http\Controllers\UserController::class,"login"]);

       Route::post('register',[\App\Http\Controllers\UserController::class,"register"]);

       Route::middleware("auth:user_api")->group(function (){
           Route::post('logout',[\App\Http\Controllers\UserController::class,"logout"]);

           Route::post('user/reset',[\App\Http\Controllers\UserController::class,"resetPassword"]);

           Route::get("size",[\App\Http\Controllers\SizeController::class,"getAllSize"]);

           Route::get("photo",[\App\Http\Controllers\PhotoController::class,"getMyPhotos"]);

           Route::post("editPhoto/{photo_id}",[\App\Http\Controllers\PhotoController::class,"editPhotoById"]);

           Route::post("photo",[\App\Http\Controllers\PhotoController::class,"uploadPhoto"]);
           Route::delete("photo/{photo_id}",[\App\Http\Controllers\PhotoController::class,"deletePhotoById"]);
           Route::get("photo/size/{size_id}",[\App\Http\Controllers\PhotoController::class,"getMyPhotoBySizeId"]);

           Route::post("photo/frame/{photo_id}/{frame_id?}",[\App\Http\Controllers\PhotoController::class,"setFrame"]);

           Route::get("frame",[\App\Http\Controllers\FrameController::class,"getAllFrame"]);

           Route::get("cart",[\App\Http\Controllers\PhotoController::class,"getCart"]);
           Route::post("cart",[\App\Http\Controllers\PhotoController::class,"appendToCart"]);


           Route::get("order",[\App\Http\Controllers\OrderController::class,"getMyOrder"]);
           Route::post("order",[\App\Http\Controllers\OrderController::class,"createOrder"]);

           Route::post("order/cancel/{order_id}",[\App\Http\Controllers\OrderController::class,"cancelOrder"]);
       });

   });

});
