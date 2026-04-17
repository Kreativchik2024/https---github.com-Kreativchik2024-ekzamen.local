<link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

<header>
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark shadow-sm sticky-top" x-data="{ open: false }">
        <div class="container">
            <!-- Логотип -->
            <a class="navbar-brand fw-bold fs-3" href="{{ route('home') }}">
                <i class="bi bi-journal-bookmark-fill me-2"></i>PV315
            </a>

            <!-- Кнопка для мобильных -->
            <button class="navbar-toggler" type="button" @click="open = !open">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Основное меню (десктоп + мобильное) -->
            <div class="collapse navbar-collapse" :class="{'show': open}" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                    @auth
      
    <!-- остальные пункты -->
@endauth
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Войти</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Регистрация</a></li>
                    @else
                        <!-- Выпадающее меню пользователя (Bootstrap dropdown) -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
     @auth
    @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
        <li class="nav-item"><a class="nav-link" href="{{ route('admin.index') }}" style="color: #8b5cf6 !important;">Админка</a></li>
         
    @endif
    @auth
    <li class="nav-item"><a class="nav-link" href="{{ route('posts.create') }}" style="color: #8b5cf6 !important;">Новый пост</a></li>
    @endif
    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}" style="color: #8b5cf6 !important;">Профиль</a></li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="color: #8b5cf6 !important;">Выйти</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
    </li>
@endauth
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</header>