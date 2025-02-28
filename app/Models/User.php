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
    
    // Add this method to your User model
    public function friends()
    {
        // This assumes your Friendship model has a 'status' field 
        // with 'accepted' to indicate confirmed friendships
        return User::whereIn('id', function($query) {
            $query->select('friend_id')
                  ->from('friendships')
                  ->where('user_id', $this->id)
                  ->where('status', 'accepted');
        })->orWhereIn('id', function($query) {
            $query->select('user_id')
                  ->from('friendships')
                  ->where('friend_id', $this->id)
                  ->where('status', 'accepted');
        });
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
        'location',
        'profile_photo_path',
        'bio',
        'cover_photo_path'
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
    
    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return asset('storage/' . $this->profile_photo_path);
        }
        
        return "https://ui-avatars.com/api/?name=" . urlencode($this->name) . "&color=7F9CF5&background=EBF4FF";
    }
    
    /**
     * Get cover photo URL
     */
    public function getCoverPhotoUrlAttribute()
    {
        if ($this->cover_photo_path) {
            return asset('storage/' . $this->cover_photo_path);
        }
        
        return null; // Default cover photo color will be handled by CSS
    }
}