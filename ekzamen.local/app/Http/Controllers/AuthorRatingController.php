<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserRating;
use Illuminate\Http\Request;

class AuthorRatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function like(User $author)
    {
        if ($author->id === auth()->id()) {
            return back()->with('error', 'Нельзя оценивать самого себя.');
        }

        $existing = UserRating::where('user_id', auth()->id())
            ->where('author_id', $author->id)
            ->first();

        if ($existing) {
            if ($existing->type === 'like') {
                $existing->delete();
            } else {
                $existing->update(['type' => 'like']);
            }
        } else {
            UserRating::create([
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

        $existing = UserRating::where('user_id', auth()->id())
            ->where('author_id', $author->id)
            ->first();

        if ($existing) {
            if ($existing->type === 'dislike') {
                $existing->delete();
            } else {
                $existing->update(['type' => 'dislike']);
            }
        } else {
            UserRating::create([
                'user_id' => auth()->id(),
                'author_id' => $author->id,
                'type' => 'dislike'
            ]);
        }

        return back();
    }
}