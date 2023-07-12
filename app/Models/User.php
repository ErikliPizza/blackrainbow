<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Creativeorange\Gravatar\Facades\Gravatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;

    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'instagram',
        'twitter',
        'nso'
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
        'password' => 'hashed',
    ];

    public function arts()
    {
        return $this->hasMany(Art::class);
    }

    public function likedArts()
    {
        return $this->belongsToMany(Art::class, 'likes', 'user_id', 'art_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function getGravatarAttribute()
    {
        return Gravatar::get($this->attributes['email'], ['size'=>200]);
    }
    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'followed_user_id', 'user_id')
            ->withTimestamps();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'followed_user_id')
            ->withTimestamps();
    }

}
