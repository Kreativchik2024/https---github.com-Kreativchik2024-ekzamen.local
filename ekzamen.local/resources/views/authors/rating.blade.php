@extends('layouts.app')

@section('title', 'Рейтинг авторов')
@section('header', 'Рейтинг авторов')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <span>Авторы блога</span>
            <form method="GET" action="{{ route('authors.rating') }}" class="d-inline">
                <select name="sort" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                    <option value="likes" {{ ($sortBy ?? 'likes') == 'likes' ? 'selected' : '' }}>По лайкам</option>
                    <option value="posts" {{ ($sortBy ?? 'likes') == 'posts' ? 'selected' : '' }}>По количеству постов</option>
                    <option value="dislikes" {{ ($sortBy ?? 'likes') == 'dislikes' ? 'selected' : '' }}>По дизлайкам</option>
                </select>
            </form>
        </div>
    </div>
    <div class="card-body">
        @if($authors->count())
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Автор</th>
                            <th>Email</th>
                            <th>Постов</th>
                            <th>Лайков</th>
                            <th>Дизлайков</th>
                            <th>Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($authors as $author)
                        <tr>
                            <td>{{ $author->name }}</td>
                            <td>{{ $author->email }}</td>
                            <td>{{ $author->posts_count }}</td>
                            <td>{{ $author->likes_count ?? 0 }}</td>
                            <td>{{ $author->dislikes_count ?? 0 }}</td>
                            <td>
                                @if(Auth::id() !== $author->id)
                                    <form action="{{ route('authors.rating.like', $author) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $author->likedByCurrentUser() ? 'btn-primary' : 'btn-outline-primary' }}">👍</button>
                                    </form>
                                    <form action="{{ route('authors.rating.dislike', $author) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $author->dislikedByCurrentUser() ? 'btn-danger' : 'btn-outline-danger' }}">👎</button>
                                    </form>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $authors->links() }}
        @else
            <p>Нет авторов.</p>
        @endif
    </div>
</div>
@endsection