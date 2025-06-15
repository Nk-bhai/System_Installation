@extends('master')

@section('contents')
    <div class="container-fluid py-10">
        <div class="row">
            <div class="col-lg-6 mb-10">
                <div class="card">
					<div class="card-body p-3">
						<div class="d-flex align-items-center">
							<div class="flex-grow-1">
								<h3 class="text-dark fw-bolder fs-3">Role Management</h3>
								<div class="text-muted">Total Roles: {{ $roleCount ?? '0' }}</div>
							</div>
						</div>
					</div>
                </div>
            </div>
            <div class="col-lg-6 mb-10">
                <div class="card">
					<div class="card-body p-3">
						<div class="d-flex align-items-center">
							<div class="flex-grow-1">
								<h3 class="text-dark fw-bolder fs-3">Users</h3>
								<div class="text-muted">Total Users: {{ $userCount ?? '0' }}</div>
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