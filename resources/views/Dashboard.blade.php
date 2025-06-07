{{-- <h1>Dashboard</h1>

<p>Role</p>
<a href="{{ route('roleInstall') }}">Role Install</a>

<p>User</p>
<form action="{{ route('UserCrudInstall') }}" method="post">
    @csrf
    <input type="submit" value="User install">

</form>


<form action="{{ route('logout') }}" method="post">
    @csrf
    <input type="submit" value="Logout">
</form> --}}



@extends('master')

@section('contents')

<div class="d-flex flex-column flex-root">
    <div class="page d-flex flex-row flex-column-fluid">
        <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
            <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">

                <!-- Sidebar -->
                <div id="kt_aside" class="aside card" style="min-height: 100vh;">
                    <div class="aside-menu flex-column-fluid">
                        <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-arrow-gray-500"
                            id="#kt_aside_menu" data-kt-menu="true">

                            <div class="menu-item">
                                <div class="menu-content pb-2">
                                    <span class="menu-section text-muted text-uppercase fs-8 ls-1">Dashboard</span>
                                </div>
                            </div>

                            <div class="menu-item">
                                <div id="roleMenu" class="menu-link" onclick="toggleSection('role')">
                                   <span class="menu-icon">
                                        <span class="svg-icon svg-icon-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <rect x="2" y="2" width="9" height="9" rx="2" fill="black" />
                                                <rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="black" />
                                                <rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2"
                                                    fill="black" />
                                                <rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                    </span>
                                    <span class="menu-title">Role Management</span>
                                </div>
                            </div>

                            <div class="menu-item">
                                <div id="userMenu" class="menu-link" onclick="toggleSection('user')">
                                   <span class="menu-icon">
                                        <span class="svg-icon svg-icon-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3"
                                                    d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                                    fill="black" />
                                                <path
                                                    d="M19 10.4C19 10.3 19 10.2 19 10C19 8.9 18.1 8 17 8H16.9C15.6 6.2 14.6 4.29995 13.9 2.19995C13.3 2.09995 12.6 2 12 2C11.9 2 11.8 2 11.7 2C12.4 4.6 13.5 7.10005 15.1 9.30005C15 9.50005 15 9.7 15 10C15 11.1 15.9 12 17 12C17.1 12 17.3 12 17.4 11.9C18.6 13 19.9 14 21.4 14.8C21.7 14.2 21.8 13.5 21.9 12.7C20.9 12.1 19.9 11.3 19 10.4Z"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                    </span>
                                    <span class="menu-title">User Management</span>
                                </div>
                            </div>

                            <div class="menu-item mt-4">
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <input type="submit" class="btn btn-sm btn-danger w-100" value="Logout">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-grow-1 p-10">
                    <div id="default-section" class="install-section active text-center">
                        <div class="install-title">Welcome to Dashboard</div>
                    </div>

                    <!-- Role Install Section -->
                    <div id="role-section" class="install-section">
                        <div class="install-title">Role Management</div>
                        <a href="{{ route('roleInstall') }}" class="btn btn-sm btn-primary">Install Roles</a>
                    </div>

                    <!-- User Install Section -->
                    <div id="user-section" class="install-section">
                        <div class="install-title">User Management</div>
                        <form action="{{ route('UserCrudInstall') }}" method="post">
                            @csrf
                            <input type="submit" class="btn btn-sm btn-primary" value="Install Users">
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSection(type) {
        document.getElementById('default-section').classList.remove('active');
        document.getElementById('role-section').classList.remove('active');
        document.getElementById('user-section').classList.remove('active');

        document.getElementById('roleMenu').classList.remove('active');
        document.getElementById('userMenu').classList.remove('active');

        if (type === 'role') {
            document.getElementById('role-section').classList.add('active');
            document.getElementById('roleMenu').classList.add('active');
        } else if (type === 'user') {
            document.getElementById('user-section').classList.add('active');
            document.getElementById('userMenu').classList.add('active');
        }
    }
</script>
@endsection
