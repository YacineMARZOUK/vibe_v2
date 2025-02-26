<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'friend_id', 'status'];

    // L'utilisateur qui envoie la demande
    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // L'utilisateur qui reçoit la demande
    public function receiver()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
