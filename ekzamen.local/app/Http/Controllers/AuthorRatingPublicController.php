<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthorRatingPublicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // только зарегистрированные пользователи
    }

    public function index(Request $request)
    {
        $sortBy = $request->get('sort', 'likes'); // likes, posts, dislikes
        $period = $request->get('period', 'all'); // период для постов (опционально)

        $query = User::query();

        // Подсчёт постов (можно с фильтром по периоду, но для простоты оставим все)
        $query->withCount('posts');

        // Подсчёт полученных лайков и дизлайков
        $query->withCount(['ratingsReceived as likes_count' => function ($q) {
            $q->where('type', 'like');
        }]);
        $query->withCount(['ratingsReceived as dislikes_count' => function ($q) {
            $q->where('type', 'dislike');
        }]);

        // Сортировка
        if ($sortBy === 'likes') {
            $query->orderBy('likes_count', 'desc');
        } elseif ($sortBy === 'dislikes') {
            $query->orderBy('dislikes_count', 'desc');
        } else {
            $query->orderBy('posts_count', 'desc');
        }

        $authors = $query->paginate(20);

        return view('authors.rating', compact('authors', 'sortBy'));
    }

    public function like(User $author)
    {
        // нельзя голосовать за себя
        if ($author->id === auth()->id()) {
            return back()->with('error', 'Нельзя оценивать самого себя.');
        }

        $existing = \App\Models\UserRating::where('user_id', auth()->id())
            ->where('author_id', $author->id)
            ->first();

        if ($existing) {
            if ($existing->type === 'like') {
                $existing->delete();
            } else {
                $existing->update(['type' => 'like']);
            }
        } else {
            \App\Models\UserRating::create([
                'user_id' => auth()->id(),
                'author_id' => $author->id,
                'type' => 'like'
            ]);
        }

        return back();
    }

    public function dislike(User $author)
    {
        if ($author->id === auth()->id()) {
            return back()->with('error', 'Нельзя оценивать самого себя.');
        }

        $existing = \App\Models\UserRating::where('user_id', auth()->id())
            ->where('author_id', $author->id)
            ->first();

        if ($existing) {
            if ($existing->type === 'dislike') {
                $existing->delete();
            } else {
                $existing->update(['type' => 'dislike']);
            }
        } else {
            \App\Models\UserRating::create([
                'user_id' => auth()->id(),
                'author_id' => $author->id,
                'type' => 'dislike'
            ]);
        }

        return back();
    }
}