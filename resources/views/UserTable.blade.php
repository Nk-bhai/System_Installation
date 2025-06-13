@extends('master')
@section('contents')

<div class="container py-10">
        <div class="d-flex justify-content-end mb-5 gap-5">
            <a href="/admin" class="btn btn-danger">Logout</a>
        </div>
        @foreach ($user_name as $name)
            <h1 class="text-center mb-10">Welcome Mr. {{ $name->name }}</h1>
        @endforeach
        <div class="d-flex justify-content-center">
            <div class="col-lg-10"> 
                <div class="card shadow-sm">
                    <div class="card-header border-0 pt-5 d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label fw-bold fs-3 mb-1">User List</span>
                            </h3>
                        </div>

                        @if (in_array('Create', $permissions))
                            <a href="{{ route('user.index') }}" class="btn btn-sm btn-primary">
                                Create User
                            </a>
                        @endif
                    </div>

                    <div class="card-body">
                        @if ($data->isEmpty())
                            <div class="alert alert-danger">No User Data</div>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5">
                                    <thead>
                                        <tr class="fw-bold text-muted">
                                            <th class="min-w-150px">Name</th>
                                            <th class="min-w-200px">Email</th>
                                            <th class="min-w-150px">Role</th>
                                            @if(!in_array('Delete', $permissions) && !in_array('Update' , $permissions))
                                            <th class="min-w-150px text-center" style="display: none">Actions</th>
                                            @else
                                            <th class="min-w-150px text-center">Actions</th>
                                            @endif
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
                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        @if (in_array('Update', $permissions))
                                                            <form action="{{ route('User_Table_edit') }}" method="post">
                                                                @csrf
                                                                <input type="hidden" value="{{ $dt->id }}" name="id">
                                                                <button type="submit" class="btn btn-sm btn-light-primary">
                                                                    Edit
                                                                </button>
                                                            </form>
                                                        @endif

                                                        @if (in_array('Delete', $permissions))
                                                            <form action="{{ route('user.destroy', $dt->id) }}" method="post" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-light-danger">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        @endif
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

@endsection