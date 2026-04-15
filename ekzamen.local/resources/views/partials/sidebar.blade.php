<div class="sidebar">


    <!-- Виджет: Последние посты -->
    <div class="card shadow-sm mb-4 border-0 rounded-4">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="bi bi-clock-history text-primary me-2"></i>Последние записи
            </h5>
            <ul class="list-unstyled mb-0">
                @forelse($recentPosts ?? [] as $post)
                    <li class="mb-3 pb-2 border-bottom">
                        {{-- Ссылка на пост – используем $post (Laravel сам возьмёт post_id) --}}
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

    <!-- Виджет: Категории (если есть) – пример -->
    @if(isset($categories) && $categories->count())
    <div class="card shadow-sm mb-4 border-0 rounded-4">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="bi bi-tags text-primary me-2"></i>Категории
            </h5>
            <div class="d-flex flex-wrap gap-2">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="badge bg-light text-dark text-decoration-none px-3 py-2 rounded-pill">
                        {{ $category->name }} ({{ $category->posts_count ?? 0 }})
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>