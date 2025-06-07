<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<h1>Admin Update Page</h1>

 @foreach ($data as $dt )

<form action="{{ route('admin_update') }}" method="post" id="admin_update">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{ $dt->id }}" >
    <label>Name</label>
    <input type="text" name="name" value="{{ $dt->name }}" id="name">
    <span id="name_error" style="color: red"></span>

    <br><br>
    
    <label>Email</label>
    <input type="email" name="email" value="{{ $dt->email }}" id="email">
    <span id="name_error" style="color: red"></span>

    <br><br>

    <label>Assign Role</label>
    <select name="role" id="role">
        @foreach ($role as $r)
            
            <option value="{{ $r->role_name }}" {{ $r->role_name == $dt->role ? "selected" : ""}}>{{$r->role_name}}</option>
        @endforeach
    </select>

    <br><br>    

    <input type="submit" value="Update">

</form>
@endforeach


<script>
    $(document).ready(function () {
        $("#name").on('input', ValidateName);
        $("#email").on('input', ValidateEmail);
        // $("#role").on('change', ValidateRole);

        $("#admin_update").submit(function (e) {
            let name = ValidateName();
            let email = ValidateEmail();
            // let role = ValidateRole();
            if (!name || !email){
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

//     // role validations
//     function ValidateRole() {
//     let role = $("#role").val();
//     if (role === "assign") {
//         $("#role_error").html("Please select a role");
//         return false;
//     } else {
//         $("#role_error").html("");
//         return true;
//     }
// }


</script>

{{-- @extends('master') --}}
{{-- @section('contents') --}}
{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>System Installation</title>
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="../../dist/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
    <link href="../../dist/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>

<body id="kt_body" class="bg-body">

    <div class="d-flex flex-column flex-root">
        <!--begin::Authentication - Sign-in -->
        <div
            class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed">
            <!--begin::Content-->
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">

                <!--begin::Logo-->

                <!--end::Logo-->
                <!--begin::Wrapper-->
                <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                    <!--begin::Form-->
                    @foreach ($data as $dt)
                        <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form"
                            action="{{ route('user.update', $dt->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <!--begin::Heading-->

                            <div class="text-center mb-10">
                                <!--begin::Title-->
                                <h1 class="text-dark mb-3">User Crud Update Page</h1>
                                <!--end::Title-->
                            </div>

                            <div class="fv-row mb-10">
                                <label class="form-label fs-6 fw-bolder text-dark">Name</label>

                                <input class="form-control form-control-lg form-control-solid" type="text" name="name"
                                    autocomplete="off" value="{{ $dt->name }}" />
                            </div>
                            <div class="fv-row mb-10">
                                <div class="d-flex flex-stack mb-2">
                                    <label class="form-label fw-bolder text-dark fs-6 mb-0">Age</label>
                                </div>
                                <input class="form-control form-control-lg form-control-solid" type="number" name="age"
                                    autocomplete="off" value="{{ $dt->age }}" />
                            </div>
                            <div class="text-center">
                                <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                                    <span class="indicator-label">Update</span>
                                </button>
                            </div>
                        </form>
                    @endforeach
                </div>
            </div>

        </div>
    </div> --}}

    {{-- @endsection --}}
{{-- 
</body>

</html> --}}