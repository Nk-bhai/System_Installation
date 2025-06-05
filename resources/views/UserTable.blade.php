<table border="1">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    @foreach ($data as $dt )
    <tr>
        <td>{{$dt->name}}</td>
        <td>{{$dt->email}}</td>
        <td>{{$dt->role}}</td>
        <td>
            <form action="{{ route('user.destroy' , $dt->id) }}" method="post">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete">
            </form>
            <form action="{{ route('user.edit' , $dt->id) }}" method="get">
                <input type="submit" value="Edit">
            </form>
        </td>
    </tr>
    @endforeach
</table>


