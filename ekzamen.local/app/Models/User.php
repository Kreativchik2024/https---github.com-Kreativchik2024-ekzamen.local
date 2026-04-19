<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Роли
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Связи
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    // Оценки, которые пользователь поставил другим
    public function ratingsGiven()
    {
        return $this->hasMany(UserRating::class, 'user_id');
    }

    // Оценки, которые пользователь получил как автор
    public function ratingsReceived()
    {
        return $this->hasMany(UserRating::class, 'author_id');
    }

    // Аксессоры для количества полученных лайков/дизлайков
    public function getLikesCountAttribute()
    {
        return $this->ratingsReceived()->where('type', 'like')->count();
    }

    public function getDislikesCountAttribute()
    {
        return $this->ratingsReceived()->where('type', 'dislike')->count();
    }

    // Проверки для текущего пользователя
    public function likedByCurrentUser()
    {
        if (!auth()->check()) return false;
        return $this->ratingsReceived()
            ->where('user_id', auth()->id())
            ->where('type', 'like')
            ->exists();
    }

    public function dislikedByCurrentUser()
    {
        if (!auth()->check()) return false;
        return $this->ratingsReceived()
            ->where('user_id', auth()->id())
            ->where('type', 'dislike')
            ->exists();
    }
}