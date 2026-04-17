<div class="sidebar">
    @php
        // Принудительно выбираем только одобренные посты для сайдбара
        $recentPosts = \App\Models\Post::where('is_approved', true)->latest()->take(5)->get();
    @endphp

    <!-- Виджет: Последние посты -->
    <div class="card shadow-sm mb-4 border-0 rounded-4">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="bi bi-clock-history text-primary me-2"></i>Последние записи
            </h5>
            <ul class="list-unstyled mb-0">
                @forelse($recentPosts as $post)
                    <li class="mb-3 pb-2 border-bottom">
                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none fw-semibold d-block mb-1">
                            {{ $post->title }}
                        </a>
                        <small class="text-muted">
                            <i class="bi bi-calendar3"></i> {{ $post->created_at->format('d.m.Y') }}
                        </small>
                    </li>
                @empty
                    <li class="text-muted">Нет постов</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Остальной код сайдбара (категории и т.д.) -->
</div>