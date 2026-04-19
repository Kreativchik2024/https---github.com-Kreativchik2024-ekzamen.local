<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(Request $request, Post $post)
    {
        $user = auth()->user();
        $existing = Like::where('user_id', $user->id)
                        ->where('post_id', $post->post_id)
                        ->first();

        $type = $request->input('type'); // 'like' или 'dislike'

        if ($existing) {
            // Если уже поставил такой же тип — удаляем (отмена)
            if ($existing->type === $type) {
                $existing->delete();
                $this->updatePostCounts($post);
                return back()->with('success', ucfirst($type) . ' убран');
            } else {
                // Меняем тип
                $existing->update(['type' => $type]);
                $this->updatePostCounts($post);
                return back()->with('success', 'Оценка изменена');
            }
        } else {
            // Создаём новый
            Like::create([
                'user_id' => $user->id,
                'post_id' => $post->post_id,
                'type' => $type,
            ]);
            $this->updatePostCounts($post);
            return back()->with('success', 'Спасибо за оценку');
        }
    }

    private function updatePostCounts(Post $post)
    {
        $post->likes_count = Like::where('post_id', $post->post_id)->where('type', 'like')->count();
        $post->dislikes_count = Like::where('post_id', $post->post_id)->where('type', 'dislike')->count();
        $post->save();
    }
}