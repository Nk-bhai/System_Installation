<html lang="en">
<!--begin::Head-->

<head>
	<title>@yield('title', 'System Installation')</title>
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
	{{--
	<link rel="icon"
		href="{{ session('favicon') ? asset('storage/favicons/' . session('favicon')) : asset('elsner_favicon.svg') }}"
		type="image/x-icon"> --}}
	<link id="favicon" rel="icon" href="{{ asset('storage/favicons/' . session('favicon')) }}" type="image/x-icon">

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
	<!-- DataTables CSS -->
	<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

	<!--end::Global Stylesheets Bundle-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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

		.flex-root,
		.page,
		.wrapper {
			min-height: 100vh;
			display: flex;
			flex-direction: column;
		}

		.content {
			flex: 1 0 auto;
		}

		.footer {
			flex-shrink: 0;
		}

		h1,
		h2,
		h3,
		h4 {
			font-size: calc(1.2rem + 0.5vw);
		}

		/* Sidebar scroll fix on mobile */
		@media (max-width: 991.98px) {
			#kt_aside_menu_wrapper {
				max-height: 80vh;
				overflow-y: auto;
			}
		}

		/* Responsive wrapper for buttons and headers */
		@media (max-width: 576px) {
			.d-flex.align-items-center.justify-content-between {
				flex-direction: column;
				align-items: flex-start !important;
			}
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
						{{-- <img alt="Logo"
							src="{{ session('sidebar_logo') ? asset('storage/sidebar_logos/' . session('sidebar_logo')) : asset('dist/assets/media/logos/elsner-logo.svg') }}"
							class="h-25px logo" /> --}}
						<img alt="Logo" src="{{ asset('storage/sidebar_logos/' . session('sidebar_logo')) }}"
							class="h-25px logo"
							onerror="this.onerror=null;this.src='{{ asset('dist/assets/media/logos/elsner-logo.svg') }}';" />
						{{-- <img alt="Logo" src="{{ asset('dist/assets/media/logos/elsner-logo.svg')  }}"
							class="h-25px logo" /> --}}
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
							@unless(session('login_email'))
								<div class="menu-item">
									{{-- <a class="menu-link {{ Request::is('user') ? 'active' : '' }}" href="/user"> --}}
										<a class="menu-link {{ Request::is('role') ? 'active' : '' }}"
											href="{{ route('roleInstall') }}">
											<span class="menu-icon">
												<!--begin::Svg Icon | path: icons/duotune/art/art002.svg-->
												<span class="svg-icon svg-icon-2">
													<i class='fas fa-user-cog'></i>
												</span>
												<!--end::Svg Icon-->
											</span>
											<span class="menu-title">Role Management</span>
										</a>
								</div>
							@endunless
							@unless(session('without_create'))
								<div class="menu-item">
									{{-- <a class="menu-link {{ Request::is('user') ? 'active' : '' }}" href="/user"> --}}
										<a class="menu-link {{ Request::is('user') ? 'active' : '' }}"
											href="{{ route('UserCrudInstall') }}">
											<span class="menu-icon">
												<!--begin::Svg Icon | path: icons/duotune/art/art002.svg-->
												<span class="svg-icon svg-icon-2">
													<i class="fa-solid fa-users"></i>
												</span>
												<!--end::Svg Icon-->
											</span>
											<span class="menu-title">User Management</span>
										</a>
								</div>
							@endunless
							@unless(session('login_email'))
								<div class="menu-item">
									{{-- <a class="menu-link {{ Request::is('user') ? 'active' : '' }}" href="/user"> --}}
										<a class="menu-link {{ Request::is('site_control') ? 'active' : '' }}"
											href="{{ route('SiteControlPage') }}">
											<span class="menu-icon">
												<!--begin::Svg Icon | path: icons/duotune/art/art002.svg-->
												<span class="svg-icon svg-icon-2">
													<i class="fa-solid fa-users"></i>
												</span>
												<!--end::Svg Icon-->
											</span>
											<span class="menu-title">Site Control</span>
										</a>
								</div>
							@endunless
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
						<div class="d-lg-none align-items-center">
							<button class="btn btn-icon btn-active-light-primary me-2" id="kt_aside_mobile_toggle">
								<!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
								<span class="svg-icon svg-icon-2x">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none">
										<path d="M4 5h16M4 12h16M4 19h16" stroke="black" stroke-width="2"
											stroke-linecap="round" />
									</svg>
								</span>
								<!--end::Svg Icon-->
							</button>
						</div>
						<div class="d-flex align-items-center">
							<h1 class="text-dark fw-bolder fs-3">{{ $pageTitle ?? 'Dashboard' }}</h1>
						</div>
						<div class="d-flex align-items-center">
							{{-- <a href="{{ route('logout') }}" class="btn btn-danger">Logout</a> --}}
							<div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
								<!--begin::Menu wrapper-->
								<div class="cursor-pointer symbol symbol-30px symbol-md-40px"
									data-kt-menu-trigger="click" data-kt-menu-attach="parent"
									data-kt-menu-placement="bottom-end">
									@php
										$profileLogo = 'dist/assets/media/avatars/blank.png'; // Default image

										if (session('login_email')) {
											$user = \App\Models\UserModel::where('email', session('login_email'))->first();
											if ($user && $user->profile_logo) {
												$profileLogo = 'storage/avatars/' . $user->profile_logo;
											}
										} elseif (session('profile_logo')) {
											$profileLogo = 'storage/avatars/' . session('profile_logo');
										}
									@endphp

									<img src="{{ asset($profileLogo) }}" alt="user" />
								</div>
								<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px"
									data-kt-menu="true">
									<!--begin::Menu item-->
									<div class="menu-item px-3">
										<div class="menu-content d-flex align-items-center px-3">
											<!--begin::Avatar-->
											<div class="symbol symbol-50px me-5">
												{{-- <img alt="Logo"
													src="{{ asset('storage/avatars/' . session('profile_logo') ?? 'dist/assets/media/avatars/blank.png') }}" />
												--}}
												<img alt="Logo"
													src="{{ asset($profileLogo ?? 'dist/assets/media/avatars/blank.png') }}" />
											</div>
											<!--end::Avatar-->
											<!--begin::Username-->
											<div class="d-flex flex-column">
												<div class="fw-bolder d-flex align-items-center fs-5">
													{{ session('login_name') ?? 'Super Admin' }}
												</div>
												<a href="#" class="fw-bold text-muted text-hover-primary fs-7">
													{{ session('login_email') ?? session('superadmin_email') ?? 'Super Admin' }}
												</a>
											</div>
											<!--end::Username-->
										</div>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator my-2"></div>
									<!--end::Menu separator-->
									<!--begin::Menu item-->
									<div class="menu-item px-5">
										<a href="{{ route('profile.show') }}" class="menu-link px-5">My Profile</a>
									</div>
									<!--end::Menu item-->

									<!--end::Menu item-->
									<!--begin::Menu item-->
									<div class="menu-item px-5">
										<a href="{{ route('logout') }}" class="menu-link px-5">Sign Out</a>
									</div>
									<!--end::Menu item-->
									<!--begin::Menu separator-->
									<div class="separator my-2"></div>
									<!--end::Menu separator-->

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

				<!--begin::Footer-->
				<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
					<!--begin::Container-->
					<div class="container-fluid d-flex flex-column align-items-center justify-content-center">
						<!--begin::Copyright-->
						<div class="text-dark text-center">
							{{-- <span class="text-muted fw-bold me-1">© {{ date('Y') }} Elsner Technologies Pvt.
								Ltd.</span> --}}
							<span class="text-muted fw-bold me-1">©
								{{ session('copyright') ?? " Elsner Technologies Pvt. Ltd." }}</span>
							{{-- All rights reserved. --}}
						</div>
						<!--end::Copyright-->
					</div>
					<!--end::Container-->
				</div>
				<!--end::Footer-->

			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Page-->
	</div>
	<!--end::Main-->


	<script>var hostUrl = "assets/";</script>
	<!--begin::Javascript-->
	<!-- Global Metronic JS Bundle -->
	<script src="{{ asset('dist/assets/plugins/global/plugins.bundle.js') }}"></script>
	<script src="{{ asset('dist/assets/js/scripts.bundle.js') }}"></script>

	<!--  DataTables (AFTER jQuery from Metronic) -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
	<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

	<!--end::Global Javascript Bundle-->
	<!--begin::Page Vendors Javascript(used by this page)-->
	<script src="{{ asset('dist/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
	<!--end::Page Vendors Javascript-->
	<!--begin::Page Custom Javascript(used by this page)-->
	<script src="{{ asset('dist/assets/js/custom/widgets.js') }}"></script>
	<script src="{{ asset('dist/assets/js/custom/apps/chat/chat.js') }}"></script>
	<script src="{{ asset('dist/assets/js/custom/modals/create-app.js') }}"></script>
	<script src="{{ asset('dist/assets/js/custom/modals/upgrade-plan.js') }}"></script>
	<script>
		const testFavicon = document.createElement('img');
		testFavicon.src = document.getElementById('favicon').href;
		testFavicon.onload = () => {
			// favicon loaded successfully, do nothing
		};
		testFavicon.onerror = () => {
			// favicon failed to load, set fallback
			document.getElementById('favicon').href = '{{ asset('elsner_favicon.svg') }}';
		};
	</script>
	<!--end::Page Custom Javascript-->
	<!--end::Javascript-->
</body>

</html>