<h1>User Role Assign Page</h1>
<form action="{{ route('user.store') }}" method="post">
    @csrf
    <label>Name</label>
    <input type="text" name="name">

    <br><br>

    <label>Email</label>
    <input type="email" name="email">

    <br><br>

    <label>Password</label>
    <input type="password" name="password">

    <br><br>
    <label>Assign Role</label>
    <select name="role">
        @foreach ($role as $r)
            <option value="{{ $r->role_name }}">{{$r->role_name}}</option>
        @endforeach
    </select>

    <br><br>


    <input type="submit" value="submit">

</form>

<table border="1">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
    </tr>
    @foreach ($data as $dt)
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
    @endforeach
</table>

<a href="/dashboard">Dashboard</a>
<a href="/admin">Admin Login Page</a>



{{-- @extends('master')
@section('contents')
<a href="/dashboard">Dashboard</a>
<div class="d-flex flex-column flex-root">
    <!--begin::Authentication - Sign-in -->
    <div
        class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed">

        <!--begin::Wrapper-->
        <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
            <!--begin::Form-->
            <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form" action="{{ route('user.store') }}"
                method="post">
                @csrf

                <!--begin::Heading-->
                <div class="mb-10">
                    <!--begin::Title-->
                    <h1 class="text-dark mb-3 text-center">User CRUD Page</h1>
                    <!--end::Title-->
                </div>
                <!--end::Heading-->

                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <label class="form-label fs-6 fw-bolder text-dark">Name</label>
                    <input class="form-control form-control-lg form-control-solid" type="text" name="name"
                        autocomplete="off" />
                </div>

                <div class="fv-row mb-10">
                    <label class="form-label fw-bolder text-dark fs-6 mb-0">Age</label>
                    <input class="form-control form-control-lg form-control-solid" type="number" name="age"
                        autocomplete="off" />
                </div>
                <!--end::Input group-->

                <!--begin::Actions-->
                <div class="text-center">
                    <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                        <span class="indicator-label">Submit</span>
                    </button>
                </div>
                <!--end::Actions-->

            </form>
            <!--end::Form-->
        </div>
        <!--end::Wrapper-->

    </div>
    <!--end::Authentication - Sign-in-->
</div>

<!--begin::Table-->
<div class="container mt-10">
    <table class="table table-bordered align-middle table-row-dashed fs-6 gy-5" id="kt_permissions_table" border="2">
        @forelse ($data as $dt )

        <thead>
            <tr class="text-start text-black-400 fw-bolder fs-7 text-uppercase gs-0">
                <th class="text-center min-w-125px">Name</th>
                <th class="text-center min-w-125px">Age</th>
                <th class="text-center min-w-100px">Actions</th>
            </tr>
        </thead>
        @break
        @empty

        @endforelse

        @forelse ($data as $dt)
        <tbody class="fw-bold text-gray-600">
            <tr>
                <td class="text-center">{{ $dt->name }}</td>
                <td class="text-center">{{ $dt->age }}</td>
                <td class="text-center">
                    <form action="{{ route('user.destroy', $dt->id) }}" method="post" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                    <form action="{{ route('user.edit', $dt->id) }}" method="get" style="display:inline-block;">
                        <button type="submit" class="btn btn-sm btn-success">Edit</button>
                    </form>
                </td>
            </tr>
            @empty
            <p>No Data</p>
            @endforelse
        </tbody>
    </table>
</div>
<!--end::Table-->

@endsection --}}