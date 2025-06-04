<h1>Dashboard</h1>

<p>Role</p>
<form action="" method="post">
    @csrf
    <input type="submit" value="Role install">
    
</form>

<p>User</p>
<form action="{{ route('UserCrudInstall') }}" method="post">
    @csrf
    <input type="submit" value="User install">

</form>

<form action="{{ route('logout') }}" method="post">
    @csrf
    <input type="submit" value="Logout">
</form> 