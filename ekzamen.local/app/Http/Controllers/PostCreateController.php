<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostCreateController extends Controller
{
    public function create()
    {
        $this->authorize('create', Post::class);
        return view('posts.create');
    }

    public function store(Request $request)
    {
        // $this->authorize('create', Post::class);
        

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|max:255',
            'content' => 'required',
        ]);

        $post = auth()->user()->posts()->create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'] ?? '',
            'content' => $validated['content'],
            'is_approved' => false,
            'views' => 0,
            'is_published' => false,
            'published_at' => null,
            'status' => Post::STATUS_PENDING,
        ]);

        return redirect()->route('posts.show', $post->post_id)
            ->with('success', 'Пост создан и отправлен на модерацию.');
    }
    
}
