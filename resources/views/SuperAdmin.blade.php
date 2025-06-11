{{-- <h1>Super Admin</h1>

<form action="{{ route('superAdmin.store') }}" method="post">
    @csrf
    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}">
    @error('email')
    {{ $message }}
    @enderror

    <br><br>

    <label>Password</label>
    <input type="password" name="password" value="{{ old('password') }}">
    @error('password')
    {{ $message }}
    @enderror

    <br><br>

    <label>Key</label>
    <input type="text" name="key" value="{{ old('key') }}">
    @error('key')
    {{ $message }}
    @enderror

    <br><br>

    <input type="submit" value="Activate">
</form>

<table border="1">
    <tr>

        <th>Email</th>
        <th>Password</th>
        <th>Key</th>
        <th>Actions</th>
    </tr>

    @foreach ($data as $dt)
    <tr>
        <td>{{$dt->email}}</td>
        <td>{{$dt->password}}</td>
        <td>{{$dt->key}}</td>
        <td>
            <form action="{{ route('superAdmin.destroy', $dt->id) }}" method="post">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete">
            </form>
            <form action="{{ route('superAdmin.edit', $dt->id) }}" method="get">
                <input type="submit" value="Edit">
            </form>

        </td>
    </tr>

    @endforeach

</table> --}}

@extends('master')
@section('contents')

    <div class="container-fluid py-10">

        {{-- <div class="d-flex justify-content-end mb-5 gap-5">
            <a href="/dashboard" class="btn btn-secondary">Back to Dashboard</a>
            <a href="/admin" class="btn btn-danger">Logout</a>
        </div> --}}
        <div class="row">
            <!-- Form Section -->
            <div class="col-lg-4 mb-10">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Super Admin Page</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('superAdmin.store') }}" method="post" id="superadmin_form">
                            @csrf

                            <div class="mb-5">
                                <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                                <input class="form-control form-control-solid" type="email" name="email"
                                    value="{{ old('email') }}" id="email" />
                                <span id="email_error" style="color:red">
                                    @error('email')
                                    {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="mb-5">
                                <label class="form-label fs-6 fw-bolder text-dark">Password</label>
                                <input class="form-control form-control-solid" type="password" name="password" id="password"
                                    value="{{ old('password') }}" />
                                <span id="password_error" style="color:red">

                                    @error('password')
                                    {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div class="mb-5">
                                <label class="form-label fs-6 fw-bolder text-dark">Key</label>
                                <input class="form-control form-control-solid" type="text" name="key"
                                    value="{{ old('key') }}" id="key" />
                                <span id="key_error" style="color:red">
                                    @error('key')
                                    {{ $message }}
                                    @enderror
                                </span>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-primary w-100">
                                    Activate
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
                        <h3 class="card-title">Access Details List</h3>
                    </div>
                    <div class="card-body">
                        @if ($data->isEmpty())
                            <div class="alert alert-warning">No Details assigned Yet.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-row-black-100 align-middle gs-0 gy-3">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-200px">Email</th>
                                            <th class="min-w-150px">Password</th>
                                            <th class="min-w-150px">Key</th>
                                            <th class="min-w-150px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $dt)
                                            <tr>
                                                <td>
                                                    <span class="text-dark fw-bold d-block">{{ $dt->email }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-dark fw-bold d-block">{{ $dt->password }}</span>
                                                </td>
                                                <td>
                                                    <span class="text-dark fw-bold d-block">{{ $dt->key }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <!-- Edit Button -->
                                                        <form action="{{ route('superAdmin.edit', $dt->id) }}" method="get">
                                                            <input type="submit" value="Edit" class="btn btn-sm btn-light-primary">
                                                        </form>

                                                        <!-- Delete Button -->
                                                        <form action="{{ route('superAdmin.destroy', $dt->id) }}" method="post"
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
            $("#email").on('input', ValidateEmail);
            $("#password").on('input', ValidatePassword);
            $("#key").on('input', ValidateKey);

            $("#superadmin_form").submit(function (e) {
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