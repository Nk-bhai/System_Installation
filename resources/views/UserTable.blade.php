@extends('master_old')

@section('contents')
    <div class="container-fluid">
        <!-- Logout Button -->
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
        </div>

        <!-- Welcome Message -->
        @foreach ($user_name as $name)
            <h1 class="text-center mb-5">Welcome Mr. {{ $name->name }}</h1>
        @endforeach

        <!-- Create User Button -->
        @if (in_array('Create', $permissions))
            <div class="d-flex justify-content-end mb-5">
                <a href="{{ route('user.index') }}" class="btn btn-primary">Create User</a>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">User List</h3>
                        <div class="search-bar">
                            <input type="text" id="searchInput" class="form-control form-control-solid"
                                placeholder="Search by name, email, or role..." style="width: 300px;">
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($data->isEmpty())
                            <div class="alert alert-warning">No User Data</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-row-black-100 align-middle gs-0 gy-3"
                                    id="userTable">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-150px">Name</th>
                                            <th class="min-w-200px">Email</th>
                                            <th class="min-w-150px">Role</th>
                                            @if(!in_array('Delete', $permissions) && !in_array('Update', $permissions))
                                                <th class="min-w-150px text-center" style="display: none">Actions</th>
                                            @else
                                                <th class="min-w-150px text-center">Actions</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $dt)
                                            <tr>
                                                <td>
                                                    <span class="text-dark fw-bold d-block fs-6">{{ $dt->name }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-dark fw-bold d-block">{{ $dt->email }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-dark fw-bold d-block">{{ $dt->role }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-3">
                                                        @if (in_array('Update', $permissions))
                                                            <button type="button" class="btn btn-sm btn-light-primary editUserButton"
                                                                data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                                data-id="{{ $dt->id }}" data-name="{{ $dt->name }}"
                                                                data-email="{{ $dt->email }}" data-role="{{ $dt->role }}"
                                                                data-url="{{ route('admin_update') }}">Edit</button>
                                                        @endif
                                                        @if (in_array('Delete', $permissions))
                                                            <button type="button" class="btn btn-sm btn-light-danger deleteUserButton"
                                                                data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                                                                data-id="{{ $dt->id }}" data-name="{{ $dt->name }}"
                                                                data-url="{{ route('user.destroy', $dt->id) }}">Delete</button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $data->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    @if (in_array('Update', $permissions))
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" id="user_update">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="edit_id">
                            <div class="mb-5">
                                <label class="form-label fs-6 fw-bolder text-dark">Name</label>
                                <input class="form-control form-control-solid" type="text" name="name" id="edit_name" />
                                <span id="edit_name_error" style="color:red"></span>
                                @error('name')
                                    {{ $message }}
                                @enderror
                            </div>
                            <div class="mb-5">
                                <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                                <input class="form-control form-control-solid" type="email" name="email" id="edit_email" />
                                <span id="edit_email_error" style="color:red"></span>

                            </div>
                            <div class="mb-5">
                                @foreach ($data as $dt)
                                        <label class="form-label fw-bolder text-dark fs-6">Assign Role</label>
                                        <select name="role" id="edit_role" class="form-select form-select-solid"
                                            data-placeholder="Select a role">
                                            <option value="assign">--Assign Role--</option>
                                            @foreach ($allrole as $r)
                                                <option value="{{ $r->role_name }}" {{ $r->role_name == $dt->role ? "selected" : ""}}>
                                                    {{ $r->role_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span id="edit_role_error" style="color:red">

                                        </span>
                                    </div>
                                    <div>
                                @endforeach
                                <button type="submit" class="btn btn-primary w-100">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete User Modal -->
    @if (in_array('Delete', $permissions))
        <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete user <strong id="delete_user_name"></strong>?</p>
                        <form action="" method="post" id="user_delete">
                            @csrf
                            @method('DELETE')
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        $(document).ready(function () {
            // Search functionality
            $("#searchInput").on("keyup", function () {
                let value = $(this).val().toLowerCase();
                $("#userTable tbody tr").filter(function () {
                    $(this).toggle(
                        $(this).find("td:eq(0)").text().toLowerCase().indexOf(value) > -1 || // Name
                        $(this).find("td:eq(1)").text().toLowerCase().indexOf(value) > -1 || // Email
                        $(this).find("td:eq(2)").text().toLowerCase().indexOf(value) > -1   // Role
                    );
                });
            });

            // Edit User Validations
            $("#edit_name").on('input', ValidateEditName);
            $("#edit_email").on('input', ValidateEditEmail);
            $("#edit_role").on('change', ValidateEditRole);

            $("#user_update").submit(function (e) {
                let name = ValidateEditName();
                let email = ValidateEditEmail();
                let role = ValidateEditRole();
                if (!name || !email || !role) {
                    e.preventDefault();
                }
            });

            // Populate Edit Modal
            $('.editUserButton').on('click', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let email = $(this).data('email');
                let role = $(this).data('role');
                let url = $(this).data('url');

                $('#editUserModal').find('form').attr('action', url);
                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_email').val(email);
                $('#edit_role').val(role);
            });

            // Populate Delete Modal
            $('.deleteUserButton').on('click', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let url = $(this).data('url');

                $('#deleteUserModal').find('form').attr('action', url);
                $('#delete_user_name').text(name);
            });

            // Validation Functions
            function ValidateEditName() {
                let name = $("#edit_name").val();
                if (name == "") {
                    $("#edit_name_error").html("Name cannot be empty");
                    return false;
                } else if (/^[ ]{1,100}$/.test(name)) {
                    $("#edit_name_error").html("Name cannot contain spaces only");
                    return false;
                } else if (!/^[A-Za-z ]{1,100}$/.test(name)) {
                    $("#edit_name_error").html("Name should contain characters and spaces only");
                    return false;
                } else {
                    $("#edit_name_error").html("");
                    return true;
                }
            }

            function ValidateEditEmail() {
                let email = $("#edit_email").val();
                if (email == "") {
                    $("#edit_email_error").html("Email cannot be empty");
                    return false;
                } else if (!/^[A-Za-z0-9.]+@[A-Za-z]{2,7}\.[A-Za-z]{2,3}$/.test(email)) {
                    $("#edit_email_error").html("Email must be valid");
                    return false;
                } else {
                    $("#edit_email_error").html("");
                    return true;
                }
            }

            function ValidateEditRole() {
                let role = $("#edit_role").val();
                if (role === "assign") {
                    $("#edit_role_error").html("Please select a role");
                    return false;
                } else {
                    $("#edit_role_error").html("");
                    return true;
                }
            }
        });
    </script>
@endsection