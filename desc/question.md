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

