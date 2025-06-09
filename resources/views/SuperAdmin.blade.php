<h1>Super Admin</h1>

<form action="{{ route('superAdmin.store') }}" method="post">
    @csrf
    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}">
    @error('email')
        {{ $message }}
    @enderror

    <br><br>

    <label>Password</label>
    <input type="password" name="password" value="{{ old('password') }}">
    @error('password')
        {{ $message }}
    @enderror

    <br><br>

    <label>Key</label>
    <input type="text" name="key" value="{{ old('key') }}">
    @error('key')
        {{ $message }}
    @enderror

    <br><br>

    <input type="submit" value="Activate">
</form>

<table border="1">
    <tr>

        <th>Email</th>
        <th>Password</th>
        <th>Key</th>
        <th>Actions</th>
    </tr>

    @foreach ($data as $dt)
        <tr>
            <td>{{$dt->email}}</td>
            <td>{{$dt->password}}</td>
            <td>{{$dt->key}}</td>
            <td>
                <form action="{{ route('superAdmin.destroy', $dt->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="Delete">
                </form>
                <form action="{{ route('superAdmin.edit', $dt->id) }}" method="get">
                    @csrf
                    <input type="submit" value="Edit">
                </form>

            </td>
        </tr>

    @endforeach

</table>