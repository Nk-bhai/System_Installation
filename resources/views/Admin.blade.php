@extends('master')
@section('contents')
    {{-- <h1>Admin Login Page</h1>

    <form action="{{ route('admin') }}" method="post">
        @csrf
        <label>Email</label>
        <input type="email" name="email">

        <br><br>

        <label>Password</label>
        <input type="password" name="password">

        <br><br>

        <input type="submit" value="Login">
    </form> --}}

    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
            style="background-image: url(dist/assets/media/illustrations/dozzy-1/14.png">
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                {{-- <h1 style="color:green">Admin Page</h1> --}}
                <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                    <!--begin::Form-->
                    <form class="form w-100"  action="{{ route('admin') }}" method="post"
                        id="loginform">
                        @csrf
                        <div class="text-center mb-10">
                            <h1 class="text-dark mb-3"> Login Page</h1>
                        </div>
                        <div class="fv-row mb-10">
                            <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                            <input class="form-control form-control-lg form-control-solid" type="email" name="email"
                                id="email" />
                            <div id="email_error" style="color:red"></div>
                        </div>
                        <div class="fv-row mb-10">
                            <div class="d-flex flex-stack mb-2">
                                <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                            </div>
                            <input class="form-control form-control-lg form-control-solid" type="password" name="password"
                                autocomplete="off" id="password" />
                            <div id="password_error" style="color:red"></div>
                        </div>
                        <div style="color:red">
                            {{ session('error') }}
                        </div>
                        <div class="text-center">
                            <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                                <span class="indicator-label">Log In</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    	<script>
        // frontend validations
        $(document).ready(function () {
            $("#email").on("input", ValidateEmail);
            $("#password").on("input", ValidatePassword);

            $("#loginform").submit(function (e) {
                let email = ValidateEmail();
                let password = ValidatePassword();
                if (!email || !password) {
                    e.preventDefault();
                }
            })
        })

        // email validations
        function ValidateEmail() {
            let email = $("#email").val();
            if (email == "") {
                $("#email_error").html("Email cannot be blank");
                return false;
            }
            else if (!/^[A-Za-z0-9.]+@[A-Za-z]{2,7}\.[A-Za-z]{2,3}$/.test(email)) {
                $("#email_error").html("Email must be valid");
                return false;
            }
            else {
                $("#email_error").html("");
                return true;
            }
        }

        // password validations
        function ValidatePassword() {
            let password = $("#password").val();
            if (password == "") {
                $("#password_error").html("Password cannot be blank");
                return false;
            } else {
                $("#password_error").html("");
                return true;

            }
        }
    </script>
    
@endsection