<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<h1>Role Page</h1>

<form action="{{ route('role.store') }}" method="post" id="role_assign_form">
    @csrf
    <label>Role Name</label>
    <input type="text" name="role" id="role_name">
    <span id="role_name_error" style="color:red"></span>

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

<table border="1">
    <tr>
        <th>Role Name</th>
        <th>Permissions</th>
        <th>Actions</th>
    </tr>
    @foreach ($roleData as $rd )
        <tr>
            <td>{{$rd->role_name}}</td>
            <td>{{$rd->permissions}}</td>
            <td>
                <form action="{{ route('role.destroy' , $rd->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="Delete">
                </form>
                <form action="{{ route('role.edit' , $rd->id) }}" method="get">
                    <input type="submit" value="Edit">
                </form>
            </td>
        </tr>
    @endforeach
</table>



<script>
      
        $(document).ready(function () {
            $("#role_name").on("input", Validate_Role_name);

            $("#role_assign_form").submit(function (e) {
                let role_name = Validate_Role_name();
                
                if (!role_name) {
                    e.preventDefault();
                }
            })
        })

     // role name validations
        function Validate_Role_name() {
            let role_name = $("#role_name").val();
            if (role_name == "") {
                $("#role_name_error").html("Role name cannot be blank");
                return false;
            }
            else if (!/^[A-Za-z ]{1,100}$/.test(role_name)) {
                $("#role_name_error").html("role name must contain only characters and spaces");
                return false;
            }
            else {
                $("#role_name_error").html("");
                return true;
            }
        }

</script>