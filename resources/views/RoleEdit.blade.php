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
        
        <div id="permissions_error" style="color:red"></div>

        <br><br>

        <input type="submit" value="submit">
    </form>

@endforeach



<a href="/dashboard">Dashboard</a>


<script>

    $(document).ready(function () {
        $("#role_name").on("input", Validate_Role_name);
        $("input[name='permissions[]']").on("change", Validate_Permissions);

        $("#role_assign_form").submit(function (e) {
            let role_name_valid = Validate_Role_name();
            let permissions_valid = Validate_Permissions();

            if (!role_name_valid || !permissions_valid) {
                e.preventDefault(); // Prevent submission
            }
        });
    });
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

    function Validate_Permissions() {
        if ($("input[name='permissions[]']:checked").length === 0) {
            $("#permissions_error").html("At least one permission must be selected");
            return false;
        } else {
            $("#permissions_error").html("");
            return true;
        }
    }


</script>