{{-- <form action="{{ route('role.store') }}" method="post" id="role_assign_form">
    @csrf
    <label>Role Name</label>
    <input type="text" name="role" id="role_name">
    <span id="role_name_error" style="color:red"></span>

    <br><br>

    <label>Permissions : </label>
    <input type="checkbox" value="View" id="view" name="permissions[]" checked>View
    <input type="checkbox" value="Create" name="permissions[]" id="create">Create
    <input type="checkbox" value="Delete" name="permissions[]" id="delete">Delete
    <input type="checkbox" value="Update" name="permissions[]" id="update">Update
    <div id="permissions_error" style="color:red"></div>
    <br><br>

    <input type="submit" value="submit">
</form> --}}


{{--
<table border="1">
    @forelse ($roleData as $rd)
    <tr>
        <th>Role Name</th>
        <th>Permissions</th>
        <th>Actions</th>
    </tr>
    @break
    @empty
    @endforelse

    @forelse ($roleData as $rd)
    <tr>
        <td>{{$rd->role_name}}</td>
        <td>{{$rd->permissions}}</td>
        <td>
            <form action="{{ route('role.destroy', $rd->id) }}" method="post">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete">
            </form>
            <form action="{{ route('role.edit', $rd->id) }}" method="get">
                <input type="submit" value="Edit">
            </form>
        </td>
    </tr>
    @empty
    <p>No Role Assigned Yet.</p>
    @endforelse
</table> --}}




@extends('master')
@section('contents')

    <div class="container-fluid py-10">

        {{-- <div class="d-flex justify-content-end mb-5">
            <a href="/dashboard" class="btn btn-secondary">Back to Dashboard</a>
        </div> --}}
        <div class="row">
            <!-- Form Section -->
            <div class="col-lg-4 mb-10">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Add Role</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('role.store') }}" method="post" id="role_assign_form">
                            @csrf

                            <div class="mb-5">
                                <label class="form-label fs-6 fw-bolder text-dark">Role Name</label>
                                <input class="form-control form-control-solid" type="text" name="role" id="role_name" />
                                <span id="role_name_error" style="color:red">
                                    @error('role')
                                    {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="mb-5">
                                <label class="form-label fw-bolder text-dark fs-6">Permissions:</label>
                                <div class="d-flex flex-wrap gap-4">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="View" id="view"
                                            name="permissions[]" checked>
                                        <label class="form-check-label" for="view">View</label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="Create" id="create"
                                            name="permissions[]">
                                        <label class="form-check-label" for="create">Create</label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="Delete" id="delete"
                                            name="permissions[]">
                                        <label class="form-check-label" for="delete">Delete</label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" value="Update" id="update"
                                            name="permissions[]">
                                        <label class="form-check-label" for="update">Update</label>
                                    </div>
                                </div>
                                <div id="permissions_error" style="color:red"></div>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary w-100">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Role List</h3>
                    </div>
                    <div class="card-body">
                        @if ($roleData->isEmpty())
                            <div class="alert alert-warning">No Role Assigned Yet.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-row-black-100 align-middle gs-0 gy-3">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-150px">Role Name</th>
                                            <th class="min-w-200px">Permissions</th>
                                            <th class="min-w-150px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roleData as $rd)
                                            <tr>
                                                <td>
                                                    <span class="text-dark fw-bold d-block fs-6">{{ $rd->role_name }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-dark fw-bold d-block">{{ $rd->permissions }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <!-- Edit Button -->
                                                        <a href="{{ route('role.edit', $rd->id) }}"
                                                            class="btn btn-sm btn-light-primary">
                                                            Edit
                                                        </a>

                                                        <!-- Delete Button -->
                                                        <form action="{{ route('role.destroy', $rd->id) }}" method="post"
                                                            onsubmit="return confirm('Are you sure you want to delete this role?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-light-danger">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
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