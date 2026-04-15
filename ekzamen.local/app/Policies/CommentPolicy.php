<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;

class CommentPolicy
{
    // Создание комментария (авторизованные пользователи)
    public function create(User $user): bool
    {
        return true;
    }

    // Редактирование комментария
    public function update(User $user, Comment $comment): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }
        return $user->id === $comment->user_id;
    }

    // Удаление комментария
    public function delete(User $user, Comment $comment): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }
        return $user->id === $comment->user_id;
    }
}