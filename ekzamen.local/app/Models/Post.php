<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Post extends Model
{
    use HasFactory;

    protected $primaryKey = 'post_id'; // Указываем кастомный первичный ключ
    protected $table = 'posts';

    protected $fillable = [
        'title',
        "description",
        'content',
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
