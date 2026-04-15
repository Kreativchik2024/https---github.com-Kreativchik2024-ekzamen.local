@extends('layouts.app')

@section('title', 'Редактирование поста')
@section('header', 'Редактирование: ' . $post->title)

@section('content')
    <form action="{{ route('posts.update', $post->post_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Заголовок</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Краткое описание</label>
            <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $post->description) }}">
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Содержание</label>
            <textarea name="content" id="content" rows="10" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $post->content) }}</textarea>
            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">Обновить</button>
        <a href="{{ route('posts.show', $post->post_id) }}" class="btn btn-secondary">Отмена</a>
    </form>
@endsection