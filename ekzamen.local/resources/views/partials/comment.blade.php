<div class="comment mb-3 p-3 border rounded-3" id="comment-{{ $comment->id }}">
    <div class="d-flex justify-content-between">
        <strong>{{ $comment->user->name ?? 'Гость' }}</strong>
        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
    </div>
    <div class="mt-2">
        {{ $comment->body }}
    </div>

    {{-- Кнопка "Ответить" (только для авторизованных) --}}
    @auth
        <button class="btn btn-sm btn-link p-0 mt-2 reply-button" data-comment-id="{{ $comment->id }}">
            Ответить
        </button>

        {{-- Скрытая форма ответа --}}
        <div class="reply-form mt-2" style="display: none;" data-comment-id="{{ $comment->id }}">
            <form action="{{ route('comments.store', $comment->post) }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <div class="input-group">
                    <textarea name="body" class="form-control" rows="2" placeholder="Ваш ответ..." required></textarea>
                    <button class="btn btn-outline-secondary" type="submit">Ответить</button>
                </div>
            </form>
        </div>
    @endauth

    {{-- Вложенные ответы --}}
    @if($comment->replies->count())
        <div class="replies mt-3 ms-4 ps-3">
            @foreach($comment->replies as $reply)
                @include('partials.comment', ['comment' => $reply])
            @endforeach
        </div>
    @endif
</div>