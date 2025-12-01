<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserProfile;
use App\Models\Like;
use App\Models\Comment;

class Poste extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','content','image','video'];

    public function userprofile()
    {
        return $this->belongsTo(UserProfile::class,'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
