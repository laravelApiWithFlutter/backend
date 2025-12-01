<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'poste_id',
        'user_id',
    ];

    public function post()
    {
        return $this->belongsTo(Poste::class);
    }

    public function user()
    {
        return $this->belongsTo(UserProfile::class, 'user_id');
    }
}