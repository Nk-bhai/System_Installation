@extends('master')

@section('contents')

@section('title', 'Profile Details')
    <style>
        .password-wrapper input {
            background-color: #f5f8fa !important;
            color: #181c32 !important;
            font-weight: 500 !important;
            transition: none !important;
        }
    </style>

    <div class="container-fluid">
        <div class="card">
            <div class="card-header" style="border-bottom: 1px solid #ebedf2;">
                <h3 class="card-title">Profile</h3>
            </div>
            <div class="card-body">
                <form id="kt_account_profile_details_form" class="form" method="POST" action="{{ route('profile.update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="table-responsive">
                        <table class="table table-row-black-100 align-middle gs-0 gy-3">
                            {{-- <table border="2" class="table table-row-black-100"> --}}
                                <tbody>
                                    <tr>
                                        <td class="min-w-200px">
                                            <span class="text-dark fw-bold d-block fs-6">Avatar</span>
                                        </td>
                                        <td class="min-w-200px">
                                            <div class="image-input image-input-outline" data-kt-image-input="true"
                                                style="background-image: url({{ asset('assets/media/avatars/blank.png') }})">

                                                <div class="image-input-wrapper w-125px h-125px"
                                                    style="background-image: url({{ asset(session('profile_logo') ? 'storage/avatars/' . session('profile_logo') : 'dist/assets/media/avatars/blank.png') }})">
                                                </div>
                                                <label
                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                    data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                    title="Change avatar">
                                                    <i class="bi bi-pencil-fill fs-7"></i>
                                                    <input type="file" name="avatar" accept=".png, .jpg, .jpeg"
                                                        id="avatar" />
                                                    <input type="hidden" name="avatar_remove" id="avatar_remove" />
                                                </label>
                                                <span
                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                    title="Cancel avatar">
                                                    <i class="bi bi-x fs-2"></i>
                                                </span>
                                                <span
                                                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                    data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                    title="Remove avatar">
                                                    <i class="bi bi-x fs-2"></i>
                                                </span>
                                            </div>
                                            <div id="avatar_error" class="text-danger fs-7 mt-1" style="max-width: 300px;">
                                            </div>

                                            <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-200px">
                                            <span class="text-dark fw-bold d-block fs-6">Email</span>
                                        </td>
                                        <td class="min-w-200px">
                                            <span class="text-dark fw-bold d-block">
                                                {{ session('login_email') ?? session('superadmin_email') ?? 'No email available' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="min-w-200px align-top">
                                            <span class="text-dark fw-bold d-block fs-6">Change Password</span>
                                        </td>
                                        <td class="min-w-200px">
                                            <div class="position-relative" style="width: 300px;">
                                                
                                                <input class="form-control form-control-sm form-control-solid pe-10" 
                                                {{-- <input class="text-dark fw-bold d-block fs-6 pe-10"  --}}
                                                    type="password" name="password" id="password" autocomplete="off"
                                                    style="width: 100%;" />
                                                <span class="position-absolute top-50 end-0 translate-middle-y me-3"
                                                    onclick="Password_Show_hide()" style="cursor: pointer;">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>

                                            <!-- Error message wrapped and constrained -->
                                            <div id="password_error" class="text-danger fs-7 mt-1"
                                                style="max-width: 300px; word-wrap: break-word; white-space: normal;">
                                                <!-- Sample long error -->
                                                    {{-- Password must be at least 8 characters and include one uppercase letter, one
                                                    lowercase letter, and one symbol. --}}
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="min-w-150px"></td>
                                        <td class="min-w-200px">
                                            <div class="d-flex justify-content-end">
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @php
        $pageTitle = 'Profile';
    @endphp

    <script>
        document.querySelector('[data-kt-image-input-action="remove"]').addEventListener('click', function () {
            document.getElementById('avatar_remove').value = 1; // Mark for removal
        });

        function Password_Show_hide() {
            let x = document.getElementById("password");
            let icon = document.querySelector(".password-toggle-icon i");
            if (x.type === "password") {
                x.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                x.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }

        $(document).ready(function () {
            $('#password').removeAttr('data-kt-password-meter');
            // Real-time password check only when user types
            $("#password").on('input', ValidatePassword);
            $("#avatar").on('input', validate_upload_file);


            // On form submit
            $("#kt_account_profile_details_form").submit(function (e) {
                const password = $("#password").val().trim();
                let avatar = validate_upload_file();

                if (password !== "") {
                    if (!ValidatePassword()) {
                        e.preventDefault(); // prevent if invalid
                    } else {
                        passwordUpdated = true; // mark password was changed
                    }
                }
                if (!avatar) {
                    e.preventDefault();
                }
            });

            // Validation function
            function ValidatePassword() {
                const password = $("#password").val().trim();
                const errorDiv = $("#password_error");

                if (password === "") {
                    errorDiv.text(""); // no error if password is blank (not changing)
                    return true;
                }

                const isValid = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8}$/.test(password);

                if (!isValid) {
                    errorDiv.text("Password must be exactly 8 characters and include upper, lower, digit, and special character.");
                    return false;
                } else {
                    errorDiv.text("");
                    return true;
                }
            }

            // validate avatar file type
            function validate_upload_file() {
                let avatar = $("#avatar").val();
                const fileInput = $('#avatar')[0].files[0];

                const allowedTypes = ['image/jpeg', 'image/png'];

                if (fileInput && !allowedTypes.includes(fileInput.type)) {
                    $("#avatar_error").html("Invalid file type please upload a JPEG, PNG");
                    return false;
                }
                else {
                    $("#avatar_error").html("");
                    return true;

                }
            }
        });
    </script>


@endsection