@extends('master')

@section('contents')
@section('title', 'User Management')

    <style>
        .sort-column {
            color: inherit;
            text-decoration: none;
        }

        .sort-column:hover {
            text-decoration: underline;
        }

        .sort-icon.asc::after {
            content: ' ↑';
        }

        .sort-icon.desc::after {
            content: ' ↓';
        }
    </style>

    <div class="container-fluid py-1">
        <div class="d-flex justify-content-end mb-5 gap-5">
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
                            <div class="alert alert-warning">No User Created Yet.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-row-black-100 align-middle gs-0 gy-3"
                                    id="userTable">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-150px">
                                                <a href="#" class="sort-column" data-column="name">Name <span class="sort-icon"
                                                        data-column="name"></span></a>
                                            </th>
                                            <th class="min-w-200px">
                                                <a href="#" class="sort-column" data-column="email">Email <span
                                                        class="sort-icon" data-column="email"></span></a>
                                            </th>
                                            <th class="min-w-150px">
                                                <a href="#" class="sort-column" data-column="role_name">Role <span
                                                        class="sort-icon" data-column="role_name"></span></a>
                                            </th>
                                            <th class="min-w-150px">
                                                <a href="#" class="sort-column" data-column="created_by">
                                                    Created By <span class="sort-icon" data-column="created_by"></span>
                                                </a>
                                            </th>
                                            <th class="min-w-150px">Last Logout</th>
                                            <th class="min-w-150px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="userTableBody">
                                        @forelse ($data as $dt)
                                            <tr>
                                                <td><span class="text-dark fw-bold d-block fs-6">{{ $dt->name }}</span></td>
                                                <td><span class="text-dark fw-bold d-block">{{ $dt->email }}</span></td>
                                                <td><span
                                                        class="text-dark fw-bold d-block">{{ $dt->role->role_name ?? 'N/A' }}</span>
                                                </td>
                                                <td><span
                                                        class="text-dark fw-bold d-block">{{ $dt->created_by ?? 'Super Admin' }}</span>
                                                </td>
                                                <td><span class="text-dark fw-bold d-block">
                                                        {{ $dt->last_logout_at ? \Carbon\Carbon::parse($dt->last_logout_at)->timezone('Asia/Kolkata')->format('d-m-Y h:i A') : '-' }}
                                                    </span></td>
                                                <td>
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <button type="button" class="btn btn-sm btn-light-primary editUserButton"
                                                            data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                            data-id="{{ encrypt($dt->id) }}" data-name="{{ $dt->name }}"
                                                            data-email="{{ $dt->email }}" data-role="{{ $dt->role->id }}"
                                                            data-url="{{ route('user.update', encrypt($dt->id)) }}">Edit</button>
                                                        <button type="button" class="btn btn-sm btn-light-danger deleteUserButton"
                                                            data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                                                            data-id="{{ encrypt($dt->id) }}" data-name="{{ $dt->name }}"
                                                            data-url="{{ route('user.destroy', encrypt($dt->id)) }}">Delete</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">No Role assigned to user Yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div id="userCountText">Total Users: {{ $userCount ?? '0' }}</div>
                                    <div id="paginationLinks">{{ $data->links() }}</div>
                                    <form method="GET" action="{{ route('user.index') }}">
                                        <select name="per_page" class="form-select form-select-sm w-auto"
                                            onchange="this.form.submit()" id="perPageSelect">
                                            <option value="" disabled {{ !request('per_page') ? 'selected' : '' }}>Select per
                                                page</option>
                                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per page
                                            </option>
                                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 per page
                                            </option>
                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page
                                            </option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        @endif
                        @if (session('errorss'))
                            <div class="alert alert-danger">
                                {{ session('errorss') }}
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
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
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
                                    <option value="{{ $r->id }}">{{ $r->role_name }}</option>
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
                        <input type="hidden" name="user_id" id="edit_user_id" />
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
                                    <option value="{{ $r->id }}">{{ $r->role_name }}</option>
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
        $(document).ready(function () {
            $('#searchInput').on('keyup', function () {
                let query = $(this).val();
                fetchUsers(query);
            });

            $(document).on('click', '#paginationLinks a', function (e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let query = $('#searchInput').val();
                fetchUsers(query, page);
            });

            $(document).on('click', '.sort-column', function (e) {
                e.preventDefault();
                const column = $(this).data('column');

                if (sortColumn === column) {
                    sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    sortColumn = column;
                    sortDirection = 'asc';
                }

                $('.sort-icon').removeClass('asc desc');
                $(`.sort-icon[data-column="${column}"]`).addClass(sortDirection);

                const query = $('#searchInput').val();
                fetchUsers(query, 1);
            });

            $('.sort-icon[data-column="name"]').addClass('asc');

            // Add User Validations
            $("#name").on('input', ValidateName);
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
            $(document).on('click', '.editUserButton', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let email = $(this).data('email');
                let role = $(this).data('role');
                let url = $(this).data('url');

                $('#editUserModal').find('form').attr('action', url);
                $('#edit_name').val(name);
                $('#edit_email').val(email);
                $('#edit_role').val(role);
                $('#edit_user_id').val(id);

                let modal = new bootstrap.Modal(document.getElementById('editUserModal'));
                modal.show();
            });

            // Delete Modal Binding
            $(document).on('click', '.deleteUserButton', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let url = $(this).data('url');

                $('#deleteUserModal').find('form').attr('action', url);
                $('#delete_user_name').text(name);

                let modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
                modal.show();
            });

            // Fallback for add modal
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
                let name = $("#name").val().trim();
                if (name === "") {
                    $("#name_error").text("Name cannot be empty");
                    return false;
                } else if (/^[ ]{1,100}$/.test(name)) {
                    $("#name_error").text("Name cannot contain spaces only");
                    return false;
                } else if (!/^[A-Za-z ]{1,100}$/.test(name)) {
                    $("#name_error").text("Name should contain characters and spaces only");
                    return false;
                } else {
                    $("#name_error").text("");
                    return true;
                }
            }

            function ValidateEmail() {
                let email = $("#email").val().trim();
                let emailError = $("#email_error");

                emailError.text("");

                if (email === "") {
                    emailError.text("Email is required.");
                    return false;
                }

                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email)) {
                    emailError.text("Please enter a valid email.");
                    return false;
                }

                let isValid = false;

                $.ajax({
                    url: "{{ route('user.checkEmail') }}",
                    type: "POST",
                    data: {
                        email: email,
                        _token: "{{ csrf_token() }}"
                    },
                    async: false,
                    success: function (response) {
                        if (response.exists) {
                            emailError.text("This email is already taken.");
                            isValid = false;
                        } else {
                            emailError.text("");
                            isValid = true;
                        }
                    },
                    error: function () {
                        emailError.text("Server error. Please try again.");
                        isValid = false;
                    }
                });

                return isValid;
            }

            function ValidatePassword() {
                let password = $("#password").val();
                if (password === "") {
                    $("#password_error").text("Password cannot be empty");
                    return false;
                } else if (!/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8}$/.test(password)) {
                    $("#password_error").text("Password must contain at least 1 uppercase, 1 lowercase, 1 digit, 1 special character and must be 8 characters");
                    return false;
                } else {
                    $("#password_error").text("");
                    return true;
                }
            }

            function ValidateRole() {
                let role = $("#role").val();
                if (role === "assign") {
                    $("#role_error").text("Please select a role");
                    return false;
                } else {
                    $("#role_error").text("");
                    return true;
                }
            }

            // Edit User Validation Functions
            function ValidateEditName() {
                let name = $("#edit_name").val().trim();
                if (name === "") {
                    $("#edit_name_error").text("Name cannot be empty");
                    return false;
                } else if (/^[ ]{1,100}$/.test(name)) {
                    $("#edit_name_error").text("Name cannot contain spaces only");
                    return false;
                } else if (!/^[A-Za-z ]{1,100}$/.test(name)) {
                    $("#edit_name_error").text("Name should contain characters and spaces only");
                    return false;
                } else {
                    $("#edit_name_error").text("");
                    return true;
                }
            }

            function ValidateEditEmail() {
                let email = $("#edit_email").val().trim();
                let userId = $("#edit_user_id").val();
                let emailError = $("#edit_email_error");

                emailError.text("");

                if (email === "") {
                    emailError.text("Email is required.");
                    return false;
                }

                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(email)) {
                    emailError.text("Invalid email format.");
                    return false;
                }

                let isValid = false;

                $.ajax({
                    url: "{{ route('user.checkEmail') }}",
                    type: "POST",
                    data: {
                        email: email,
                        id: userId,
                        _token: "{{ csrf_token() }}"
                    },
                    async: false,
                    success: function (response) {
                        if (response.exists) {
                            emailError.text("This email is already taken.");
                            isValid = false;
                        } else {
                            emailError.text("");
                            isValid = true;
                        }
                    },
                    error: function () {
                        emailError.text("Server error. Please try again.");
                        isValid = false;
                    }
                });

                return isValid;
            }

            function ValidateEditRole() {
                let role = $("#edit_role").val();
                if (role === "assign") {
                    $("#edit_role_error").text("Please select a role");
                    return false;
                } else {
                    $("#edit_role_error").text("");
                    return true;
                }
            }
        });

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

        let sortColumn = 'name';
        let sortDirection = 'asc';
        function fetchUsers(query = '', page = 1) {
            $.ajax({
                url: "{{ route('user.search') }}",
                type: "GET",
                data: {
                    query: query,
                    page: page,
                    sort_column: sortColumn,
                    sort_direction: sortDirection
                },
                success: function (response) {
                    $('#userTableBody').html(response.html);
                    $('#paginationLinks').html(response.pagination);
                    $('#userCountText').text('Total Users: ' + response.count);
                },
                error: function () {
                    alert('Error fetching data.');
                }
            });
        }
    </script>
@endsection

@php
    $pageTitle = 'Users';
@endphp