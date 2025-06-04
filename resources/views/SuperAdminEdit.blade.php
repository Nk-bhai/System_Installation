<h1>Super Admin Edit Page</h1>
@foreach ($data as $dt )
    
<form action="{{ route('superAdmin.update' , $dt->id) }}" method="post">
    @csrf
    @method('PUT')
    <label>Email</label>
    <input type="email" name="email" value="{{ $dt->email }}">

    <br><br>
    
    <label>Key</label>
    <input type="text" name="key" value="{{ $dt->key }}">

    <br><br>

    <input type="submit" value="Update">
    @endforeach
</form>