@extends('master')

@section('contents')

@section('title', 'Role Management')

    <div class="container-fluid py-1">
        <div class="d-flex justify-content-end mb-5 gap-5">
            {{-- <a href="/dashboard" class="btn btn-secondary">Back to Dashboard</a> --}}
            <button type="button" class="btn btn-primary" id="addRoleButton" data-bs-toggle="modal"
                data-bs-target="#addRoleModal">Add</button>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Roles List</h3>
                        <div class="search-bar">
                            <input type="text" id="searchInput" class="form-control form-control-solid"
                                placeholder="Search by role name or permissions..." style="width: 300px;">
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($roleData->isEmpty())
                            <div class="alert alert-warning">No roles assigned yet.</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-row-black-100 align-middle gs-0 gy-3"
                                    id="roleTable">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-150px">Role Name</th>
                                            <th class="min-w-200px">Permissions</th>
                                            <th class="min-w-150px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="roleTableBody">
                                        @foreach ($roleData as $rd)
                                            <tr>
                                                <td><span class="text-dark fw-bold d-block fs-6">{{ $rd->role_name }}</span></td>
                                                <td><span class="text-dark fw-bold d-block">{{ $rd->permissions }}</span></td>
                                                <td>
                                                    <div class="d-flex gap-3 align-items-center">
                                                        <button type="button" class="btn btn-sm btn-light-primary editRoleButton"
                                                            data-bs-toggle="modal" data-bs-target="#editRoleModal"
                                                            data-id="{{ $rd->id }}" data-role-name="{{ $rd->role_name }}"
                                                            data-permissions="{{ is_array($rd->permissions) ? implode(',', $rd->permissions) : $rd->permissions }}"
                                                            data-url="{{ route('role.update', $rd->id) }}">
                                                            Edit
                                                        </button>
                                                        @php
                                                            $hasUsers = !Schema::hasTable('user') || (isset($rd->users) && $rd->users->isEmpty());
                                                        @endphp
                                                        @if ($hasUsers)                                                            
                                                            <button type="button" class="btn btn-sm btn-light-danger deleteRoleButton"
                                                                data-bs-toggle="modal" data-bs-target="#deleteRoleModal"
                                                                data-id="{{ $rd->id }}" data-role-name="{{ $rd->role_name }}"
                                                                data-url="{{ route('role.destroy', $rd->id) }}">
                                                                Delete
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div id="roleCountText">Total Roles: {{ $roleCount ?? '0' }}</div>
                                    <div id="paginationLinks">{{ $roleData->links() }}</div>
                                     <form method="GET" action="{{ route('role.index') }}">
                                        <select name="per_page" onchange="this.form.submit()">
                                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per page</option>
                                            <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 per page</option>
                                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoleModalLabel">Add Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('role.store') }}" method="post" id="role_assign_form">
                        @csrf
                        <div class="mb-5">
                            <label class="form-label fs-6 fw-bolder text-dark">Role Name</label>
                            <input class="form-control form-control-solid" type="text" name="role_name" id="role_name"
                                value="{{ old('role_name') }}" />
                            <span id="role_name_error" style="color:red"></span>
                            @error('role_name')
                                <span style="color:red">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label fw-bolder text-dark fs-6">Permissions:</label>
                            <div class="d-flex flex-wrap gap-4">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="View" id="view" checked
                                        name="permissions[]" {{ in_array('View', old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="view">View</label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="Create" id="create"
                                        name="permissions[]" {{ in_array('Create', old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="create">Create</label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="Delete" id="delete"
                                        name="permissions[]" {{ in_array('Delete', old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="delete">Delete</label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="Update" id="update"
                                        name="permissions[]" {{ in_array('Update', old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="update">Update</label>
                                </div>
                            </div>
                            <div id="permissions_error" style="color:red"></div>
                            @error('permissions')
                                <span style="color:red">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary w-100" id="submitRoleForm">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Role Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" id="role_update_form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_role_id" name="role_id" />
                        <div class="mb-5">
                            <label class="form-label fs-6 fw-bolder text-dark">Role Name</label>
                            <input class="form-control form-control-solid" type="text" name="role_name" id="edit_role_name"
                                value="{{ old('role_name') }}" />
                            <span id="edit_role_name_error" style="color:red"></span>
                            @error('role_name')
                                <span style="color:red">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-5">
                            <label class="form-label fw-bolder text-dark fs-6">Permissions:</label>
                            <div class="d-flex flex-wrap gap-4">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="View" id="edit_view"
                                        name="permissions[]" {{ in_array('View', old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_view">View</label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="Create" id="edit_create"
                                        name="permissions[]" {{ in_array('Create', old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_create">Create</label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="Delete" id="edit_delete"
                                        name="permissions[]" {{ in_array('Delete', old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_delete">Delete</label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" value="Update" id="edit_update"
                                        name="permissions[]" {{ in_array('Update', old('permissions', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit_update">Update</label>
                                </div>
                            </div>
                            <div id="edit_permissions_error" style="color:red"></div>
                            @error('permissions')
                                <span style="color:red">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary w-100">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Role Modal -->
    <div class="modal fade" id="deleteRoleModal" tabindex="-1" aria-labelledby="deleteRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteRoleModalLabel">Delete Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete role <strong id="delete_role_name"></strong>?</p>
                    <form action="" method="post" id="role_delete">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
    $('#searchInput').on('keyup', function () {
        let query = $(this).val();
        fetchUsers(query);
    });

    $(document).on('click', '#paginationLinks a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        let query = $('#searchInput').val();
        fetchUsers(query, page);
    });

    // Add Role Validations
    $("input[name='permissions[]']").on("change", Validate_Permissions);

    $("#role_assign_form").on("submit", function (e) {
        let role_name_valid = Validate_Role_name();
        let permissions_valid = Validate_Permissions();
        if (!role_name_valid || !permissions_valid) {
            e.preventDefault();
        }
    });

    // Edit Role Validations
    $("input[name='permissions[]']").on("change", Validate_Edit_Permissions);

    $("#role_update_form").on("submit", function (e) {
        let role_name_valid = Validate_Edit_Role_name();
        let permissions_valid = Validate_Edit_Permissions();
        if (!role_name_valid || !permissions_valid) {
            e.preventDefault();
        }
    });

    // Populate Edit Modal
    $(document).on('click', '.editRoleButton', function () {
        let id = $(this).data('id');
        let role_name = $(this).data('role-name');
        let permissions = $(this).data('permissions').split(',');
        let url = $(this).data('url');

        $('#editRoleModal').find('form').attr('action', url);
        $('#edit_role_name').val(role_name);
        $('#edit_role_id').val(id);

        // Reset all checkboxes
        $("input[name='permissions[]']").prop('checked', false);
        // Check the appropriate permissions
        permissions.forEach(function (perm) {
            $("#edit_" + perm.toLowerCase().trim()).prop('checked', true);
        });

        try {
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                let modal = new bootstrap.Modal(document.getElementById('editRoleModal'));
                modal.show();
            } else {
                console.error("Bootstrap JS is not loaded. Using fallback.");
                $("#editRoleModal").addClass("show").css("display", "block");
                $("body").addClass("modal-open").append('<div class="modal-backdrop fade show"></div>');
            }
        } catch (e) {
            console.error("Error opening edit modal:", e);
        }
    });

    $(document).on('click', '.deleteRoleButton', function () {
        let id = $(this).data('id');
        let role_name = $(this).data('role-name');
        let url = $(this).data('url');

        $('#deleteRoleModal').find('form').attr('action', url);
        $('#delete_role_name').text(role_name);

        try {
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                let modal = new bootstrap.Modal(document.getElementById('deleteRoleModal'));
                modal.show();
            } else {
                console.error("Bootstrap JS is not loaded. Using fallback.");
                $("#deleteRoleModal").addClass("show").css("display", "block");
                $("body").addClass("modal-open").append('<div class="modal-backdrop fade show"></div>');
            }
        } catch (e) {
            console.error("Error opening delete modal:", e);
        }
    });

    // Fallback to manually trigger add modal if Bootstrap JS is not loaded
    $("#addRoleButton").on("click", function () {
        try {
            if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
                console.error("Bootstrap JS is not loaded. Check your master layout.");
                $("#addRoleModal").addClass("show").css("display", "block");
                $("body").addClass("modal-open").append('<div class="modal-backdrop fade show"></div>');
            }
        } catch (e) {
            console.error("Error triggering modal:", e);
        }
    });

    // Handle manual modal close
    $(".btn-close, [data-bs-dismiss='modal']").on("click", function () {
        $("#addRoleModal").removeClass("show").css("display", "none");
        $("#editRoleModal").removeClass("show").css("display", "none");
        $("#deleteRoleModal").removeClass("show").css("display", "none");
        $("body").removeClass("modal-open");
        $(".modal-backdrop").remove();
        $('#submitRoleForm').prop('disabled', false).text('Submit');
        $('.editRoleButton').blur(); // Remove focus from Edit button
    });

    // Add Role Validation Functions
    function Validate_Role_name() {
        let role_name = $("#role_name").val().trim();
        let roleError = $("#role_name_error");

        roleError.text(""); // Clear previous errors

        if (role_name === "") {
            roleError.text("Role name cannot be empty");
            return false;
        } else if (!/^[A-Za-z ]{1,100}$/.test(role_name)) {
            roleError.text("Role name must contain only letters and spaces");
            return false;
        }

        let isValid = false;

        $.ajax({
            url: "{{ route('role.checkRoleName') }}",
            type: "POST",
            data: {
                role_name: role_name,
                _token: "{{ csrf_token() }}"
            },
            async: false,
            success: function (response) {
                if (response.exists) {
                    roleError.text("This role name is already taken.");
                    isValid = false;
                } else {
                    roleError.text("");
                    isValid = true;
                }
            },
            error: function () {
                roleError.text("Server error while checking role name.");
                isValid = false;
            }
        });

        return isValid;
    }

    function Validate_Permissions() {
        if ($("input[name='permissions[]']:checked").length === 0) {
            $("#permissions_error").text("At least one permission must be selected");
            return false;
        } else {
            $("#permissions_error").text("");
            return true;
        }
    }

    // Edit Role Validation Functions
    function Validate_Edit_Role_name() {
        let role_name = $("#edit_role_name").val().trim();
        let roleId = $("#edit_role_id").val();
        let roleError = $("#edit_role_name_error");

        roleError.text(""); // Clear previous errors

        if (role_name === "") {
            roleError.text("Role name cannot be empty");
            return false;
        } else if (!/^[A-Za-z ]{1,100}$/.test(role_name)) {
            roleError.text("Role name must contain only letters and spaces");
            return false;
        }

        let isValid = false;

        $.ajax({
            url: "{{ route('role.checkRoleName') }}",
            type: "POST",
            data: {
                role_name: role_name,
                role_id: roleId,
                _token: "{{ csrf_token() }}"
            },
            async: false,
            success: function (response) {
                if (response.exists) {
                    roleError.text("This role name is already taken.");
                    isValid = false;
                } else {
                    roleError.text("");
                    isValid = true;
                }
            },
            error: function () {
                roleError.text("Server error while checking role name.");
                isValid = false;
            }
        });

        return isValid;
    }

    function Validate_Edit_Permissions() {
        if ($("input[name='permissions[]']:checked").length === 0) {
            $("#edit_permissions_error").text("At least one permission must be selected");
            return false;
        } else {
            $("#edit_permissions_error").text("");
            return true;
        }
    }
});

function fetchUsers(query = '', page = 1) {
    $.ajax({
        url: "{{ route('role.search') }}",
        type: "GET",
        data: { query: query, page: page },
        success: function (response) {
            $('#roleTableBody').html(response.html);
            $('#paginationLinks').html(response.pagination);
            $('#roleCountText').text('Total Users: ' + response.count);
        },
        error: function () {
            alert('Error fetching data.');
        }
    });
}
    </script>
@endsection

@php
    $pageTitle = 'Role Management';
@endphp