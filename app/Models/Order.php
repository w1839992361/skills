<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $hidden = [
        "created_at","updated_at","frame_id","size_id","order_id",
    ];

    protected $fillable = [
        "status",
        "full_name",
        "phone_number",
        "shipping_address",
        "card_number",
        "name_on_card",
        "exp_date",
        "cvv",
        "total",
        "order_placed",
        "order_id"
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class);
//        return $this->hasMany(Photo::class,"order_id","id");
    }

    function size(){
        return $this->belongsTo(Size::class);
    }

    function frame(){
        return $this->belongsTo(Frame::class);
    }
}
