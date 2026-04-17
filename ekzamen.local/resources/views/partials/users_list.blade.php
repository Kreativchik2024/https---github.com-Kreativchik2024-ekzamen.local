<table class="table table-bordered">
    <thead>
        <tr><th>ID</th><th>Имя</th><th>Email</th><th>Роль</th><th>Действия</th></tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->role }}</td>
            <td>
                <form method="POST" action="{{ route('admin.updateRole', $user) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <select name="role" class="form-select form-select-sm" onchange="if(confirm('Изменить роль пользователя {{ $user->name }}?')) this.form.submit();">
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Пользователь</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Администратор</option>
                        <option value="super_admin" {{ $user->role == 'super_admin' ? 'selected' : '' }}>Суперадмин</option>
                    </select>
                </form>
             </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $users->links() }}