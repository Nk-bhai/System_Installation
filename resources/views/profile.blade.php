@extends('master')

@section('contents')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header" style="border-bottom: 1px solid #ebedf2;">
                <h3 class="card-title">Profile</h3>
            </div>
            <div class="card-body">
                <form id="kt_account_profile_details_form" class="form" method="POST" action="{{ route('profile.update') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="table-responsive">
                        <table class="table table-row-black-100 align-middle gs-0 gy-3">
                            <tbody>
                                <tr>
                                    <td class="min-w-150px">
                                        <span class="text-dark fw-bold d-block fs-6">Avatar</span>
                                    </td>
                                    <td class="min-w-200px">
                                        <div class="image-input image-input-outline" data-kt-image-input="true"
                                            style="background-image: url({{ asset('assets/media/avatars/blank.png') }})">
                                           
                                            <div class="image-input-wrapper w-125px h-125px"
                                                style="background-image: url({{ asset(session('profile_logo') ? 'storage/avatars/' . session('profile_logo') : 'dist/assets/media/avatars/blank.png') }})">
                                            </div>
                                            <label
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                title="Change avatar">
                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="avatar_remove" />
                                            </label>
                                            <span
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                title="Cancel avatar">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                            <span
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                title="Remove avatar">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                        </div>
                                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="min-w-150px">
                                        <span class="text-dark fw-bold d-block fs-6">Email</span>
                                    </td>
                                    <td class="min-w-200px">
                                        <span class="text-dark fw-bold d-block">{{ session('superadmin_email') }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="min-w-150px"></td>
                                    <td class="min-w-200px">
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
   
@endsection

@php
    $pageTitle = 'Profile';
@endphp