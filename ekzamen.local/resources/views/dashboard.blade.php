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
            <li class="nav-item"><button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats">Статистика</button></li>
            <li class="nav-item"><button class="nav-link" id="authors-tab" data-bs-toggle="tab" data-bs-target="#authors">Авторы</button></li>
            @if(Auth::user()->isSuperAdmin())
                <li class="nav-item"><button class="nav-link" id="users-tab" data-bs-toggle="tab" data-bs-target="#users">Пользователи</button></li>
            @endif
        </ul>

        <div class="tab-content">
            <!-- Опубликованные -->
            <div class="tab-pane fade show active" id="published">
                <div class="card">
                    <div class="card-header">Опубликованные посты</div>
                    <div class="card-body">
                        @include('partials.post_list_admin', ['posts' => $publishedPosts, 'showApprove' => false])
                    </div>
                </div>
            </div>

            <!-- На модерации -->
            <div class="tab-pane fade" id="pending">
                <div class="card">
                    <div class="card-header">Посты на модерации</div>
                    <div class="card-body">
                        @include('partials.post_list_admin', ['posts' => $pendingPosts, 'showApprove' => true])
                    </div>
                </div>
            </div>

            <!-- Отклонённые -->
            <div class="tab-pane fade" id="rejected">
                <div class="card">
                    <div class="card-header">Отклонённые посты</div>
                    <div class="card-body">
                        @include('partials.post_list_admin', ['posts' => $rejectedPosts, 'showApprove' => true])
                    </div>
                </div>
            </div>

            <!-- Удалённые -->
            <div class="tab-pane fade" id="trashed">
                <div class="card">
                    <div class="card-header">Удалённые посты</div>
                    <div class="card-body">
                        @include('partials.post_list_admin', ['posts' => $trashedPosts, 'showRestore' => true])
                    </div>
                </div>
            </div>

            <!-- Статистика постов (просмотры, лайки, дизлайки) -->
            <div class="tab-pane fade" id="stats">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Статистика</span>
                            <div class="d-flex gap-2">
                                <form method="GET" action="{{ url()->current() }}" class="d-inline" id="statsForm">
                                    <input type="hidden" name="tab" value="stats">
                                    <select name="period" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                        <option value="day" {{ ($period ?? 'all') == 'day' ? 'selected' : '' }}>За день</option>
                                        <option value="week" {{ ($period ?? 'all') == 'week' ? 'selected' : '' }}>За неделю</option>
                                        <option value="month" {{ ($period ?? 'all') == 'month' ? 'selected' : '' }}>За месяц</option>
                                        <option value="year" {{ ($period ?? 'all') == 'year' ? 'selected' : '' }}>За год</option>
                                        <option value="all" {{ ($period ?? 'all') == 'all' ? 'selected' : '' }}>За всё время</option>
                                    </select>
                                    <select name="stat_type" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                        <option value="views" {{ ($statType ?? 'views') == 'views' ? 'selected' : '' }}>Просмотры</option>
                                        <option value="likes" {{ ($statType ?? 'views') == 'likes' ? 'selected' : '' }}>Лайки</option>
                                        <option value="dislikes" {{ ($statType ?? 'views') == 'dislikes' ? 'selected' : '' }}>Дизлайки</option>
                                    </select>
                                </form>
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-secondary active" id="tableViewBtn">Таблица</button>
                                    <button type="button" class="btn btn-outline-secondary" id="chartViewBtn">График</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="tableView">
                            @if(isset($statsPosts) && $statsPosts->count())
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Название поста</th>
                                                <th>
                                                    @if($statType == 'views') Просмотры
                                                    @elseif($statType == 'likes') Лайки
                                                    @else Дизлайки @endif
                                                </th>
                                                <th>Дата публикации</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($statsPosts as $post)
                                            <tr>
                                                <td><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></td>
                                                <td>
                                                    @if($statType == 'views') {{ $post->views }}
                                                    @elseif($statType == 'likes') {{ $post->likes_count ?? 0 }}
                                                    @else {{ $post->dislikes_count ?? 0 }} @endif
                                                </td>
                                                <td>{{ $post->created_at->format('d.m.Y') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $statsPosts->links() }}
                            @else
                                <p>Нет постов за выбранный период.</p>
                            @endif
                        </div>
                        <div id="chartView" style="display: none;">
                            <canvas id="statsChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Статистика по авторам (количество постов + лайки/дизлайки) -->
         <div class="tab-pane fade" id="authors">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span>Активность авторов</span>
                <div class="d-flex gap-2">
                    <form method="GET" action="{{ url()->current() }}" class="d-inline">
                        <input type="hidden" name="tab" value="authors">
                        <select name="author_period" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                            <option value="day" {{ ($authorPeriod ?? 'all') == 'day' ? 'selected' : '' }}>За день</option>
                            <option value="week" {{ ($authorPeriod ?? 'all') == 'week' ? 'selected' : '' }}>За неделю</option>
                            <option value="month" {{ ($authorPeriod ?? 'all') == 'month' ? 'selected' : '' }}>За месяц</option>
                            <option value="year" {{ ($authorPeriod ?? 'all') == 'year' ? 'selected' : '' }}>За год</option>
                            <option value="all" {{ ($authorPeriod ?? 'all') == 'all' ? 'selected' : '' }}>За всё время</option>
                        </select>
                        <select name="author_sort" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                            <option value="posts" {{ ($authorSort ?? 'posts') == 'posts' ? 'selected' : '' }}>По количеству постов</option>
                            <option value="likes" {{ ($authorSort ?? 'posts') == 'likes' ? 'selected' : '' }}>По полученным лайкам</option>
                            <option value="dislikes" {{ ($authorSort ?? 'posts') == 'dislikes' ? 'selected' : '' }}>По полученным дизлайкам</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(isset($userStats) && $userStats->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Пользователь</th>
                                <th>Email</th>
                                <th>Роль</th>
                                <th>Постов (за период)</th>
                                <th>Лайков (всего)</th>
                                <th>Дизлайков (всего)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userStats as $author)
                            <tr>
                                <td>{{ $author->name }}</td>
                                <td>{{ $author->email }}</td>
                                <td>{{ $author->role }}</td>
                                <td>{{ $author->posts_count ?? 0 }}</td>
                                <td>{{ $author->likes_count ?? 0 }}</td>
                                <td>{{ $author->dislikes_count ?? 0 }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $userStats->links() }}
            @else
                <p>Нет данных за выбранный период.</p>
            @endif
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableViewBtn = document.getElementById('tableViewBtn');
        const chartViewBtn = document.getElementById('chartViewBtn');
        const tableView = document.getElementById('tableView');
        const chartView = document.getElementById('chartView');
        let chartInstance = null;

        function showTableView() {
            tableView.style.display = 'block';
            chartView.style.display = 'none';
            tableViewBtn.classList.add('active');
            chartViewBtn.classList.remove('active');
        }

        function showChartView() {
            tableView.style.display = 'none';
            chartView.style.display = 'block';
            chartViewBtn.classList.add('active');
            tableViewBtn.classList.remove('active');
            renderChart();
        }

        function renderChart() {
            const canvas = document.getElementById('statsChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            const labels = @json($statsPosts->pluck('title'));
            let data = [];
            if ('{{ $statType }}' === 'views') {
                data = @json($statsPosts->pluck('views'));
            } else if ('{{ $statType }}' === 'likes') {
                data = @json($statsPosts->pluck('likes_count'));
            } else {
                data = @json($statsPosts->pluck('dislikes_count'));
            }
            if (chartInstance) {
                chartInstance.destroy();
            }
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '{{ $statType === 'views' ? 'Просмотры' : ($statType === 'likes' ? 'Лайки' : 'Дизлайки') }}',
                        data: data,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });
        }

        if (tableViewBtn && chartViewBtn) {
            tableViewBtn.addEventListener('click', showTableView);
            chartViewBtn.addEventListener('click', showChartView);
        }

        // Сохранение активной вкладки (работает с localStorage и параметром tab)
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
        const urlParams = new URLSearchParams(window.location.search);
        const tabParam = urlParams.get('tab');
        if (tabParam) {
            const tabToActivate = document.querySelector(`button[data-bs-target="#${tabParam}"]`);
            if (tabToActivate) {
                const tab = new bootstrap.Tab(tabToActivate);
                tab.show();
                localStorage.setItem('activeTab', `#${tabParam}`);
            }
        }
    });
</script>
@endpush