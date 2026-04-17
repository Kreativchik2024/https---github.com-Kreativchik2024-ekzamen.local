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
    protected $primaryKey = 'post_id'; // Указываем кастомный первичный ключ
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
    // Связь "один ко многим" с комментариями

    public function user()
{
    return $this->belongsTo(User::class);
}
public function comments()
{
    // Указываем, что внешний ключ в таблице comments называется 'post_id'
    return $this->hasMany(Comment::class, 'post_id');
}







}
