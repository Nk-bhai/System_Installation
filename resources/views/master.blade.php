<html lang="en">
<!--begin::Head-->

<head>
	<title>System Installation</title>
	<meta name="description"
		content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue & Laravel versions. Grab your copy now and get life-time updates for free." />
	<meta name="keywords"
		content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta charset="utf-8" />
	<meta property="og:locale" content="en_US" />
	<meta property="og:type" content="article" />
	<meta property="og:title"
		content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular & Laravel Admin Dashboard Theme" />
	<meta property="og:url" content="https://keenthemes.com/metronic" />
	<meta property="og:site_name" content="Keenthemes | Metronic" />
	<link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
	<link rel="icon" href="{{ asset('elsner_favicon.svg') }}" type="image/x-icon">
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Page Vendor Stylesheets(used by this page)-->
	<link href="{{ asset('dist/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet"
		type="text/css" />
	<!--end::Page Vendor Stylesheets-->
	<!--begin::Global Stylesheets Bundle(used by all pages)-->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
		integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link href="{{ asset('dist/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('dist/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
	<!--end::Global Stylesheets Bundle-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<style>
		.password-wrapper {
			position: relative;
			width: 100%;
			/* Ensure the wrapper takes full width of the input */
		}

		.password-wrapper input {
			padding-right: 40px;
			/* Space for the eye icon */
			width: 100%;
			/* Ensure input takes full width */
		}

		.password-toggle-icon {
			position: absolute;
			right: 15px;
			/* Position inside the input field */
			top: 50%;
			/* Vertically center the icon */
			transform: translateY(-50%);
			cursor: pointer;
			color: #7e8299;
			/* Matches Metronic's muted text color */
			font-size: 10.1rem;
			/* Slightly larger for visibility */
			transition: color 0.3s ease;
			/* Smooth hover transition */
		}

		.password-toggle-icon:hover {
			color: #009ef7;
			/* Matches Metronic's primary color for interactivity */
		}

		/* Fine-tune vertical alignment for large inputs */
		.form-control-lg~.password-toggle-icon {
			top: calc(50% + 2px);
		}

		#kt_content {
			padding-top: 0 !important;
			margin-top: 0 !important;
		}

		.wrapper {
			min-height: auto !important;
		}

		.popup {
			position: fixed;
			top: 20px;
			left: 50%;
			transform: translateX(-50%);
			background-color: #4CAF50;
			/* Green background */
			color: white;
			padding: 15px 25px;
			border-radius: 25px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
			z-index: 1000;
			text-align: center;
			opacity: 0;
			/* Initially hidden */
			transition: opacity 0.5s ease-in-out;
		}

		.popup.show {
			opacity: 1;
		}
	</style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body"
	class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed"
	style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
	<!--begin::Main-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Page-->
		<div class="page d-flex flex-row">
			<!--begin::Aside-->
			<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true"
				data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}"
				data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}"
				data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
				<!--begin::Brand-->
				<div class="aside-logo flex-column-auto" id="kt_aside_logo">
					<!--begin::Logo-->
					<a href="/dashboard">
						<img alt="Logo" src="{{ asset('dist/assets/media/logos/elsner-logo.svg') }}"
							class="h-25px logo" />
					</a>
					<!--end::Logo-->
					<!--begin::Aside toggler-->
					<div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle"
						data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
						data-kt-toggle-name="aside-minimize">
						<!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
						<span class="svg-icon svg-icon-1 rotate-180">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
								fill="none">
								<path opacity="0.5"
									d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
									fill="black" />
								<path
									d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
									fill="black" />
							</svg>
						</span>
						<!--end::Svg Icon-->
					</div>
					<!--end::Aside toggler-->
				</div>
				<!--end::Brand-->
				<!--begin::Aside menu-->
				<div class="aside-menu flex-column-fluid">
					<!--begin::Aside Menu-->
					<div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true"
						data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto"
						data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer"
						data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
						<!--begin::Menu-->
						<div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500"
							id="#kt_aside_menu" data-kt-menu="true">
							<div class="menu-item">
								<a class="menu-link {{ Request::is('dashboard') ? 'active' : '' }}" href="/dashboard">
									<span class="menu-icon">
										<!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
										<span class="svg-icon svg-icon-2">
											<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
												viewBox="0 0 24 24" fill="none">
												<rect x="2" y="2" width="9" height="9" rx="2" fill="black" />
												<rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2"
													fill="black" />
												<rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2"
													fill="black" />
												<rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2"
													fill="black" />
											</svg>
										</span>
										<!--end::Svg Icon-->
									</span>
									<span class="menu-title">Dashboard</span>
								</a>
							</div>
							<div class="menu-item">
								{{-- <a class="menu-link {{ Request::is('roleinstall') ? 'active' : '' }}" href="/role">
									--}}
									<a class="menu-link {{ Request::is('roleinstall') ? 'active' : '' }}"
										href="{{ route('roleInstall') }}">

										<span class="menu-icon">
											<!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
											<span class="svg-icon svg-icon-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="25"
													viewBox="0 0 24 25" fill="none">
													<path opacity="0.3"
														d="M8.9 21L7.19999 22.6999C6.79999 23.0999 6.2 23.0999 5.8 22.6999L4.1 21H8.9ZM4 16.0999L2.3 17.8C1.9 18.2 1.9 18.7999 2.3 19.1999L4 20.9V16.0999ZM19.3 9.1999L15.8 5.6999C15.4 5.2999 14.8 5.2999 14.4 5.6999L9 11.0999V21L19.3 10.6999C19.7 10.2999 19.7 9.5999 19.3 9.1999Z"
														fill="black" />
													<path
														d="M21 15V20C21 20.6 20.6 21 20 21H11.8L18.8 14H20C20.6 14 21 14.4 21 15ZM10 21V4C10 3.4 9.6 3 9 3H4C3.4 3 3 3.4 3 4V21C3 21.6 3.4 22 4 22H9C9.6 22 10 21.6 10 21ZM7.5 18.5C7.5 19.1 7.1 19.5 6.5 19.5C5.9 19.5 5.5 19.1 5.5 18.5C5.5 17.9 5.9 17.5 6.5 17.5C7.1 17.5 7.5 17.9 7.5 18.5Z"
														fill="black" />
												</svg>
											</span>
											<!--end::Svg Icon-->
										</span>
										<span class="menu-title">Role Management</span>
									</a>
							</div>
							<div class="menu-item">
								{{-- <a class="menu-link {{ Request::is('user') ? 'active' : '' }}" href="/user"> --}}
									<a class="menu-link {{ Request::is('user') ? 'active' : '' }}"
										href="{{ route('UserCrudInstall') }}">
										<span class="menu-icon">
											<!--begin::Svg Icon | path: icons/duotune/art/art002.svg-->
											<span class="svg-icon svg-icon-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
													viewBox="0 0 24 24" fill="none">
													<path opacity="0.3"
														d="M21.25 18.525L13.05 21.825C12.35 22.125 11.65 22.125 10.95 21.825L2.75 18.525C1.75 18.125 1.75 16.725 2.75 16.325L4.04999 15.825L10.25 18.325C10.85 18.525 11.45 18.625 12.05 18.625C12.65 18.625 13.25 18.525 13.85 18.325L20.05 15.825L21.35 16.325C22.35 16.725 22.35 18.125 21.25 18.525ZM13.05 16.425L21.25 13.125C22.25 12.725 22.25 11.325 21.25 10.925L13.05 7.62502C12.35 7.32502 11.65 7.32502 10.95 7.62502L2.75 10.925C1.75 11.325 1.75 12.725 2.75 13.125L10.95 16.425C11.65 16.725 12.45 16.725 13.05 16.425Z"
														fill="black" />
													<path
														d="M11.05 11.025L2.84998 7.725C1.84998 7.325 1.84998 5.925 2.84998 5.525L11.05 2.225C11.75 1.925 12.45 1.925 13.15 2.225L21.35 5.525C22.35 5.925 22.35 7.325 21.35 7.725L13.05 11.025C12.45 11.325 11.65 11.325 11.05 11.025Z"
														fill="black" />
												</svg>
											</span>
											<!--end::Svg Icon-->
										</span>
										<span class="menu-title">Users</span>
									</a>
							</div>
							<div class="menu-item">
								{{-- <a class="menu-link {{ Request::is('profile') ? 'active' : '' }}" href="/profile">
									--}}
									<a class="menu-link {{ Request::is('profile') ? 'active' : '' }}"
										href="{{ route('profile.show') }}">
										<span class="menu-icon">
											<!--begin::Svg Icon | path: icons/duotune/art/art002.svg-->
											<span class="svg-icon svg-icon-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
													viewBox="0 0 24 24" fill="none">
													<path
														d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z"
														fill="black" />
													<rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4"
														fill="black" />
												</svg>
											</span>
											<!--end::Svg Icon-->
										</span>
										<span class="menu-title">Profile Details</span>
									</a>
							</div>

						</div>
						<!--end::Menu-->
					</div>
					<!--end::Aside Menu-->
				</div>
				<!--end::Aside menu-->
			</div>
			<!--end::Aside-->
			<!--begin::Wrapper-->
			<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
				<!--begin::Header-->
				<div id="kt_header" class="header align-items-stretch">
					<div class="container-fluid d-flex align-items-stretch justify-content-between">
						<div class="d-flex align-items-center">
							<h1 class="text-dark fw-bolder fs-3">{{ $pageTitle ?? 'Dashboard' }}</h1>
						</div>
						<div class="d-flex align-items-center">
							<a href="{{ route('logout') }}" class="btn btn-danger">Logout</a>
							<div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
								<!--begin::Menu wrapper-->
								<div class="cursor-pointer symbol symbol-30px symbol-md-40px"
									data-kt-menu-trigger="click" data-kt-menu-attach="parent"
									data-kt-menu-placement="bottom-end">
									{{-- <img
										src="{{ asset('storage/avatars/'.session('profile_logo') ?? 'dist/assets/media/avatars/blank.png') }}"
										alt="user" /> --}}
										{{-- @if (session('profile_logo'))
											<?php //  dd(session('profile_logo'))  ?>
										@endif --}}
									<img src="{{ asset(session('profile_logo') ? 'storage/avatars/' . session('profile_logo') : 'dist/assets/media/avatars/blank.png') }}"
										alt="user" />

								</div>
								<!--begin::Menu-->
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px"
									data-kt-menu="true">
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<div class="menu-content d-flex align-items-center px-3">
											<!--begin::Avatar-->
											<div class="symbol symbol-50px me-5">

												<img alt="Logo"
													src="{{ asset('storage/avatars/' . session('profile_logo') ?? 'dist/assets/media/avatars/blank.png') }}" />
											</div>
											<!--end::Avatar-->
											<!--begin::Username-->
											<div class="d-flex flex-column">
												<div class="fw-bolder d-flex align-items-center fs-5">
													{{ session('superadmin_name') ?? 'User' }}
													<!-- <span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">Pro</span> -->
												</div>
												<a href="#" class="fw-bold text-muted text-hover-primary fs-7">
													{{ session('superadmin_email') ?? 'user@example.com' }}
												</a>
											</div>
											<!--end::Username-->
										</div>
									</div>
									<!--end::Menu item-->
								</div>
								<!--end::Menu-->
							</div>
						</div>
					</div>
				</div>
				<!--end::Header-->
				<!--begin::Content-->
				<div class="content d-flex flex-column" id="kt_content">

					@yield('contents')
				</div>
				<!--end::Content-->
			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Page-->
	</div>
	<!--end::Main-->
	<script>var hostUrl = "assets/";</script>
	<!--begin::Javascript-->
	<!--begin::Global Javascript Bundle(used by all pages)-->
	<script src="{{ asset('dist/assets/plugins/global/plugins.bundle.js') }}"></script>
	<script src="{{ asset('dist/assets/js/scripts.bundle.js') }}"></script>
	<!--end::Global Javascript Bundle-->
	<!--begin::Page Vendors Javascript(used by this page)-->
	<script src="{{ asset('dist/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
	<!--end::Page Vendors Javascript-->
	<!--begin::Page Custom Javascript(used by this page)-->
	<script src="{{ asset('dist/assets/js/custom/widgets.js') }}"></script>
	<script src="{{ asset('dist/assets/js/custom/apps/chat/chat.js') }}"></script>
	<script src="{{ asset('dist/assets/js/custom/modals/create-app.js') }}"></script>
	<script src="{{ asset('dist/assets/js/custom/modals/upgrade-plan.js') }}"></script>
	<!--end::Page Custom Javascript-->
	<!--end::Javascript-->
</body>

</html>