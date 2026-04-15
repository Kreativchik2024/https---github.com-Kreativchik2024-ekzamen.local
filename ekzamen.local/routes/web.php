<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;


use App\Http\Controllers\PostController;
Route::get('/', [PostController::class, 'index'])->name('home');
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
