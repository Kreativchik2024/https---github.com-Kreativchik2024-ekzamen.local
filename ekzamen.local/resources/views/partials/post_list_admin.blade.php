@if($posts->count())
    <ul class="list-group">
        @foreach($posts as $post)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                <div>
                    @if(isset($showApprove) && $showApprove)
                        <form action="{{ route('posts.approve', $post) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-success">Одобрить</button>
                        </form>
                        <form action="{{ route('posts.reject', $post) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Отклонить пост?')">Отклонить</button>
                        </form>
                    @endif
                    @if(isset($showRestore) && $showRestore)
                        <form action="{{ route('posts.restore', $post->post_id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-warning">Восстановить</button>
                        </form>
                    @endif
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить пост?')">Удалить</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
    {{ $posts->links() }}
@else
    <p>Нет постов.</p>
@endif