@extends('master')

@section('contents')

@section('title', 'Profile Details')
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
                            <tbody>
                                <tr>
                                    <td class="min-w-150px">
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
                                                <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
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
                                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="min-w-150px">
                                        <span class="text-dark fw-bold d-block fs-6">Email</span>
                                    </td>
                                    <td class="min-w-200px">
                                        {{-- <span class="text-dark fw-bold d-block">{{ session('superadmin_email')
                                            }}</span> --}}
                                        <span class="text-dark fw-bold d-block">
                                            {{ session('login_email') ?? session('superadmin_email') ?? 'No email available' }}
                                        </span>
                                    </td>
                                </tr>
                                {{-- @dd($keyData['password']); --}}
                                <tr>
                                    <td class="min-w-150px">
                                        <span class="text-dark fw-bold d-block fs-6">Change Password</span>
                                    </td>
                                    <td class="min-w-200px">
                                        <div class="password-wrapper position-relative" style="max-width: 300px;">
                                            <input class="form-control form-control-sm form-control-solid" type="password"
                                                name="password" id="password" autocomplete="off" />
                                            <span
                                                class="password-toggle-icon position-absolute top-50 end-0 translate-middle-y me-3"
                                                onclick="Password_Show_hide()" style="cursor: pointer;">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </div>
                                        <div id="password_error" class="text-danger fs-7 mt-1"></div>
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

@endsection

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

    // password validations
    function ValidatePassword() {
        let password = $("#password").val();
        if (password === "") {
            $("#password_error").html("Password cannot be empty");
            return false;
        } else if (!/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8}$/.test(password)) {
            $("#password_error").html("Password must be 8 chars, include upper, lower, digit, and special char");
            return false;
        } else {
            $("#password_error").html("");
            return true;
        }
    }
</script>