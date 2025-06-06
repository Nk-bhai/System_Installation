@foreach ($user_name as  $name)
        <h1>Welcome {{$name->name}}</h1>
@endforeach
<table border="1">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    @foreach ($data as $dt)
        <tr>
            <td>{{ $dt->name }}</td>
            <td>{{ $dt->email }}</td>
            <td>{{ $dt->role }}</td>
            <td>
                @if (in_array('Delete', $permissions))
                    <form action="{{ route('user.destroy', $dt->id) }}" method="post" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="Delete">
                    </form>
                @endif

                @if (in_array('Update', $permissions))
                    <form action="{{ route('user.edit', $dt->id) }}" method="get" style="display:inline;">
                        <input type="submit" value="Edit">
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
</table>

@if (in_array('Create', $permissions))
    <a href="{{ route('user.index') }}">Create User</a>
@endif