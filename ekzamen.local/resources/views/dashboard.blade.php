@extends('layouts.app')

@section('title', 'Панель управления')
@section('header', 'Добро пожаловать, ' . Auth::user()->name)

@section('content')
    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
        <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
            <li class="nav-item"><button class="nav-link active" id="published-tab" data-bs-toggle="tab" data-bs-target="#published">Опубликованные</button></li>
            <li class="nav-item"><button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending">На модерации</button></li>
            <li class="nav-item"><button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected">Отклонённые</button></li>
            <li class="nav-item"><button class="nav-link" id="trashed-tab" data-bs-toggle="tab" data-bs-target="#trashed">Удалённые</button></li>
            @if(Auth::user()->isSuperAdmin())
                <li class="nav-item"><button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users">Пользователи</button></li>
            @endif
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="published">
                <div class="card">
                    <div class="card-header">Опубликованные посты</div>
                    <div class="card-body">
                        @include('partials.post_list_admin', ['posts' => $publishedPosts, 'showApprove' => false])
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pending">
                <div class="card">
                    <div class="card-header">Посты на модерации</div>
                    <div class="card-body">
                        @include('partials.post_list_admin', ['posts' => $pendingPosts, 'showApprove' => true])
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="rejected">
                <div class="card">
                    <div class="card-header">Отклонённые посты</div>
                    <div class="card-body">
                        @include('partials.post_list_admin', ['posts' => $rejectedPosts, 'showApprove' => true])
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="trashed">
                <div class="card">
                    <div class="card-header">Удалённые посты</div>
                    <div class="card-body">
                        @include('partials.post_list_admin', ['posts' => $trashedPosts, 'showRestore' => true])
                    </div>
                </div>
            </div>
            @if(Auth::user()->isSuperAdmin())
                <div class="tab-pane fade" id="users">
                    <div class="card">
                        <div class="card-header">Пользователи</div>
                        <div class="card-body">
                            @include('partials.users_list', ['users' => $users])
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="card">
            <div class="card-header">Мои посты</div>
            <div class="card-body">
                @if($myPosts->count())
                    <ul class="list-group">
                        @foreach($myPosts as $post)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                                <span class="badge bg-{{ $post->status === 'approved' ? 'success' : ($post->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ $post->status === 'approved' ? 'Опубликован' : ($post->status === 'pending' ? 'На модерации' : 'Отклонён') }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    {{ $myPosts->links() }}
                @else
                    <p>У вас пока нет постов. <a href="{{ route('posts.create') }}">Создать первый пост</a></p>
                @endif
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    // Сохранение активной вкладки
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabs.forEach(tab => {
            tab.addEventListener('shown.bs.tab', function (event) {
                const targetId = event.target.getAttribute('data-bs-target');
                localStorage.setItem('activeTab', targetId);
            });
        });
        const activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            const tabToActivate = document.querySelector(`button[data-bs-target="${activeTab}"]`);
            if (tabToActivate) {
                const tab = new bootstrap.Tab(tabToActivate);
                tab.show();
            }
        }
    });
</script>
@endpush