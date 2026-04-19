@extends('layouts.app')

@section('title', 'Новый пост')
@section('header', 'Создание поста')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST"> 
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Заголовок *</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Краткое описание</label>
            <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}">
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Содержание *</label>
            <textarea name="content" id="content" rows="10" class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">Опубликовать</button>
        <a href="{{ route('home') }}" class="btn btn-secondary">Отмена</a>
    </form>
@endsection