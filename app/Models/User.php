<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as authUser;
use Illuminate\Notifications\Notifiable;

class User extends authUser
{
    use HasFactory,Notifiable;

    protected $fillable = [
        "password","email","create_time","username"
    ];

    protected $hidden = [
        "created_at","updated_at","password","token"
    ];

    public function photos(){
        return $this->hasMany(Photo::class);
    }
}
