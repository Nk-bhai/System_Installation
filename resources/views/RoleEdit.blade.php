{{-- @foreach ($role_edit_data as $re)

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

@endforeach --}}
{{-- <a href="/dashboard">Dashboard</a> --}}




@extends('master')
@section('contents')

    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
            style="background-image: url(../../dist/assets/media/illustrations/dozzy-1/14.png">
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                {{-- <h1 style="color:green">Admin Page</h1> --}}
                <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">

                    <div class="card-body">
                        @foreach ($role_edit_data as $re)
                            <form action="{{ route('role.update', $re->id) }}" method="post" id="role_assign_form">
                                <div class="text-center mb-10">
                                    <h1 class="text-dark mb-3"> Role Update Page</h1>
                                </div>
                                @csrf
                                @method('PUT')
                                <div class="mb-5">
                                    <label class="form-label fs-6 fw-bolder text-dark">Role Name</label>
                                    <input class="form-control form-control-solid" type="text" name="role_name" id="role_name"
                                        value="{{ $re->role_name }}" />
                                    <span id="role_name_error" style="color:red"></span>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label fw-bolder text-dark fs-6">Permissions:</label>
                                    <div class="d-flex flex-wrap gap-4">
                                        <div class="form-check form-check-custom form-check-solid">
                                            @if (in_array('View', $permissions))
                                                <input class="form-check-input" type="checkbox" value="View" id="view"
                                                    name="permissions[]" checked>
                                                <label class="form-check-label" for="view">View</label>
                                            @else
                                                <input class="form-check-input" type="checkbox" value="View" id="view"
                                                    name="permissions[]">
                                                <label class="form-check-label" for="view">View</label>
                                            @endif
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid">
                                            @if(in_array('Create', $permissions))
                                                <input class="form-check-input" type="checkbox" value="Create" id="create"
                                                    name="permissions[]" checked>
                                                <label class="form-check-label" for="create">Create</label>
                                            @else
                                                <input class="form-check-input" type="checkbox" value="Create" id="create"
                                                    name="permissions[]">
                                                <label class="form-check-label" for="create">Create</label>
                                            @endif
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid">
                                            @if(in_array('Delete', $permissions))
                                                <input class="form-check-input" type="checkbox" value="Delete" id="delete"
                                                    name="permissions[]" checked>
                                                <label class="form-check-label" for="delete">Delete</label>
                                            @else
                                                <input class="form-check-input" type="checkbox" value="Delete" id="delete"
                                                    name="permissions[]">
                                                <label class="form-check-label" for="delete">Delete</label>
                                            @endif
                                        </div>
                                        <div class="form-check form-check-custom form-check-solid">
                                            @if(in_array('Update', $permissions))
                                                <input class="form-check-input" type="checkbox" value="Update" id="update"
                                                    name="permissions[]" checked>
                                                <label class="form-check-label" for="update">Update</label>
                                            @else
                                                <input class="form-check-input" type="checkbox" value="Update" id="update"
                                                    name="permissions[]">
                                                <label class="form-check-label" for="update">Update</label>
                                            @endif
                                        </div>
                                    </div>
                                    <div id="permissions_error" style="color:red"></div>
                                </div>

                                <div class="d-flex justify-content-between gap-2">
                                    <button type="submit" class="btn btn-primary w-50">
                                        Update
                                    </button>
                                    <a href="{{ route('role.index') }}" class="btn btn-danger w-50">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    

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

    // permission validations
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
@endsection