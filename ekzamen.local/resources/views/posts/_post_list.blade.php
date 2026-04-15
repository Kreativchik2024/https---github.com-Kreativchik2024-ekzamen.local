@foreach($posts as $post)
    <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden">
        <div class="card-body p-4">
            <h2 class="h4 fw-semibold mb-2">
                <a href="{{ route('posts.show', $post) }}" class="text-dark text-decoration-none stretched-link">
                    {{ $post->title }}
                </a>
            </h2>
            <div class="text-muted small mb-3">
                <i class="bi bi-calendar3 me-1"></i> {{ $post->created_at->format('d.m.Y') }}
                <span class="mx-2">•</span>
                <i class="bi bi-eye me-1"></i> {{ $post->views ?? 0 }} просмотров
            </div>
            <p class="card-text">{{ $post->description ?? Str::limit($post->content, 120) }}</p>
            <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                Читать далее <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
@endforeach