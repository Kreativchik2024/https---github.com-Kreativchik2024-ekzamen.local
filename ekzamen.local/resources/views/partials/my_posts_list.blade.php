@if($myPosts->count())
    <ul>
        @foreach($myPosts as $post)
            <li><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a> ({{ $post->created_at->format('d.m.Y') }})</li>
        @endforeach
    </ul>
    {{ $myPosts->links() }}
@else
    <p>У вас пока нет постов. <a href="{{ route('posts.create') }}">Создать первый пост</a></p>
@endif