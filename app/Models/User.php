<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

     // Un utilisateur peut avoir plusieurs publications
     public function posts()
     {
         return $this->hasMany(Post::class);
     }
 
     // Un utilisateur peut avoir plusieurs commentaires
     public function comments()
     {
         return $this->hasMany(Comment::class);
     }
 
     // Un utilisateur peut aimer plusieurs posts
     public function likes()
     {
         return $this->hasMany(Like::class);
     }
 
     // Relation pour les demandes d'amis envoyées
     public function sentFriendRequests()
     {
         return $this->hasMany(Friendship::class, 'user_id');
     }
 
     // Relation pour les demandes d'amis reçues
     public function receivedFriendRequests()
     {
         return $this->hasMany(Friendship::class, 'friend_id');
     }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
