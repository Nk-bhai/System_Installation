@extends('master')

@section('title', 'User Table Page')

@section('contents')
    <div class="container-fluid py-1">
        <!-- Welcome Message -->
        @foreach ($user_name as $name)
            <h1 class="text-center mb-5">Welcome Mr. {{ $name->name }}</h1>
        @endforeach

        <!-- Create User Button -->
        @if (in_array('Create', $permissions))
            <div class="d-flex justify-content-end mb-5">
                <button type="button" class="btn btn-primary" id="addUserButton" data-bs-toggle="modal"
                    data-bs-target="#addUserModal">Create User</button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Users List</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3"
                                id="userTable">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-150px">Name</th>
                                        <th class="min-w-200px">Email</th>
                                        <th class="min-w-150px">Role</th>
                                        <th class="min-w-150px">Last Logout</th>
                                        <th class="min-w-150px text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    @if (in_array('Create', $permissions))
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
                                    <span style="color:red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-5">
                                <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                                <input class="form-control form-control-solid" type="email" name="email" id="email" />
                                <span id="email_error" style="color:red"></span>
                                {{-- @error('email')
                                    <span style="color:red">{{ $message }}</span>
                                @enderror --}}
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
                                {{-- @error('password')
                                    <span style="color:red">{{ $message }}</span>
                                @enderror --}}
                            </div>
                            <div class="mb-5">
                                <label class="form-label fw-bolder text-dark fs-5">Assign Role</label>
                                <select name="role" id="role" class="form-select form-select-solid"
                                    data-placeholder="Select a role">
                                    <option value="assign">--Assign Role--</option>
                                    @foreach ($allrole as $r)
                                        <option value="{{ $r->id }}">{{ $r->role_name }}</option>
                                    @endforeach
                                </select>
                                <span id="role_error" style="color:red"></span>
                                {{-- @error('role')
                                    <span style="color:red">{{ $message }}</span>
                                @enderror --}}
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary w-100" id="submitUserForm">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
                        <form action="{{ route('admin_update') }}" method="post" id="user_update">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="edit_id">
                            <div class="mb-5">
                                <label class="form-label fs-6 fw-bolder text-dark">Name</label>
                                <input class="form-control form-control-solid" type="text" name="name" id="edit_name" />
                                <span id="edit_name_error" style="color:red"></span>
                                @error('name')
                                    <span style="color:red">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-5">
                                <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                                <input class="form-control form-control-solid" type="email" name="email" id="edit_email" />
                                <span id="edit_email_error" style="color:red"></span>
                                {{-- @error('email')
                                    <span style="color:red">{{ $message }}</span>
                                @enderror --}}
                            </div>
                            <div class="mb-5">
                                <label class="form-label fw-bolder text-dark fs-6">Assign Role</label>
                                <select name="role" id="edit_role" class="form-select form-select-solid"
                                    data-placeholder="Select a role">
                                    <option value="assign">--Assign Role--</option>
                                    @foreach ($allrole as $r)
                                        <option value="{{ $r->id }}">{{ $r->role_name }}</option>
                                    @endforeach
                                </select>
                                <span id="edit_role_error" style="color:red"></span>
                                {{-- @error('role')
                                    <span style="color:red">{{ $message }}</span>
                                @enderror --}}
                            </div>
                            <div>
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
            // Initialize DataTable with Metronic styling
            $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('usertables.data') }}',
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'role_name', name: 'role_name' },
                    { data: 'last_logout_at', name: 'last_logout_at' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, visible: {{ in_array('Update', $permissions) || in_array('Delete', $permissions) ? 'true' : 'false' }} }
                ],
                dom: '<"d-flex justify-content-between align-items-center mb-3"<"fw-semibold fs-3">f>t<"d-flex justify-content-between align-items-center mt-4"lip>',
                language: {
                    search: '',
                    searchPlaceholder: 'Search users...',
                    lengthMenu: '_MENU_ entries per page',
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
                },
                pageLength: 10,
                lengthMenu: [10, 20, 50],
                order: [[0, 'asc']],
                responsive: true,
                autoWidth: false,
                drawCallback: function () {
                    KTApp.init();
                }
            });

            // Add User Validations
            $("#name").on('input', ValidateName);
            $("#password").on('input', ValidatePassword);
            $("#role").on('change', ValidateRole);

            $("#user_role_assign").on("submit", function (e) {
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

            $("#user_update").on("submit", function (e) {
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
                $('#edit_id').val(id);
                $('#edit_name').val(name);
                $('#edit_email').val(email);
                $('#edit_role').val(role);

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

            // Populate Delete Modal
            $(document).on('click', '.deleteUserButton', function () {
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
                $('#submitUserForm').prop('disabled', false).text('Submit');
                $('.editUserButton').blur();
            });

            // Add User Validation Functions
            function ValidateName() {
                let name = $("#name").val().trim();
                let nameError = $("#name_error");

                nameError.text("");

                if (name === "") {
                    nameError.text("Name cannot be empty");
                    return false;
                } else if (/^[ ]{1,100}$/.test(name)) {
                    nameError.text("Name cannot contain spaces only");
                    return false;
                } else if (!/^[A-Za-z ]{1,100}$/.test(name)) {
                    nameError.text("Name should contain characters and spaces only");
                    return false;
                } else {
                    nameError.text("");
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
                let passwordError = $("#password_error");

                passwordError.text("");

                if (password === "") {
                    passwordError.text("Password cannot be empty");
                    return false;
                } else if (!/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8}$/.test(password)) {
                    passwordError.text("Password must contain at least 1 uppercase, 1 lowercase, 1 digit, 1 special character and must be 8 characters");
                    return false;
                } else {
                    passwordError.text("");
                    return true;
                }
            }

            function ValidateRole() {
                let role = $("#role").val();
                let roleError = $("#role_error");

                roleError.text("");

                if (role === "assign") {
                    roleError.text("Please select a role");
                    return false;
                } else {
                    roleError.text("");
                    return true;
                }
            }

            // Edit User Validation Functions
            function ValidateEditName() {
                let name = $("#edit_name").val().trim();
                let nameError = $("#edit_name_error");

                nameError.text("");

                if (name === "") {
                    nameError.text("Name cannot be empty");
                    return false;
                } else if (/^[ ]{1,100}$/.test(name)) {
                    nameError.text("Name cannot contain spaces only");
                    return false;
                } else if (!/^[A-Za-z ]{1,100}$/.test(name)) {
                    nameError.text("Name should contain characters and spaces only");
                    return false;
                } else {
                    nameError.text("");
                    return true;
                }
            }

            function ValidateEditEmail() {
                let email = $("#edit_email").val().trim();
                let userId = $("#edit_id").val();
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
                let roleError = "#edit_role_error";

                $(roleError).text("");

                if (role === "assign") {
                    $(roleError).text("Please select a role");
                    return false;
                } else {
                    $(roleError).text("");
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
    </script>
@endsection

@php
    $pageTitle = 'User Table';
@endphp