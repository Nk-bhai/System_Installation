<h1>Role Page</h1>

<form action="{{ route('role.store') }}" method="post">
    @csrf
    <label>Role Name</label>
    <input type="text" name="role">

    <br><br>

    <label>permissions</label>
    <input type="checkbox" value="View" name="permissions[]" checked >View
    <input type="checkbox" value="Create" name="permissions[]">Create
    <input type="checkbox" value="Delete" name="permissions[]">Delete
    <input type="checkbox" value="Update" name="permissions[]">Update

    <br><br>

    <input type="submit" value="submit">
</form>


<a href="/dashboard">Dashboard</a>