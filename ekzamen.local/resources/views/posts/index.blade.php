@extends('layouts.app')

@section('title', 'Все посты')
@section('header', 'Список статей')

@section('content')
    <div id="posts-container">
        @include('posts._post_list', ['posts' => $posts])
    </div>

    <div class="text-center mt-4" id="loader">
        <div class="spinner-border text-primary" style="display: none;" role="status"></div>
    </div>
@endsection

@push('scripts')
<script>
    let page = 1;
    let loading = false;
    let hasMorePages = {{ $posts->hasMorePages() ? 'true' : 'false' }};

    function loadMore() {
        if (loading || !hasMorePages) return;
        loading = true;
        document.querySelector('#loader .spinner-border').style.display = 'inline-block';

        page++;
        fetch(`?page=${page}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            document.querySelector('#posts-container').insertAdjacentHTML('beforeend', html);
            loading = false;
            document.querySelector('#loader .spinner-border').style.display = 'none';
            // Проверяем, есть ли ещё страницы (можно передавать из ответа)
            if (html.trim() === '') hasMorePages = false;
        })
        .catch(() => {
            loading = false;
            document.querySelector('#loader .spinner-border').style.display = 'none';
        });
    }

    // Бесконечный скролл
    window.addEventListener('scroll', () => {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200) {
            loadMore();
        }
    });
</script>
@endpush