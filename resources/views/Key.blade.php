<form action="{{ route('key') }}" method="post">
    @csrf
    <label >Enter Key</label>
    <input type="text" name="key">

    <br><br>

    <input type="submit" value="submit">

    {{ session('error') }}
</form>