{{-- <h1>User Crud Update Page</h1>

@foreach ($data as $dt )

<form action="{{ route('user.update' , $dt->id) }}" method="post" id="user_update">
    @csrf
    @method('PUT')
    <label>Name</label>
    <input type="text" name="name" value="{{ $dt->name }}" id="name">
    <span id="name_error" style="color:red"></span>

    <br><br>

    <label>Email</label>
    <input type="email" name="email" value="{{ $dt->email }}" id="email">
    <span id="email_error" style="color:red"></span>

    <br><br>

    <label>Assign Role</label>
    <select name="role" id="role">
        @foreach ($role as $r)

        <option value="{{ $r->role_name }}" {{ $r->role_name == $dt->role ? "selected" : ""}}>{{$r->role_name}}</option>
        @endforeach
    </select>
    <span id="role_error" style="color:red"></span>

    <br><br>

    <input type="submit" value="Update">

</form>
@endforeach --}}



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
                            <form action="{{ route('user.update', $dt->id) }}" method="post" id="user_update">
                                <div class="text-center mb-10">
                                    <h1 class="text-dark mb-3"> User Update Page</h1>
                                </div>
                                @csrf
                                @method('PUT')
                                <div class="mb-5">
                                    <label class="form-label fs-6 fw-bolder text-dark">Name</label>
                                    <input class="form-control form-control-solid" type="text" name="name"
                                        value="{{ $dt->name }}" id="name" />
                                    <span id="name_error" style="color:red"></span>
                                </div>
                                <div class="mb-5">
                                    <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                                    <input class="form-control form-control-solid" type="email" name="email"
                                        value="{{ $dt->email }}" id="email" />
                                    <span id="email_error" style="color:red"></span>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label fw-bolder text-dark fs-6">Assign Role:</label>
                                    <div class="d-flex flex-wrap gap-4">
                                        <select name="role" id="role" class="form-select form-select-solid"
                                            data-control="select2" data-placeholder="Select a role" id="role">
                                            <option value="assign">--Assign Role--</option>
                                            @foreach ($role as $r)
                                                <option value="{{ $r->role_name }}" {{ $r->role_name == $dt->role ? "selected" : ""}}>
                                                    {{ $r->role_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="role_error" style="color:red"></div>
                                </div>

                                <div class="d-flex justify-content-between gap-2">
                                    <button type="submit" class="btn btn-primary w-50">
                                        Update
                                    </button>
                                    <a href="{{ route('role.index') }}" class="btn btn-danger w-50">
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
        $("#name").on('input', ValidateName);
        $("#email").on('input', ValidateEmail);
        $("#role").on('change', ValidateRole);

        $("#user_update").submit(function (e) {
            let name = ValidateName();
            let email = ValidateEmail();
            let role = ValidateRole();
            if (!name || !email || !role) {
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