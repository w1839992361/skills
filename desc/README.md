# TestProject

## 一. Laravel目录结构组成

```
其中 app目录是程序的核心代码,包括控制器、模型、服务提供者等;

config 目录是包括应用程序的配置文件,例如 数据库、缓存、邮件等;

database 目录包括数据相关的文件,例如迁移文件、种子文件等;

public 目录包括公共文件,例如前端资源、入口文件等;

routes 目录包括应用程序的路由文件(这里面写api的地址);

resources 目录包括应用程序的资源文件，例如视图、语言包、前端资源等;

storage 目录包括应用程序的存储文件，例如日志、缓存、会话等;

tests 目录包括应用程序的测试文件;

vendor 目录包括 Composer 依赖的第三方库。
```

## 二.启动

首先打开集成工具 XAMPP,把apache和mysql功能全部打开;

然后找到XAMPP的目录把我们的laravel放到里面的htdocs文件夹下,然后打开127.0.0.1就可以到laravel了点击如果可以看到laravel的页面就说明启动成功了

<img title="" src="./img/Snipaste_2023-05-24_20-43-55.png" alt="" data-align="inline">

## 三.项目配置

第一步 先在.env文件里面把数据库配置好

数据库名字 和 账号密码 写自己的

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=root
```

第二步 可以看到项目完成的api地址是没有/public那一层的,这样的话我们就需要把laravel的入口文件index.php从public目录里面提取出来,直接放在最外面,切记要把index.php里面的引入路径地址更换一下(需要去掉一层)

然后配置基本完成可以进行下一步了

## 接口

### Auth

#### a.Login

1.先在控制台输入  

php artisan make:controller Auth创建控制器,然后可以发现在 app/Http/Controllers/  目录下多了一个AuthController.php

 先在database/migrations/...sizes....php
 写上我们需要的字段
 ![image](./img/Snipaste_2023-05-28_10-50-34.png)
 php artisan migrate:refresh  进行回滚所有数据库迁移

 再去database/Seeders/DatabaseSeeder.php(用于给数据库添加数据的)
 在里面run方法里面写入

```php
/*
 这里面的Admin就是模型

 Hash 使用的是这个-->  use Illuminate\Support\Facades\Hash;
*/
    Admin::create([
           "email"=>"admin@eaphoto.com",
            "full_name"=>"admin",
            "password"=>Hash::make("admin"),
            "create_time"=>date("Y-m-d h:m") // 这一步其实应该不重要因为已经有$table->timestamps();
        ]);
```

最后执行 php artisan db:seed  进行生成定义的数据

2.配置一下auth.php (在config目录下)

```php
/*
下面可以看到有一个 guards
 guards --> 定义了认证守卫，可以配置多个守卫，每个守卫可以使用不同的用户提供者和会话存储驱动。
① 先把web的provider改为admins
② 在api里面加入  
"hash" => false,   
"input_key"=>"token",  
"storage_key"=>"token"
*/
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'admins',
            'hash' => false,
            "input_key"=>"token",
            "storage_key"=>"token"
        ],
    ],


/*
再往下面走 可以看到有一个 providers
providers -->定义了用户提供者，可以配置多个用户提供者，每个用户提供者可以使用不同的 
Eloquent 模型或数据库表。
在里面加入一个admins
*/
 'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

/*

上述代码的目的是将 Laravel 应用程序中的用户认证系统改为使用 admins 提供的用户模型进行
认证，以实现更高的灵活性和安全性。

具体来说，代码中做了以下配置：
将 web 守卫的用户提供者改为 admins，这样在使用 web 守卫时会使用 admins 提供的用户模
型进行认证。

在 api 守卫中增加了一些配置，包括关闭哈希加密、使用 token 作为输入和存储的键值、设置
用户提供者为 admins 等。这样在使用 api 守卫时，会使用 admins 提供的用户模型进行认证
，并且使用 token 进行身份验证。

在 providers 中增加了一个 admins 用户提供者，使用了 App\Models\Admin 模型进行认
证。这样在使用 admins 用户提供者进行认证时，会使用该模型进行查询和验证。
*/
```

3.打开AuthController.php 在里面写一个login方法

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // login  Request就是前端的请求体,里面有参数
    function login(Request $req){
        $data = $req->only("email","password");

        // 这里的required和email是 laravel自带的校验规则
        $val = Validator::make($data,[
            "email"=>"required|email",
            "password"=>"required"
        ]);

        // 如果有错误,就说明数据有错误
        if($val->fails()){
            return response()->json([
                "msg"=>"data cannot be processed"
            ],422);
        }

       /*
        这里利用laravel的守卫去校验传过来的账号密码
        如果通过校验就通过Auth::user() 取出来user的信息

        这里还有一种写法就是指定guards 不写的话默认就是web
        指定的写法: Auth::guard("web")->attempt($data)
        指定的写法: $user = Auth::guard("web")->user();

        写入token 然后进行save()
        在使用response()->json([数据],状态码默认200)返回前端想要的数据结果
        */
        if(Auth::attempt($data)){
            $user = Auth::user();
            $user->token = md5($user->email);
            $user->save();

            return response()->json([
                "msg"=>"success",
                "data"=>[
                    "id"=>$user->id,
                    "email"=>$user->email,
                    "full_name"=>$user->full_name,
                    "token"=>$user->token,
                    "created_at"=>$user->created_at,
//                    "create_time"=>$user->create_time
                ]
            ]);
        }

        return response()->json([
            "msg"=>"user credentials are invalid"
        ],401);
    }
}
```

4.然后在routes的api.php里面写一个接口

```php
/*
可以看到这个接口需要前缀/v1/admin  我们可以在用prefix("v1")给路由增加前缀
然后在v1这个group函数里面写的接口地址都会带上v1
*/
// 在这里面写接口 最终访问就需要走/v1了
Route::prefix("v1")->group(function (){

     // 在这里面写接口 最终访问就需要走/v1/admin了
    Route::prefix("admin")->group(function (){
        // post:就是调用接口的方式,[这里是controller里面的控制类,"这里是里面方法名"]
         Route::post('login',[\App\Http\Controllers\AuthController::class,"login"]);
    }
}

// 然后调用login 传入对应的参数就可以正常访问了
```

#### b.Logout

1. 打开AuthController.php写入logout方法
   
   ```php
    // logout
    function logout(){
      // 通过认证获取当前的用户
        $user = Auth::user();
        // 把token赋值为null
        $user->token = null;
        // 调用保存
        $user->save();
        return response()->json([
            "msg"=>"success"
        ]);
    }
   ```


2. 在routes的api.php里面写一个接口

```php
   /*
   auth:api 中间件是 Laravel Passport 所提供的中间件，用于保护 API 路由
   api是我们在auth.php 认证守卫(guards)里面写的
   */
   Route::post("logout",[\App\Http\Controllers\AuthController::class,"logout"])->middleware("auth:api");

  // ps: 还有一种写中间件的方法
  Route::middleware("auth:api")->group(function (){
  // 我们就可以把接口需要认证token的接口写在这 不需要token认证的的写在外面
  Route::post("logout",[\App\Http\Controllers\AuthController::class,"logout"]);
  })
```

![image](./img/Snipaste_2023-05-28_10-31-34.png)

3. 捕获401无权限
   
```php
   /*我们可以在app/Exceptions/Handler.php 里面统一捕获没有token的错误然后返回401
use Illuminate\Auth\AuthenticationException;

打开Handler.php 找到register函数
*/

// 方法1(捕捉单个错误):
        // 捕捉单个身份验证失败的情况
        $this->renderable(function (AuthenticationException $e){
                  return response()->json([
                      "msg"=>"unauthorized"
                  ],401);
        });

// 方法2(捕捉所有错误):
        // 捕捉所有的情况
        $this->renderable(function (\Exception $e){
          // 然后判断是不是身份失败的情况
            if($e instanceof AuthenticationException){
                return response()->json([
                      "msg"=>"unauthorized"
                  ],401);
            }
        });

```
### Size

#### a.Get All Sizes 接口
1. 首先创建 Size的controller类和模型
 php artisan make:controller SizeController
 php artisan make:model Size -m
 -m 的意思是默认创建数据库迁移文件


2. 去创建数据库
 先在database/migrations/...sizes....php
 写上我们需要的字段
 ![image](./img/Snipaste_2023-05-28_10-45-07.png)
 php artisan migrate:refresh  进行回滚所有数据库迁移

  再去database/Seeders/DatabaseSeeder.php(用于给数据库添加数据的)
 在里面run方法里面写入几个size数据

```php
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
```

最后执行 php artisan db:seed  进行生成定义的数据

ps: 关于金额用的都是integer,然后存的时候把价格*100,取的时候在/100 这样不容易出问题

3. 去写getAllSize方法

```php
   function getAllSize(){
    // 获取所有 Size 模型实例
    $size = Size::all();
    // 遍历所有 Size 实例，将价格除以 100，以便前端展示时更清晰
    foreach ($size as $item){
        $item->price = $item->price/100;
    }
    // 取消对 $item 的引用，避免后续操作影响数据
    unset($item);
    // 返回 JSON 格式的响应，包括成功信息和所有 Size 实例
    return response()->json([
        "msg"=>"success",
        "data"=> $size
    ]);
   }
```

4. 路由api
   
```php
   // /api/v1/admin/size
   Route::get("size",[\App\Http\Controllers\SizeController::class,"getAllSize"]);

```
#### b.Update Size 接口

1. 在sizeController里面写入 updateSize方法

```php
    // $id 是路由传过来的参数
    function updateSize(Request $req,$id){
    // 查找指定 ID 的 Size 实例
    $size = Size::find($id);
    // 如果未找到该实例，返回 404 错误响应
    if (!$size) return response()->json(["msg"=>"not found"],404);
    // 如果价格小于 0，返回 422 错误响应
    if($req->get("price") < 0) return response()->json([],422);
    // 更新 Size 实例的价格
    $size->update(["price"=>$req->get("price")*100]);
    // 返回 JSON 格式的响应，包括成功信息和更新后的 Size 实例
    return response()->json([
        "msg"=>"success",
        "data"=>$size
    ]);
   }
```

2. 路由api
   
```php

// patch方法  /{size_id}:就是要传给函数的id   
// /api/v1/admin/size/XX
Route::patch("size/{size_id}",[\App\Http\Controllers\SizeController::class,"updateSize"]);

```
### Frame

#### a.Get All Frames 

1. init
   php artisan make:controller  FrameController
   php artisan make:model Frame -m

2. 数据库
   <img title="" src="./img/Snipaste_2023-05-28_11-24-11.png" alt="image" width="651">

```php
      Schema::create('frames', function (Blueprint $table) {
            $table->id();
            $table->string("url");
            $table->integer("price");
            $table->string("name");
//            $table->foreignIdFor(\App\Models\Size::class)->constrained()->cascadeOnDelete(); // 并定义了级联删除操作。当 sizes 表中的一条记录被删除时，关联的 frames 表中的所有记录也会被自动删除，从而保证数据的完整性和一致性。
            $table->foreignIdFor(\App\Models\Size::class)->constrained();
            $table->timestamps();
        });

        /*
        创建了一个指向 sizes 表的外键字段，用于关联 frames 表和 sizes 表中的记录。这样做的好处是可以确保 frames 表中的每条记录都关联到了 sizes 表中的一条记录，从而保证数据的完整性和一致性。
        */
```

3. 获取方法
   在FrameController.php写入getAllFrame方法

```php
   // 方法1 ->
    function getAllFrame(){
      // 获取所有相框
        $frames = Frame::all();
        // 遍历相框，查询对应的尺寸和价格，并更新size和price中
        foreach ($frames as $item){
            $item->size = Size::find($item->size_id)->size;
            $item->price = $item->price/100;
        }
        // 解除 item 的引用 unset(item);
        unset($item);
      return  response()->json([
          "msg"=>"success",
          "data"=>$frames
      ]);
    }
// 方法2 ->
    function getAllFrame(){
      // 获取所有相框
        $frames = Frame::join("sizes","frames.size_id","=","sizes.id")->select("frames.id","frames.url","frames.price","frames.name","sizes.size")->get()->map(function ($item){
            $item->price /=100;
            return $item;
        });
      return  response()->json([
          "msg"=>"success",
          "data"=>$frames
      ]);
    }
```

4. 路由
   
   ```php
     Route::get("frame",[\App\Http\Controllers\FrameController::class,"getAllFrame"]);
   ```

#### b.Update Frame 

1. 更新方法
   在FrameController.php写入updateFrameById方法
   
   ```php
      function updateFrameById(Request $req,$id){
        // 根据id 查询相框
        $frame = Frame::find($id);
        // 如果相框不存在
        if(!$frame) return response()->json(["msg"=>"not found"],404);
        // 更新相框的价格和名称
        $frame->update(["price"=>$req->get("price")*100,"name"=>$req->get("frame_name")]);
        // 返回响应
        return response()->json(["msg"=>"success","data"=>$frame]);
      }
   ```

2. 路由
   
   ```php
   Route::patch("frame/{frame_id}",[\App\Http\Controllers\FrameController::class,"updateFrameById"]);
   ```


### Order

#### a. Get All Orders
1. init
php artisan make:controller OrderController
php artisan make:model Order -m

2. 数据库
  ![image](./img/Snipaste_2023-05-28_14-58-47.png)
  
  可以增加几个订单数据 这里关联到了photo所以需要把photo初始化也做出来
  php artisan make:controller PhotoController
  php artisan make:model Photo -m

  ```php
      Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string("edited_url")->nullable();
            $table->string("original_url")->nullable();
            $table->string("framed_url")->nullable();
            $table->foreignIdFor(\App\Models\Frame::class)->nullable()->constrained();
            $table->foreignIdFor(\App\Models\Size::class)->constrained();
            $table->integer("user_id");
            $table->foreignIdFor(\App\Models\Order::class)->nullable()->constrained();
            $table->string("status");
            $table->timestamps();
        });

   /*
    $table->foreignIdFor(\App\Models\Frame::class)->nullable()->constrained();

    foreignIdFor 方法用于在当前数据库创建一个外键,该方法的传参是一个模型类名,表示要关联的模型 
    例如: 
    $table->foreignIdFor(\App\Models\Frame::class);
    表示在当前数据表创建一个外键字段,用于关联Frame模型的主键字段, 也可以加第二参数用于指定名称 $table->foreignIdFor(\App\Models\Frame::class,"XXX");

    nullable 方法就是指定该字段可以为空

    constrained 方法用于将外键字段与关联模型的主键,确保外键字段的值必须存在于关联模型的主键字段中 
    可以传入两个参数 分别是关联模型的表名和主键字段
    例如:
    $table->foreignIdFor(\App\Models\User::class)->constrained('users', 'user_id')表示将当前数据表中的外键字段与users表的user_id字段进行约束。

    不传参数的话就会将外键字段与关联模型的主键字段进行约束  
   */
  ```
然后 php artisan migrate:refresh 

可以写一些数据了
DatabaseSeeder.php
```php
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
```

生成数据 php artisan db:seed


3. 获取订单方法 

在OrderController.php里面写入 getAllOrders方法
```php
/*
写之前需要在Order模型里面做以下操作

这里用到了一个 一对多的知识点
订单就好比是一个博客的文章,然后图片就好比是文章的评论
一个文章有好多个评论
1对多
1就是订单
多就是图片
*/

// 要在Order的模型里面写入一个方法 表示该模型拥有多个photo模型对象,即一对多

public function photos(){
  return $this->hasmany(Photo::class);
} 

```

``` php

// 这个方法会导致n+1问题,影响性能
    function  getAllOrders(){
      // 使用with方法 预加载订单里面所有照片的信息
        $orders = Order::with("photos")->get();
        // 遍历每个订单里面的所有照片,并查询相关的相框和尺寸
        foreach ($orders as $item){
            // 定义一个总数
            $total = 0;
            foreach ($item->photos as $child){
              // 如果有相框 则查询相框信息，并更新到照片对象中
                if($child->frame_id){
                    $frame = Frame::find($child->frame_id);
                    $child->frame_price = $frame->price/100;
                    $child->frame_name = $frame->name;
                    
                }else{
                  // 如果照片没有关联相框，则设置相框价格为 0，相框名称为 "no frame"
                    $child->frame_price = 0;
                    $child->frame_name = "no frame";
                }
                // 查询照片关联的尺寸信息，并更新到照片对象中
                $size = Size::find($child->size_id);
                $child->size = $size->size;
                $child->print_price = $size->price/100;
                total += $child->print_price + $child->frame_price;
            }
        }
        unset($item);
        return  response()->json([
            "msg"=>"success",
            "data"=>$orders
        ]);
    }

```

4. 路由
```php
Route::get("order",[\App\Http\Controllers\OrderController::class,"getAllOrders"]);
```


#### b. Cancel Order

1. 取消订单方法
OrderController.php
``` php
    function cancelOrderById($id){
        // 查询状态为"Valid"且ID为指定值的订单
        $order = Order::where("status","Valid")->where("id",$id)->get();
        // 如果没有找到对应的订单，则返回404状态码和错误信息
        if(!count($order)) return response()->json(["msg"=>"not found"],404);
        // 将订单状态更新为"Cancel"
        $order[0]->update(["status"=>"Cancel"]);
        return  response()->json(["msg"=>"success"]);
    }

```

2. 路由
```php
  Route::post("order/cancel/{order_id}",[\App\Http\Controllers\OrderController::class,"cancelOrderById"]);

```


#### c. Complete Order

1. 完成订单方法
OrderController.php
``` php
    function completeOrderById($id){
      // 查询状态为"Valid"且ID为指定值的订单
        $order = Order::where("status","Valid")->where("id",$id)->get();
         // 如果没有找到对应的订单，则返回404状态码和错误信息
        if(!count($order)) return response()->json(["msg"=>"not found"],404);
        // 将订单状态更新为"Complete"
        $order[0]->update(["status"=>"Complete"]);
        return  response()->json(["msg"=>"success"]);
    }
```

2. 路由

```php
  Route::post("order/complete/{order_id}",[\App\Http\Controllers\OrderController::class,"completeOrderById"]);

```


### User

#### a. Get All Users

1. init
php artisan make:controller UserController
php artisan make:model User -m  (这里可以不执行,因为laravel自带了一个User模型)

2. 数据库

![image](./img/Snipaste_2023-05-28_16-09-09.png)

然后 php artisan migrate:refresh 

可以写一些数据了
DatabaseSeeder.php
```php
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
```

php artisan db:seed

3. 获取方法
UserController.php
``` php

   function getAllUsers(){
        $users = User::all(); //获取所有用户
        return response()->json([
            "msg"=>"success",
            "data"=> $users
        ]);
    }

```

4. 路由
``` php
Route::get("user",[\App\Http\Controllers\UserController::class,"getAllUsers"]);
```

#### b. Reset User Password

1. 重置方法
```php



```

