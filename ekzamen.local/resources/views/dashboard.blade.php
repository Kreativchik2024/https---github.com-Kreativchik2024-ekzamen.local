@extends('layouts.app')

@section('title', 'Профиль')
@section('header', 'Личный кабинет')

@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>Имя:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Дата регистрации:</strong> {{ Auth::user()->created_at->format('d.m.Y') }}</p>
        </div>
    </div>
@endsection