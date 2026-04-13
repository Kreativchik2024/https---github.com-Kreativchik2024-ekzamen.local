<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Сохраняет новый комментарий или ответ в базе данных.
     */
    public function store(Request $request, Post $post)
    {
        // Валидация данных из формы
        $request->validate([
            'body' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id', // Проверяем, что parent_id, если передан, существует
        ]);

        // Создаём комментарий через связь с постом
        $post->comments()->create([
            'user_id' => auth()->id(), // ID текущего авторизованного пользователя
            'body' => $request->body,
            'parent_id' => $request->parent_id,
        ]);

        // Возвращаем пользователя обратно на страницу поста с сообщением об успехе
        return back()->with('success', 'Комментарий добавлен!');
    }
}