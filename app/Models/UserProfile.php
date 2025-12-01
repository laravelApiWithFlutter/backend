<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Companie;
use App\Models\Post;

class UserProfile extends Authenticatable
{
    use HasApiTokens,HasFactory;

    protected $table='user_profiles';

    protected $fillable =['name','email','address','password','type'];


    protected $hidden = [
        'password',
    ];

     public function companies()
    {
        return $this->hasMany(Companie::class,'user_id');
    }

    public function posts()
    {
        return $this->hasMany(Poste::class,'user_id');
    }

     public function likes()
    {
        return $this->hasMany(Like::class,'user_id');
    }
}
