<div class="sidebar">
    <!-- Первая карточка (например, для аватара или рекламы) -->
    <div class="card shadow-sm mb-4 border-0 rounded-4 overflow-hidden">
        <div class="card-body text-center bg-light">
            <i class="bi bi-person-circle fs-1 text-muted"></i>
            <h6 class="mt-2">Добро пожаловать!</h6>
            <p class="small text-muted mb-0">Блог о веб-разработке</p>
        </div>
    </div>

    <!-- Карточка: Последние посты -->
    <div class="card shadow-sm mb-4 border-0 rounded-4">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="bi bi-clock-history text-primary me-2"></i>Последние посты
            </h5>
            <ul class="list-unstyled mb-0">
                @forelse($recentPosts ?? [] as $post)
                    <li class="mb-3 pb-2 border-bottom">
                        <a href="" class="text-decoration-none fw-semibold d-block mb-1">
                            {{ $post->title }}
                        </a>
                        <small class="text-muted">
                            <i class="bi bi-calendar3"></i> {{ $post->created_at->format('d.m.Y') }}
                        </small>
                    </li>
                @empty
                    <li class="text-muted">Пока нет постов</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Третья карточка (например, для цитаты или баннера) -->
    <div class="card shadow-sm border-0 rounded-4 bg-dark text-white text-center">
        <div class="card-body">
            <i class="bi bi-quote fs-1"></i>
            <p class="small mt-2">«Место для рекламы»</p>
            <hr class="my-2 bg-white">
            <small class="opacity-75">@php echo date('Y'); @endphp</small>
        </div>
    </div>
</div>