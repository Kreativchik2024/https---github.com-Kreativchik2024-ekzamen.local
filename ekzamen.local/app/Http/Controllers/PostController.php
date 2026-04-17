<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::orderBy('post_id', 'DESC');
        
        if (!auth()->check() || !(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())) {
            $query->where('is_approved', true);
        }
        
        $posts = $query->paginate(3);
        $recentPosts = Post::latest()->take(5)->get();
        
        if ($request->ajax()) {
            return view('posts._post_list', compact('posts'))->render();
        }
        
        return view('posts.index', compact('posts', 'recentPosts'));
    }
    
    public function create()
    {
        $this->authorize('create', Post::class);
        return view('posts.create');
    }
    
    public function store(Request $request)
    {
        $this->authorize('create', Post::class);
        
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
            'status' => Post::STATUS_PENDING, // добавлено
        ]);
        
        return redirect()->route('posts.show', $post->post_id)
            ->with('success', 'Пост создан и отправлен на модерацию.');
    }
    
    public function show(Post $post)
    {
        $user = auth()->user();
        
        if (!$post->is_approved) {
            if (!$user || !($user->isSuperAdmin() || $user->isAdmin() || $user->id === $post->user_id)) {
                abort(404);
            }
        }

        $post->load(['comments' => function ($query) {
            $query->with('user', 'replies.user')->latest();
        }]);
        $recentPosts = Post::where('is_approved', true)->latest()->take(5)->get();

        return view('posts.show', compact('post', 'recentPosts'));
    }
    
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }
    
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);
        
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|max:255',
            'content' => 'required',
        ]);
        
        $post->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'description' => $validated['description'] ?? '',
            'content' => $validated['content'],
        ]);
        
        return redirect()->route('posts.show', $post->post_id)
            ->with('success', 'Пост обновлён.');
    }
    
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();
         return back()->with('success', 'Пост удалён.');
    }
    
    public function approve(Post $post)
    {
        $this->authorize('approve', $post);
        $post->update([
            'is_approved' => true,
            'is_published' => true,
            'published_at' => now(),
            'status' => Post::STATUS_APPROVED,
        ]);
        return back()->with('success', 'Пост одобрен и опубликован.');
    }
    
    public function restore($id)
    {
        $post = Post::withTrashed()->findOrFail($id);
        $this->authorize('delete', $post);
        $post->restore();
        // Восстанавливаем статус на "на модерации"
        $post->update([
            'is_approved' => false,
            'is_published' => false,
            'status' => Post::STATUS_PENDING,
        ]);
        return back()->with('success', 'Пост восстановлен.');
    }
    public function reject(Post $post)
{
    $this->authorize('approve', $post); // те же права, что на одобрение
    $post->update([
        'is_approved' => false,
        'is_published' => false,
        'status' => Post::STATUS_REJECTED,
    ]);
    return back()->with('success', 'Пост отклонён.');
}

}