@extends('master')

@section('contents')
@section('title', 'Dashboard')
    <div class="container-fluid py-10">
        <pre>
            <?php //print_r(session()->all());
                // session()->flush();
            ?>
        </pre>
        <div class="row">
             @unless(session('login_email'))
            <div class="col-lg-6 mb-10">
                <div class="card" onclick="window.location='{{ route('roleInstall') }}'">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="col bg-light-primary px-6 py-8 rounded-2">
                                <!--begin::Svg Icon | path: icons/duotune/finance/fin006.svg-->
                                <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                                    <i class='fas fa-user-cog' style='font-size:36px'></i>
                                </span>
                                <!--end::Svg Icon-->
                                <a href="#" class="text-primary fw-bold fs-6">Role Management</a>
                                <div class="text-muted">Total Roles: {{ $roleCount ?? '0' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endunless
            <div class="col-lg-6 mb-10">
                @if(session('without_create'))
                <div class="card">
                @else
                <div class="card" onclick="window.location='{{ route('UserCrudInstall') }}'">
                    @endif
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center">
                            <div class="col bg-light-danger px-6 py-8 rounded-2">
                                <!--begin::Svg Icon | path: icons/duotune/abstract/abs027.svg-->
                                <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
                                    <i class="fa-solid fa-users" style='font-size:36px'></i>
                                </span>
                                <!--end::Svg Icon-->
                                <a href="#" class="text-danger fw-bold fs-6">User Management</a>
                                {{-- <div class="text-muted">Total Users: {{ $userCount ?? '0' }}</div> --}}
                                <div class="text-muted">Total Users: {{ session('without_create') ? '0' : ($userCount ?? '0') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@php
    $pageTitle = 'Dashboard';
@endphp