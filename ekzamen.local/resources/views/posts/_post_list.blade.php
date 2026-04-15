@foreach($posts as $post)
    <div class="post-card p-4 mb-4">
        <div class="card-body p-0">
            <h2 class="h4 fw-semibold mb-2">
                <a href="{{ route('posts.show', $post->post_id) }}" class="text-dark text-decoration-none stretched-link">
                    {{ $post->title }}
                </a>
            </h2>
            <div class="text-muted small mb-3">
                <i class="bi bi-calendar3 me-1"></i> {{ $post->created_at->format('d.m.Y') }}
                <span class="mx-2">•</span>
                <i class="bi bi-eye me-1"></i> {{ $post->views ?? 0 }} просмотров
                @if(!$post->is_approved && (auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())))
                    <span class="mx-2">•</span>
                    <span class="badge bg-warning text-dark">На модерации</span>
                @endif
            </div>
            <p class="card-text">{{ $post->description ?? Str::limit($post->content, 120) }}</p>
            
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('posts.show', $post->post_id) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    Читать далее <i class="bi bi-arrow-right ms-1"></i>
                </a>
                
                @auth
                    @can('update', $post)
                        <div>
                            <a href="{{ route('posts.edit', $post->post_id) }}" class="btn btn-sm btn-outline-secondary me-1">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('posts.destroy', $post->post_id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить пост?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    @endcan
                @endauth
            </div>
        </div>
    </div>
@endforeach