{{-- <h1>User Role Assign Page</h1> --}}
{{-- <form action="{{ route('user.store') }}" method="post" id="user_role_assign">
    @csrf
    <label>Name</label>
    <input type="text" name="name" id="name">
    <span id="name_error" style="color:red">
    </span>
    @error('name')
    {{ $message }}
    @enderror
    <br><br>

    <label>Email</label>
    <input type="email" name="email" id="email">
    <span id="email_error" style="color:red">
    </span>
    @error('email')
    {{ $message }}
    @enderror

    <br><br>

    <label>Password</label>
    <input type="password" name="password" id="password">
    <span id="password_error" style="color:red">
    </span>
    @error('password')
    {{ $message }}
    @enderror

    <br><br>
    <label>Assign Role</label>
    <select name="role" id="role">
        <option value="assign">--Assign Role--</option>
        @foreach ($role as $r)
        <option value="{{ $r->role_name }}">{{$r->role_name}}</option>
        @endforeach
    </select>
    <span id="role_error" style="color:red">

        @error('role')
        {{ $message }}
        @enderror
        <br><br>


        <input type="submit" value="submit">

</form> --}}

{{-- <table border="1">

    @forelse ($data as $dt)
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
    </tr>
    @break
    @empty
    @endforelse

    @forelse ($data as $dt)
    <tr>
        <td>{{$dt->name}}</td>
        <td>{{$dt->email}}</td>
        <td>{{$dt->role}}</td>
        <td>
            <form action="{{ route('user.destroy', $dt->id) }}" method="post">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete">
            </form>
            <form action="{{ route('user.edit', $dt->id) }}" method="get">
                <input type="submit" value="Edit">
            </form>
        </td>
    </tr>
    @empty
    <p>No Role assigned to user Yet.</p>
    @endforelse
</table> --}}

{{-- <a href="/dashboard">Dashboard</a>
<a href="/admin">Admin Login Page</a> --}}




@extends('master')
@section('contents')

    <div class="container-fluid py-10">

        {{-- <div class="d-flex justify-content-end mb-5 gap-5">
            <a href="/dashboard" class="btn btn-secondary">Back to Dashboard</a>
            <a href="/admin" class="btn btn-danger">Logout</a>
        </div> --}}
        {{-- <div class="d-flex justify-content-end mb-5">
        </div> --}}
        <div class="row">
            <!-- Form Section -->
            <div class="col-lg-4 mb-10">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Assign Role</h3>
                    </div>
                    <div class="card-body">
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
                                <input class="form-control form-control-solid" type="password" name="password"
                                    id="password" />
                                <span id="password_error" style="color:red"></span>
                                @error('password')
                                    {{ $message }}
                                @enderror
                            </div>

                            <div class="mb-5">
                                <label class="form-label fw-bolder text-dark fs-6">Assign Role</label>
                                <select name="role" id="role" class="form-select form-select-solid" data-control="select2"
                                    data-placeholder="Select a role" id="role">
                                    <option value="assign">--Assign Role--</option>
                                    @foreach ($role as $r)
                                        <option value="{{ $r->role_name }}">{{ $r->role_name }}</option>
                                    @endforeach
                                </select>
                                <span id="role_error" style="color:red">
                                    @error('role')
                                        {{ $message }}
                                    @enderror
                                </span>
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
                        <h3 class="card-title">Users List</h3>
                    </div>
                    <div class="card-body">
                        @if ($data->isEmpty())
                            <div class="alert alert-warning">No Role assigned to user Yet.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-row-black-100 align-middle gs-0 gy-3">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-150px">Name</th>
                                            <th class="min-w-200px">Email</th>
                                            <th class="min-w-150px">Role</th>
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
                                                    <div class="d-flex gap-2">
                                                        <!-- Edit Button -->
                                                        <form action="{{ route('user.edit', $dt->id) }}" method="get">
                                                            <input type="submit" value="Edit" class="btn btn-sm btn-light-primary">
                                                        </form>

                                                        <!-- Delete Button -->
                                                        <form action="{{ route('user.destroy', $dt->id) }}" method="post"
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
            })
        })

        // name validations
        function ValidateName() {
            let name = $("#name").val();
            if (name == "") {
                $("#name_error").html("Name cannot be blank");
                return false;
            }

            else if (/^[ ]{1,100}$/.test(name)) {
                $("#name_error").html("Name cannot contain spaces only");
                return false;
            }
            else if (!/^[A-Za-z ]{1,100}$/.test(name)) {
                $("#name_error").html("Name should contain characters and spaces only");
                return false;
            }
            else {
                $("#name_error").html("");
                return true;

            }
        }

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

            // role validations
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


    </script>
@endsection