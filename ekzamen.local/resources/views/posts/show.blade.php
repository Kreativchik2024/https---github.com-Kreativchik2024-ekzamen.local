@extends('layouts.app')

@section('title', $post->title)
@section('header', $post->title)

@section('content')
    <article>
        {{-- Метаданные поста --}}
        <div class="d-flex justify-content-between text-muted mb-3">
            <div>
                <i class="bi bi-person-circle"></i> {{ $post->user->name ?? 'Автор не указан' }}
            </div>
            <div>
                <i class="bi bi-calendar3"></i> {{ $post->created_at->format('d.m.Y H:i') }}
                <span class="ms-3"><i class="bi bi-eye"></i> {{ $post->views ?? 0 }} просмотров</span>
            </div>
        </div>

        {{-- Описание (если есть) --}}
        @if($post->description)
            <div class="lead mb-4">{{ $post->description }}</div>
        @endif

        {{-- Полный контент --}}
        <div class="content mb-5">
            {!! nl2br(e($post->content)) !!}
        </div>
    </article>
    @auth
    <div class="mt-4 d-flex gap-2">
        @can('update', $post)
            <a href="{{ route('posts.edit', $post->post_id) }}" class="btn btn-warning">Редактировать</a>
        @endcan
        @can('delete', $post)
            <form action="{{ route('posts.destroy', $post->post_id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Удалить пост?')">Удалить</button>
            </form>
        @endcan
        @can('approve', $post)
            @if(!$post->is_approved)
                <form action="{{ route('posts.approve', $post->post_id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Одобрить пост</button>
                </form>
            @endif
        @endcan
    </div>
@endauth

    {{-- БЛОК КОММЕНТАРИЕВ --}}
    <section class="comments-section mt-5">
        <h4>Комментарии ({{ $post->comments->where('parent_id', null)->count() }})</h4>

        {{-- Форма для нового комментария (только для авторизованных) --}}
        @auth
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <h5>Оставить комментарий</h5>
                    <form action="{{ route('comments.store', $post) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea name="body" class="form-control" rows="3" placeholder="Ваш комментарий..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </form>
                </div>
            </div>
        @else
            <div class="alert alert-info">
                <a href="{{ route('login') }}">Войдите</a>, чтобы оставить комментарий.
            </div>
        @endauth

        {{-- Список комментариев --}}
        <div class="comments-list">
            @foreach($post->comments->where('parent_id', null) as $comment)
                @include('partials.comment', ['comment' => $comment])
            @endforeach
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .content {
            line-height: 1.7;
            font-size: 1.1rem;
        }
        .comment {
            background: #f8f9fa;
            transition: background 0.2s;
        }
        .comment:hover {
            background: #fff;
        }
        .replies {
            border-left: 2px solid #dee2e6;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Toggle reply form
        document.querySelectorAll('.reply-button').forEach(button => {
            button.addEventListener('click', function() {
                const commentId = this.dataset.commentId;
                const form = document.querySelector(`.reply-form[data-comment-id="${commentId}"]`);
                if (form) {
                    form.style.display = form.style.display === 'none' ? 'block' : 'none';
                }
            });
        });
    </script>
@endpush