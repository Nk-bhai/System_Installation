<html lang="en">
<head>
    <title>@yield('title' , 'System Installation')</title>
    <meta name="description" content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue & Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta name="keywords" content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular & Laravel Admin Dashboard Theme" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Keenthemes | Metronic" />
    	<link rel="icon" href="{{ asset('elsner_favicon.svg') }}" type="image/x-icon">
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
    {{-- @if (config('carlicense.is_valid', false)) --}}
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ asset('dist/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dist/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dist/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
     <style>
        .password-wrapper {
            position: relative;
            width: 100%; /* Ensure the wrapper takes full width of the input */
        }

        .password-wrapper input {
            padding-right: 40px; /* Space for the eye icon */
            width: 100%; /* Ensure input takes full width */
        }

        .password-toggle-icon {
            position: absolute;
            right: 15px; /* Position inside the input field */
            top: 50%; /* Vertically center the icon */
            transform: translateY(-50%);
            cursor: pointer;
            color: #7e8299; /* Matches Metronic's muted text color */
            font-size: 10.1rem; /* Slightly larger for visibility */
            transition: color 0.3s ease; /* Smooth hover transition */
        }

        .password-toggle-icon:hover {
            color: #009ef7; /* Matches Metronic's primary color for interactivity */
        }

        /* Fine-tune vertical alignment for large inputs */
        .form-control-lg ~ .password-toggle-icon {
            top: calc(50% + 2px);
        }
        
		/* .flex-root,
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
		} */
    </style>
</head>
<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
        {{-- @yield('contents') --}}
          <div class="d-flex flex-column flex-root" style="min-height: 100vh;"> 
        <div class="d-flex flex-column flex-column-fluid">

            <main class="content flex-grow-1">
                @yield('contents')
            </main>
        <!--begin::Footer-->
				<div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
					<!--begin::Container-->
					<div class="container-fluid d-flex flex-column align-items-center justify-content-center">
						<!--begin::Copyright-->
						<div class="text-dark text-center">
							<span class="text-muted fw-bold me-1">© {{ date('Y') }} Elsner Technologies Pvt. Ltd.</span>
							All rights reserved.
						</div>
						<!--end::Copyright-->
					</div>
					<!--end::Container-->
				</div>
				<!--end::Footer-->

    {{-- @endif --}}
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