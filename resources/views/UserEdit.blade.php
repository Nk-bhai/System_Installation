<h1>User Crud Update Page</h1>
@foreach ($data as $dt )
    
<form action="{{ route('user.update' , $dt->id) }}" method="post">
    @csrf
    @method('PUT')
    <label >Name</label>
    <input type="text" name="name" value="{{ $dt->name }}">
    
    <br><br>

    <label>Age</label>
    <input type="number" name="age" value="{{ $dt->age }}">
    
    <br><br>

    <input type="submit" value="Update">

</form>
@endforeach