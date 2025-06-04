<h1>User Crud Page</h1>
<form action="{{ route('user.store') }}" method="post">
    @csrf
    <label >Name</label>
    <input type="text" name="name">

    <br><br>

    <label>Age</label>
    <input type="number" name="age">

    <br><br>

    <input type="submit" value="submit">

</form>

<table border="1">
    <tr>
        <th>Name</th>
        <th>Age</th>
        <th>Actions</th>
    </tr>
    @foreach ($data as $dt )
    <tr>
        <td>{{$dt->name}}</td>
        <td>{{$dt->age}}</td>
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

<a href="/dashboard">Dashboard</a>