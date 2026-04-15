<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Указываем, какие поля можно массово заполнять
    protected $fillable = ['post_id', 'user_id', 'parent_id', 'body'];

    /**
     * Связь "принадлежит" с моделью Post.
     */
    public function post()
    {
        // Указываем, что внешний ключ — 'post_id', а локальный ключ — 'post_id' в таблице posts
    return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    /**
     * Связь "принадлежит" с моделью User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь "имеет много" с дочерними комментариями (ответами).
     * Здесь $this->id совпадает с parent_id в дочерних комментариях.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id', 'id');
    }

    /**
     * Связь "принадлежит" с родительским комментарием.
     * Здесь $this->parent_id совпадает с id в родительском комментарии.
     */
 
    }
