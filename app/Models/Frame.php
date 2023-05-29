<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    use HasFactory;

    protected $fillable = [
        "price","name"
    ];

    protected $hidden = [
        'created_at', 'updated_at',"size_id"
    ];

    function size(){
        return $this->belongsTo(Size::class)->select('size');
    }

}
