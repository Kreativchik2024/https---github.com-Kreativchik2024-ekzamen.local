<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Post;

class PostPolicy
{
    // Просмотр списка постов (для гостей и всех пользователей)
    public function viewAny(?User $user): bool
    {
        return true;
    }

    // Просмотр конкретного поста (доступен всем, но неодобренные видят только админы)
    public function view(?User $user, Post $post): bool
    {
        if ($post->is_approved) {
            return true;
        }
        return $user && ($user->isSuperAdmin() || $user->isAdmin());
    }

    // Создание поста (только авторизованные пользователи)
    public function create(User $user): bool
    {
        return true; // все авторизованные могут создавать
    }

    // Редактирование поста
    public function update(User $user, Post $post): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }
        return $user->id === $post->user_id; // только свой пост
    }

    // Удаление поста
    public function delete(User $user, Post $post): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin()) {
            return true;
        }
        return $user->id === $post->user_id;
    }

    // Одобрение поста (только суперадмин и админ)
    public function approve(User $user, Post $post): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin();
    }
}