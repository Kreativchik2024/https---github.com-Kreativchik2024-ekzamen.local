<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

   public function index()
{
    $user = auth()->user();
    $users = collect();
    $publishedPosts = collect();
    $pendingPosts = collect();
    $trashedPosts = collect();
    $rejectedPosts = collect(); // добавить
    $myPosts = collect();

    if ($user->isSuperAdmin()) {
        $users = User::paginate(10);
        $publishedPosts = Post::where('is_approved', true)->latest()->paginate(10);
        $pendingPosts = Post::where('is_approved', false)->latest()->paginate(10);
        $rejectedPosts = Post::where('status', 'rejected')->latest()->paginate(10);
        $trashedPosts = Post::onlyTrashed()->latest()->paginate(10);
    } elseif ($user->isAdmin()) {
        $publishedPosts = Post::where('is_approved', true)->latest()->paginate(10);
        $pendingPosts = Post::where('is_approved', false)->latest()->paginate(10);
        $rejectedPosts = Post::where('status', 'rejected')->latest()->paginate(10);
        $trashedPosts = Post::onlyTrashed()->latest()->paginate(10);
    } else {
        $myPosts = $user->posts()->latest()->paginate(10);
    }

    return view('dashboard', compact('users', 'publishedPosts', 'pendingPosts', 'trashedPosts', 'rejectedPosts', 'myPosts'));
}
}