<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BLOG w523')</title>
    <!-- Bootstrap + Icons + пользовательские стили -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
    <div class="wrapper d-flex flex-column min-vh-100">
        @include('partials.header')

        <main class="flex-grow-1 py-5">
            <div class="container">
                <div class="row g-4">
                    <!-- Сайдбар (слева) -->
                    <div class="col-lg-3 col-md-4">
                        @hasSection('sidebar')
                            @yield('sidebar')
                        @else
                            @include('partials.sidebar', ['recentPosts' => $recentPosts ?? []])
                        @endif
                    </div>

                    <!-- Основной контент (справа) в карточке -->
                    <div class="col-lg-9 col-md-8">
                        <div class="main-card p-4 p-lg-5">
                            @if(trim($__env->yieldContent('header')))
                                <div class="border-bottom pb-3 mb-4">
                                    <h1 class="display-6 fw-semibold">@yield('header', '')</h1>
                                </div>
                            @endif
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </main>

        @include('partials.footer')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>