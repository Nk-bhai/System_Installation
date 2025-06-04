<h1>Admin Login Page</h1>

<form action="{{ route('admin') }}" method="post">
    @csrf
    <label >Email</label>
    <input type="email" name="email">

    <br><br>

    <label >Password</label>
    <input type="password" name="password">

    <br><br>

    <input type="submit" value="Login">
</form>

{{ session('error') }}