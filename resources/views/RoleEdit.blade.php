<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<h1>Role Update Page</h1>
@foreach ($role_edit_data as $re)

    <form action="{{ route('role.update', $re->id) }}" method="post" id="role_assign_form">
        @csrf
        @method('PUT')
        <label>Role Name</label>
        <input type="text" name="role_name" id="role_name" value="{{ $re->role_name }}">
        <span id="role_name_error" style="color:red"></span>

        <br><br>

        <label>permissions</label>
        @if (in_array('View', $permissions))
            <input type="checkbox" value="View" name="permissions[]" checked>View
        @else
            <input type="checkbox" value="View" name="permissions[]">View
        @endif

        @if(in_array('Create', $permissions))
            <input type="checkbox" value="Create" name="permissions[]" checked>Create
        @else
            <input type="checkbox" value="Create" name="permissions[]">Create
        @endif
        
        @if (in_array('Delete', $permissions))
            <input type="checkbox" value="Delete" name="permissions[]" checked>Delete
        @else
            <input type="checkbox" value="Delete" name="permissions[]">Delete
        @endif
        
        @if (in_array('Update', $permissions))
            <input type="checkbox" value="Update" name="permissions[]">Update
        @else
            <input type="checkbox" value="Update" name="permissions[]">Update
        @endif
        


        <br><br>

        <input type="submit" value="submit">
    </form>

@endforeach



<a href="/dashboard">Dashboard</a>




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