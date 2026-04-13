<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Comment; // если понадобится отдельно, но можно через отношение

class PostController extends Controller
{
   
public function index(Request $request)
{
    $posts = Post::orderBy('post_id', 'DESC')->paginate(3);
    $recentPosts = Post::latest()->take(5)->get();

    if ($request->ajax()) {
        return view('posts._post_list', compact('posts'))->render();
    }

    return view('posts.index', compact('posts', 'recentPosts'));
}

public function show(Post $post)
{
    // Загружаем комментарии с авторами и ответами, сортируем по дате (сначала новые)
    $post->load(['comments' => function ($query) {
        $query->with('user', 'replies.user')->latest();
    }]);

    return view('posts.show', compact('post'));
}
    }


