<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Аутентификация (стандартные маршруты Laravel)
Auth::routes(['verify' => false]);

// Главная страница
Route::get('/', [PostController::class, 'index'])->name('home');

// Просмотр поста (доступен всем)

// Группа для авторизованных пользователей
Route::middleware(['auth'])->group(function () {
    // Управление постами
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::patch('/posts/{post}/approve', [PostController::class, 'approve'])->name('posts.approve');
    Route::patch('/posts/{post}/reject', [PostController::class, 'reject'])->name('posts.reject');
    Route::patch('/posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');
    

    // Комментарии
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Дашборд (профиль пользователя)
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');


// Админ-панель (доступна только суперадминам и админам)
Route::middleware(['auth', 'role:super_admin,admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('admin.updateRole');
    
});