@extends('layouts.guest')

@section('title', 'Вход')

@section('content')
<div class="row justify-content-center w-100">
    <div class="col-md-6 col-lg-5">
        <div class="glass-card p-4 p-lg-5">
            <div class="text-center mb-4">
                <div class="bg-white rounded-circle d-inline-flex p-3 shadow-sm mb-3">
                    <i class="bi bi-person-circle fs-1 text-gradient"></i>
                </div>
                <h2 class="fw-bold">Добро пожаловать</h2>
                <p class="text-muted">Войдите в свой аккаунт</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" required autofocus placeholder="hello@example.com">
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Пароль</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               required placeholder="••••••">
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Запомнить меня</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-decoration-none small">Забыли пароль?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Войти
                </button>

                <div class="text-center mt-4">
                    <p class="mb-0">Нет аккаунта? <a href="{{ route('register') }}" class="fw-semibold">Зарегистрироваться</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection