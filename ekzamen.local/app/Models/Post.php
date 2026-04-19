<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $primaryKey = 'post_id';
    protected $table = 'posts';

    protected $fillable = [
        'title', 'slug', 'description', 'content', 
        'views', 'is_published', 'is_approved', 'status', 'published_at', 
        'user_id', 'category_id'
    ];

    protected $casts = [
        'post_id' => 'integer'      
    ];

    protected $hidden = [
        'remember_token'
    ];

    // Добавляем виртуальные атрибуты для счётчиков
    protected $appends = ['likes_count', 'dislikes_count'];

    public function getLikesCountAttribute()
    {
        return $this->likes()->where('type', 'like')->count();
    }

    public function getDislikesCountAttribute()
    {
        return $this->likes()->where('type', 'dislike')->count();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id', 'post_id');
    }

    public function userLike()
    {
        return $this->hasOne(Like::class, 'post_id', 'post_id')->where('user_id', auth()->id());
    }
}