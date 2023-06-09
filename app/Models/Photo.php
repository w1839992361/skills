<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        "original_url","size_id","user_id","status"
    ];

    function size(){
        return $this->belongsTo(Size::class);
    }

    function order(){
        return $this->belongsTo(Photo::class);
    }

    function frame(){
        return $this->belongsTo(Frame::class);
    }

}
