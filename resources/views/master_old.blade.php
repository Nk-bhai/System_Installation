<html lang="en">
<head>
    <title>System Installation</title>
    <meta name="description" content="The most advanced Bootstrap Admin Theme on Themeforest trusted by 94,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue & Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta name="keywords" content="Metronic, bootstrap, bootstrap 5, Angular, VueJs, React, Laravel, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Metronic - Bootstrap 5 HTML, VueJS, React, Angular & Laravel Admin Dashboard Theme" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Keenthemes | Metronic" />
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
    </style>
</head>
<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
    {{-- @if (!config('carlicense.is_valid', false))
        <h1 class="error">Application Locked: Invalid or Revoked License</h1>
    @else --}}
        @yield('contents')
    {{-- @endif --}}
</body>
</html>