### 1. belongsTo 属于的问题

1.在相框的模型 Frame.php
``` php

  function size(){
      return $this->belongsTo(Size::class)->select('size');
  }

```

2.方法

然后在查询相框的时候 FrameController.php
``` php

$frames = Frame::all()->map(function ($item){
    // 尝试直接给size 赋值 size但是没有起作用
    $item->size = $item->size->size;
    return $item;
});

```

返回的数据当中 size是个对象
```json
[
    {
        "id": 1,
        "url": "http://127.0.0.1/laravel/public/storage/mockup_1.jpg",
        "price": 550,
        "name": "black",
        "size": {
            "size": "1 Inch"
        }
        // "size":"1 Inch" size如何变成这样
    }
]
```

### 1. 路由可选参数问题
frame_id是可选参数
```php

Route::post("photo/frame/{photo_id}/{frame_id?}",[\App\Http\Controllers\PhotoController::class,"setFrame"]);
```


```php
// 相框id非必传
function setFrame($photo_id,$frame_id = null,Request $req){
  //...
}
```
但是我路由上真的不传相框id的时候
```
photo/frame/1
```
就会报缺少参数错误
```
ArgumentCountError: Too few arguments to function App\Http\Controllers\PhotoController::setFrame(), 2 passed in D:\workspace\XMAPP\htdocs\laravel\vendor\laravel\framework\src\Illuminate\Routing\Controller.php on line 54 and exactly 3 expected in file D:\workspace\XMAPP\htdocs\laravel\app\Http\Controllers\PhotoController.php on line 0
```