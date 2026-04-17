@if($posts->count())
    <ul>
        @foreach($posts as $post)
            <li>
                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                <form action="{{ route('posts.approve', $post) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-success">Одобрить</button>
                </form>
            </li>
        @endforeach
    </ul>
    {{ $posts->links() }}
@else
    <p>Нет постов на модерации.</p>
@endif