@extends('layouts.guest')

@section('title', 'Регистрация')

@section('content')
<div class="row justify-content-center w-100">
    <div class="col-md-8 col-lg-6">
        <div class="glass-card p-4 p-lg-5">
            <div class="text-center mb-4">
                <div class="bg-white rounded-circle d-inline-flex p-3 shadow-sm mb-3">
                    <i class="bi bi-person-plus fs-1 text-gradient"></i>
                </div>
                <h2 class="fw-bold">Создать аккаунт</h2>
                <p class="text-muted">Присоединяйтесь к нашему блогу</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Имя</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" required autofocus placeholder="Анна">
                    </div>
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required placeholder="hello@example.com">
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Пароль</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               required placeholder="минимум 8 символов">
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Подтверждение пароля</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" name="password_confirmation" class="form-control" required placeholder="повторите пароль">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-person-plus me-2"></i>Зарегистрироваться
                </button>

                <div class="text-center mt-4">
                    <p class="mb-0">Уже есть аккаунт? <a href="{{ route('login') }}" class="fw-semibold">Войти</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection