<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'poste_id',
        'user_id',
    ];

    /**
     * Un commentaire appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(UserProfile::class);
    }

    /**
     * Un commentaire appartient à un poste.
     */
    public function poste()
    {
        return $this->belongsTo(Poste::class);
    }
}

