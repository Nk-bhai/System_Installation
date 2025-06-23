@extends('master')

@section('contents')

@section('title', 'Profile Details')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header" style="border-bottom: 1px solid #ebedf2;">
                <h3 class="card-title">Profile</h3>
            </div>
            <div class="card-body">
                <form id="kt_account_profile_details_form" class="form" method="POST" action="{{ route('SiteControl') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="table-responsive">
                        <table class="table table-row-black-100 align-middle gs-0 gy-3">
                            <tbody>
                                <tr>
                                    <td class="min-w-150px">
                                        <span class="text-dark fw-bold d-block fs-6">Favicon</span>
                                    </td>
                                    <td class="min-w-200px">
                                        <div class="image-input image-input-outline" data-kt-image-input="true"
                                            style="background-image: url({{ asset(session('favicon') ? 'storage/favicon/' . session('favicon') : 'dist/assets/media/avatars/blank.png') }})">

                                            <div class="image-input-wrapper w-125px h-125px"
                                                style="background-image: url({{ session('favicon') ? asset('storage/favicons/' . session('favicon')) : asset('dist/assets/media/avatars/blank.png') }})">
                                            </div>
                                            <label
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                title="Change avatar">
                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                <input type="file" name="Favicon" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="Favicon_remove" id="Favicon_remove" value="0">
                                            </label>
                                            <span
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                title="Cancel Favicon">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                            <span
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                title="Remove Favicon">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                        </div>
                                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                        @error('Favicon')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td class="min-w-150px">
                                        <span class="text-dark fw-bold d-block fs-6">Sidebar Logo</span>
                                    </td>
                                    <td class="min-w-200px">
                                        <div class="image-input image-input-outline" data-kt-image-input="true"
                                            style="background-image: url({{ asset(session('sidebar_logo') ? 'storage/sidebar_logo/' . session('sidebar_logo') : 'dist/assets/media/avatars/blank.png') }})">

                                            <div class="image-input-wrapper w-125px h-125px"
                                                style="background-image: url({{ session('sidebar_logo') ? asset('storage/sidebar_logos/' . session('sidebar_logo')) : asset('dist/assets/media/avatars/blank.png') }})">
                                            </div>
                                            <label
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                title="Change sidebar_logo">
                                                <i class="bi bi-pencil-fill fs-7"></i>
                                                <input type="file" name="sidebar_logo" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="sidebar_logo_remove" id="sidebar_logo_remove"
                                                    value="0">
                                            </label>
                                            <span
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                title="Cancel sidebar_logo">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                            <span
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                title="Remove sidebar_logo">
                                                <i class="bi bi-x fs-2"></i>
                                            </span>
                                        </div>
                                        <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
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
    $pageTitle = 'Site Control';
@endphp

<script>
    document.querySelectorAll('[data-kt-image-input-action="remove"]').forEach((el) => {
    el.addEventListener('click', function () {
        const name = el.getAttribute("title").toLowerCase().replace(/\s+/g, '_');
        if (name === 'remove_favicon') {
            document.getElementById('Favicon_remove').value = 1;
        } else if (name === 'remove_sidebar_logo') {
            document.getElementById('sidebar_logo_remove').value = 1;
        }
    });
});
</script>