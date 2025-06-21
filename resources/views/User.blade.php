@extends('master')

@section('contents')
@section('title', 'User Management')
    <div class="container-fluid">
        <div class="d-flex justify-content-end mb-5 gap-5 mt-n10">
            {{-- <a href="/dashboard" class="btn btn-secondary">Back to Dashboard</a> --}}
            <button type="button" class="btn btn-primary" id="addUserButton" data-bs-toggle="modal"
                data-bs-target="#addUserModal">Add</button>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Users List</h3>
                        <div class="search-bar">
                            <input type="text" id="searchInput" class="form-control form-control-solid"
                                placeholder="Search by name, email, or role..." style="width: 300px;">
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($data->isEmpty())
                            <div class="alert alert-warning">No Role assigned to user Yet.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-row-black-100 align-middle gs-0 gy-3"
                                    id="userTable">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-150px">Name</th>
                                            <th class="min-w-200px">Email</th>
                                            <th class="min-w-150px">Role</th>
                                            <th class="min-w-150px">Last Logout</th>
                                            <th class="min-w-150px">Actions</th>
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
                                                <td>
                                                    {{-- <span class="text-dark fw-bold d-block">{{ $dt->last_logout_at ?
                                                        \Carbon\Carbon::parse($dt->last_logout_at)->diffForHumans() : 'Never'
                                                        }}</span> --}}
                                                    {{-- <span class="text-dark fw-bold d-block">{{ $dt->last_logout_at ?
                                                        $dt->last_logout_at : '-' }}</span> --}}
                                                    <span class="text-dark fw-bold d-block">
                                                        {{ $dt->last_logout_at ? \Carbon\Carbon::parse($dt->last_logout_at)->timezone('Asia/Kolkata')->format('d-m-Y h:i A') : '-' }}
                                                    </span>

                                                </td>
                                                <td>
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <button type="button" class="btn btn-sm btn-light-primary editUserButton"
                                                            data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                            data-id="{{ $dt->id }}" data-name="{{ $dt->name }}"
                                                            data-email="{{ $dt->email }}" data-role="{{ $dt->role }}"
                                                            data-url="{{ route('user.update', $dt->id) }}">Edit</button>
                                                        
                                                        <button type="button" class="btn btn-sm btn-light-danger deleteUserButton"
                                                            data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                                                            data-id="{{ $dt->id }}" data-name="{{ $dt->name }}"
                                                            data-url="{{ route('user.destroy', $dt->id) }}">Delete</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>Total Users: {{ $userCount ?? '0' }}</div>
                                    <div>{{ $data->links() }}</div>
                                </div>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Assign Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('user.store') }}" method="post" id="user_role_assign">
                        @csrf
                        <div class="mb-5">
                            <label class="form-label fs-6 fw-bolder text-dark">Name</label>
                            <input class="form-control form-control-solid" type="text" name="name" id="name" />
                            <span id="name_error" style="color:red"></span>
                            @error('name')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                            <input class="form-control form-control-solid" type="email" name="email" id="email" />
                            <span id="email_error" style="color:red"></span>
                            @error('email')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label fs-6 fw-bolder text-dark">Password</label>
                            <div class="password-wrapper">
                                <input class="form-control form-control-solid" type="password" name="password"
                                    id="password" />
                                <span class="password-toggle-icon" onclick="Password_Show_hide()">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            <div id="password_error" style="color:red"></div>
                            @error('password')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label fw-bolder text-dark fs-5">Assign Role</label>
                            <select name="role" id="role" class="form-select form-select-solid"
                                data-placeholder="Select a role">
                                <option value="assign">--Assign Role--</option>
                                @foreach ($role as $r)
                                    <option value="{{ $r->role_name }}">{{ $r->role_name }}</option>
                                @endforeach
                            </select>
                            <span id="role_error" style="color: red">
                                @error('role')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
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
                            @error('email')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label fw-bolder text-dark fs-6">Assign Role</label>
                            <select name="role" id="edit_role" class="form-select form-select-solid"
                                data-placeholder="Select a role">
                                <option value="assign">--Assign Role--</option>
                                @foreach ($role as $r)
                                    <option value="{{ $r->role_name }}">{{ $r->role_name }}</option>
                                @endforeach
                            </select>
                            <span id="edit_role_error" style="color:red">
                                @error('role')
                                    {{ $message }}
                                @enderror
                            </span>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary w-100">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete User Modal -->
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
    <script>
        // password show hide
        function Password_Show_hide() {
            var x = document.getElementById("password");
            let icon = document.querySelector(".password-toggle-icon i");
            if (x.type === "password") {
                x.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                x.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
        // $(document).ready(function () {
        //     // Search functionality
        //     $("#searchInput").on("keyup", function () {
        //         let value = $(this).val().toLowerCase();
        //         $("#userTable tbody tr").filter(function () {
        //             $(this).toggle(
        //                 $(this).find("td:eq(0)").text().toLowerCase().indexOf(value) > -1 || // Name
        //                 $(this).find("td:eq(1)").text().toLowerCase().indexOf(value) > -1 || // Email
        //                 $(this).find("td:eq(2)").text().toLowerCase().indexOf(value) > -1   // Role
        //             );
        //         });
        //     });
        $(document).ready(function () {
            $("#searchInput").on("keyup", function () {
                let value = $(this).val().toLowerCase();
                let hasVisibleRow = false;

                $("#userTable tbody tr").each(function () {
                    // Skip the "No search result" row from being filtered
                    if ($(this).attr("id") === "noResultsRow") {
                        return;
                    }

                    let match =
                        $(this).find("td:eq(0)").text().toLowerCase().indexOf(value) > -1 || // Name
                        $(this).find("td:eq(1)").text().toLowerCase().indexOf(value) > -1 || // Email
                        $(this).find("td:eq(2)").text().toLowerCase().indexOf(value) > -1;   // Role

                    $(this).toggle(match);
                    if (match) hasVisibleRow = true;
                });

                // Show/hide "No search result found"
                if (!hasVisibleRow) {
                    if ($("#noResultsRow").length === 0) {
                        $("#userTable tbody").append(`
                            <tr id="noResultsRow">
                                <td colspan="5" class="text-center text-muted">No search result found</td>
                            </tr>
                        `);
                    }
                } else {
                    $("#noResultsRow").remove();
                }
            });





            // Add User Validations
            $("#name").on('input', ValidateName);
            $("#email").on('input', ValidateEmail);
            $("#password").on('input', ValidatePassword);
            $("#role").on('change', ValidateRole);

            $("#user_role_assign").submit(function (e) {
                let name = ValidateName();
                let email = ValidateEmail();
                let password = ValidatePassword();
                let role = ValidateRole();
                if (!name || !email || !password || !role) {
                    e.preventDefault();
                }
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
                $('#edit_name').val(name);
                $('#edit_email').val(email);
                $('#edit_role').val(role);

                // Ensure modal opens
                try {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        let modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                        modal.show();
                    } else {
                        console.error("Bootstrap JS is not loaded. Using fallback.");
                        $("#editUserModal").addClass("show").css("display", "block");
                        $("body").addClass("modal-open").append('<div class="modal-backdrop fade show"></div>');
                    }
                } catch (e) {
                    console.error("Error opening edit modal:", e);
                }
            });

            $('.deleteUserButton').on('click', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let url = $(this).data('url');

                $('#deleteUserModal').find('form').attr('action', url);
                $('#delete_user_name').text(name);

                try {
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        let modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
                        modal.show();
                    } else {
                        console.error("Bootstrap JS is not loaded. Using fallback.");
                        $("#deleteUserModal").addClass("show").css("display", "block");
                        $("body").addClass("modal-open").append('<div class="modal-backdrop fade show"></div>');
                    }
                } catch (e) {
                    console.error("Error opening delete modal:", e);
                }
            });

            // Fallback to manually trigger add modal if Bootstrap JS is not loaded
            $("#addUserButton").on("click", function () {
                try {
                    if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                        console.error("Bootstrap JS is not loaded. Check your master layout.");
                        $("#addUserModal").addClass("show").css("display", "block");
                        $("body").addClass("modal-open").append('<div class="modal-backdrop fade show"></div>');
                    }
                } catch (e) {
                    console.error("Error triggering modal:", e);
                }
            });

            // Handle manual modal close
            $(".btn-close, [data-bs-dismiss='modal']").on("click", function () {
                $("#addUserModal").removeClass("show").css("display", "none");
                $("#editUserModal").removeClass("show").css("display", "none");
                $("#deleteUserModal").removeClass("show").css("display", "none");
                $("body").removeClass("modal-open");
                $(".modal-backdrop").remove();
            });

            // Add User Validation Functions
            function ValidateName() {
                let name = $("#name").val();
                if (name == "") {
                    $("#name_error").html("Name cannot be empty");
                    return false;
                } else if (/^[ ]{1,100}$/.test(name)) {
                    $("#name_error").html("Name cannot contain spaces only");
                    return false;
                } else if (!/^[A-Za-z ]{1,100}$/.test(name)) {
                    $("#name_error").html("Name should contain characters and spaces only");
                    return false;
                } else {
                    $("#name_error").html("");
                    return true;
                }
            }

            function ValidateEmail() {
                let email = $("#email").val();
                if (email == "") {
                    $("#email_error").html("Email cannot be empty");
                    return false;
                } else if (!/^[A-Za-z0-9.]+@[A-Za-z]{2,7}\.[A-Za-z]{2,3}$/.test(email)) {
                    $("#email_error").html("Email must be valid");
                    return false;
                } else {
                    $("#email_error").html("");
                    return true;
                }
            }

            function ValidatePassword() {
                let password = $("#password").val();
                if (password == "") {
                    $("#password_error").html("Password cannot be empty");
                    return false;
                } else if (!/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8}$/.test(password)) {
                    $("#password_error").html("Password must contain at least 1 uppercase, 1 lowercase, 1 digit, 1 special character and must be 8 characters");
                    return false;
                } else {
                    $("#password_error").html("");
                    return true;
                }
            }

            function ValidateRole() {
                let role = $("#role").val();
                if (role === "assign") {
                    $("#role_error").html("Please select a role");
                    return false;
                } else {
                    $("#role_error").html("");
                    return true;
                }
            }

            // Edit User Validation Functions
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

@php
    $pageTitle = 'User Management';
@endphp