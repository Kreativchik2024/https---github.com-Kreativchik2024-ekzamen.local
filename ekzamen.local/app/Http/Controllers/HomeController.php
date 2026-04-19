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

    public function index(Request $request)
    {
        $user = auth()->user();
        $users = collect();
        $publishedPosts = collect();
        $pendingPosts = collect();
        $rejectedPosts = collect();
        $trashedPosts = collect();
        $myPosts = collect();
        $statsPosts = collect();
        $userStats = collect(); // статистика по пользователям
        $period = $request->get('period', 'all');
        $statType = $request->get('stat_type', 'views');
        $authorPeriod = $request->get('author_period', 'all'); // период для авторов (по постам)
        $authorSort = $request->get('author_sort', 'posts'); // сортировка авторов: posts, likes, dislikes

        if ($user->isSuperAdmin() || $user->isAdmin()) {
            // Посты для вкладок
            $publishedPosts = Post::where('status', Post::STATUS_APPROVED)->latest()->paginate(10);
            $pendingPosts = Post::where('status', Post::STATUS_PENDING)->latest()->paginate(10);
            $rejectedPosts = Post::where('status', Post::STATUS_REJECTED)->latest()->paginate(10);
            $trashedPosts = Post::onlyTrashed()->latest()->paginate(10);

            // Статистика по постам (просмотры, лайки, дизлайки)
            $statsQuery = Post::where('status', Post::STATUS_APPROVED)
                ->withCount(['likes as likes_count' => function ($q) {
                    $q->where('type', 'like');
                }])
                ->withCount(['likes as dislikes_count' => function ($q) {
                    $q->where('type', 'dislike');
                }]);

            // Фильтр по периоду для статистики постов
            switch ($period) {
                case 'day':   $statsQuery->whereDate('created_at', '>=', now()->subDay()); break;
                case 'week':  $statsQuery->whereDate('created_at', '>=', now()->subWeek()); break;
                case 'month': $statsQuery->whereDate('created_at', '>=', now()->subMonth()); break;
                case 'year':  $statsQuery->whereDate('created_at', '>=', now()->subYear()); break;
                default: break;
            }

            // Сортировка по выбранному типу
            if ($statType === 'views') {
                $statsQuery->orderBy('views', 'desc');
            } elseif ($statType === 'likes') {
                $statsQuery->orderBy('likes_count', 'desc');
            } elseif ($statType === 'dislikes') {
                $statsQuery->orderBy('dislikes_count', 'desc');
            }
            $statsPosts = $statsQuery->paginate(20);

            // Статистика по авторам (количество постов за период + полученные лайки/дизлайки за всё время)
            $userStatsQuery = User::query();

            // Подсчёт постов с учётом периода
            switch ($authorPeriod) {
                case 'day':
                    $userStatsQuery->withCount(['posts' => function ($q) {
                        $q->whereDate('created_at', '>=', now()->subDay());
                    }]);
                    break;
                case 'week':
                    $userStatsQuery->withCount(['posts' => function ($q) {
                        $q->whereDate('created_at', '>=', now()->subWeek());
                    }]);
                    break;
                case 'month':
                    $userStatsQuery->withCount(['posts' => function ($q) {
                        $q->whereDate('created_at', '>=', now()->subMonth());
                    }]);
                    break;
                case 'year':
                    $userStatsQuery->withCount(['posts' => function ($q) {
                        $q->whereDate('created_at', '>=', now()->subYear());
                    }]);
                    break;
                default:
                    $userStatsQuery->withCount('posts');
                    break;
            }

            // Подсчёт полученных лайков и дизлайков (всего, без привязки к периоду)
            $userStatsQuery->withCount(['ratingsReceived as likes_count' => function ($q) {
                $q->where('type', 'like');
            }]);
            $userStatsQuery->withCount(['ratingsReceived as dislikes_count' => function ($q) {
                $q->where('type', 'dislike');
            }]);

            // Сортировка авторов
            if ($authorSort === 'posts') {
                $userStatsQuery->orderBy('posts_count', 'desc');
            } elseif ($authorSort === 'likes') {
                $userStatsQuery->orderBy('likes_count', 'desc');
            } elseif ($authorSort === 'dislikes') {
                $userStatsQuery->orderBy('dislikes_count', 'desc');
            }

            $userStats = $userStatsQuery->paginate(20);

            // Список пользователей для суперадмина (без изменений)
            if ($user->isSuperAdmin()) {
                $users = User::paginate(10);
            }
        } else {
            $myPosts = $user->posts()->latest()->paginate(10);
        }

        return view('dashboard', compact(
            'users', 'publishedPosts', 'pendingPosts', 'rejectedPosts',
            'trashedPosts', 'myPosts', 'statsPosts', 'period', 'statType',
            'userStats', 'authorPeriod', 'authorSort'
        ));
    }
}