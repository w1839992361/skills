<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;

class Admin extends User
{
    use HasFactory,Notifiable;

    protected $fillable = [
        "email","full_name","password","create_time"
    ];

    function getCreatedAtAttribute($t){
        return $t;
    }

}
