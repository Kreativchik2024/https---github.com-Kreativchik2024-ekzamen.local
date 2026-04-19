<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthorRatingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostCreateController;

// Аутентификация
Auth::routes(['verify' => false]);

// Главная
Route::get('/', [PostController::class, 'index'])->name('home');



// Группа для авторизованных
Route::middleware(['auth'])->group(function () {
    // Управление постами
  Route::get('/posts/create', [PostCreateController::class, 'create'])->name('posts.create');
   Route::post('/posts', [PostCreateController::class, 'store'])->name('posts.store'); 
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::patch('/posts/{post}/approve', [PostController::class, 'approve'])->name('posts.approve');
    Route::patch('/posts/{post}/reject', [PostController::class, 'reject'])->name('posts.reject');
    Route::patch('/posts/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');

    // Лайки/дизлайки для постов
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/dislike', [PostController::class, 'dislike'])->name('posts.dislike');

    // Лайки/дизлайки для авторов (используется в шаблоне поста)
    Route::post('/authors/{author}/like', [AuthorRatingController::class, 'like'])->name('authors.like');
    Route::post('/authors/{author}/dislike', [AuthorRatingController::class, 'dislike'])->name('authors.dislike');

    // Комментарии
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Дашборд
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
});
// Просмотр поста (доступен всем)
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Админ-панель
Route::middleware(['auth', 'role:super_admin,admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('admin.updateRole');
});

