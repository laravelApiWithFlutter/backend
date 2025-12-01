<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserProfile;

class Companie extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'cfe_number'];

    public function user()
    {
        return $this->belongsTo(UserProfile::class,'user_id');
    }
}
