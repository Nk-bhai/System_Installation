{{-- <h1>Super Admin Edit Page</h1>
@foreach ($data as $dt )
    
<form action="{{ route('superAdmin.update' , $dt->id) }}" method="post">
    @csrf
    @method('PUT')
    <label>Email</label>
    <input type="email" name="email" value="{{ $dt->email }}">

    <br><br>
    
    <label>Key</label>
    <input type="text" name="key" value="{{ $dt->key }}">

    <br><br>

    <input type="submit" value="Update">
    @endforeach
</form> --}}

@extends('master')
@section('contents')
        <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed"
            style="background-image: url(../../dist/assets/media/illustrations/dozzy-1/14.png">
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                {{-- <h1 style="color:green">Admin Page</h1> --}}
                <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">

                    <div class="card-body">
                        @foreach ($data as $dt)
                            <form action="{{ route('superAdmin.update' , $dt->id) }}" method="post" id="superadmin_update">
                                <div class="text-center mb-10">
                                    <h1 class="text-dark mb-3"> Super Admin Update Page</h1>
                                </div>
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-5">
                                    <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                                    <input class="form-control form-control-solid" type="email" name="email"
                                        value="{{ $dt->email }}" id="email" />
                                    <span id="email_error" style="color:red">
                                    @error('email')
                                    {{ $message }}
                                    @enderror
                                    </span>

                                </div>
                                <div class="mb-5">
                                    <label class="form-label fs-6 fw-bolder text-dark">Password</label>
                                    <input class="form-control form-control-solid" type="text" name="password"
                                        value="{{ $dt->password }}" id="password" disabled />
                                    <span id="password_error" style="color:red">
                                        @error('password')
                                    {{ $message }}
                                    @enderror
                                    </span>
                                </div>
                                <div class="mb-5">
                                    <label class="form-label fs-6 fw-bolder text-dark">Key</label>
                                    <input class="form-control form-control-solid" type="text" name="key"
                                        value="{{ $dt->key }}" id="key" />
                                    <span id="key_error" style="color:red">
                                        @error('key')
                                    {{ $message }}
                                    @enderror
                                    </span>
                                </div>

                               

                                <div class="d-flex justify-content-between gap-2">
                                    <button type="submit" class="btn btn-primary w-50">
                                        Update
                                    </button>
                                    <a href="{{ route('superAdmin.index') }}" class="btn btn-danger w-50">
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
            $("#email").on('input', ValidateEmail);
            $("#password").on('input', ValidatePassword);
            $("#key").on('input', ValidateKey);

            $("#superadmin_update").submit(function (e) {
                let email = ValidateEmail();
                let password = ValidatePassword();
                let key = ValidateKey();
                if (!email || !password || !key) {
                    e.preventDefault();
                }
            })
        })


        // emial validations
        function ValidateEmail() {
            let email = $("#email").val();

            if (email == "") {
                $("#email_error").html("Email cannot be blank");
                return false;
            }
            else if (!/^[A-Za-z0-9.]+@[A-Za-z]{2,7}\.[A-Za-z]{2,100}$/.test(email)) {
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
            }
            else if (!/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8}$/.test(password)) {
                $("#password_error").html("Password must contain atleast 1 uppercase , 1 Lowercase , 1 Digits , 1 Special Character and must be of 8 characters");
                return false;

            } else {
                $("#password_error").html("");
                return true;
            }

        }

        // key validations
        function ValidateKey() {
            let key = $("#key").val();
            console.log(key);
            if (key == "") {
                $("#key_error").html("Key cannot be blank");
                return false;
            } 
            if (key.length != 14) {
                $("#key_error").html("Key must be of 14 characters");
                return false;
            } 
            else {
                $("#key_error").html("");
                return true;

            }

        }




    </script>

@endsection