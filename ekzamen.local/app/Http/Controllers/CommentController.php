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
    $request->validate([
        'body' => 'required|string|max:2000',
        'parent_id' => 'nullable|exists:comments,id',
    ]);

    // Если это не ответ, проверяем, есть ли уже комментарий от этого пользователя
    if (!$request->parent_id) {
        $existing = Comment::where('post_id', $post->post_id)
            ->where('user_id', auth()->id())
            ->whereNull('parent_id')
            ->exists();
        if ($existing) {
            return back()->withErrors(['body' => 'Вы уже оставили комментарий к этому посту. Вы можете отвечать на комментарии других.']);
        }
    }

    $post->comments()->create([
        'user_id' => auth()->id(),
        'body' => $request->body,
        'parent_id' => $request->parent_id,
    ]);

    return back()->with('success', 'Комментарий добавлен');
}

    public function destroy(Comment $comment)
{
    $this->authorize('delete', $comment);
    $comment->delete();
    return back()->with('success', 'Комментарий удалён.');
}
}