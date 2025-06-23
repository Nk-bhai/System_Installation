<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'System Installation')</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- SEO -->
    <meta name="description" content="Admin Dashboard - Responsive and Fast." />
    <meta name="keywords" content="Admin, Bootstrap, Laravel, Metronic, Responsive" />

    <!-- Open Graph -->
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Metronic Admin Dashboard" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Keenthemes | Metronic" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('elsner_favicon.svg') }}" type="image/x-icon" />
    <link rel="canonical" href="https://preview.keenthemes.com/metronic8" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet" />

    <!-- Global Stylesheets -->
    <link href="{{ asset('dist/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/assets/css/style.bundle.css') }}" rel="stylesheet" />

    <!-- JQuery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Custom CSS -->
    <style>
        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-wrapper input {
            padding-right: 40px;
            width: 100%;
        }

        .password-toggle-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #7e8299;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .password-toggle-icon:hover {
            color: #009ef7;
        }

        .content {
            flex: 1 0 auto;
            padding: 1.5rem;
        }

        .footer {
            flex-shrink: 0;
            text-align: center;
        }
 
        
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Page Content Wrapper -->
    <div class="d-flex flex-column flex-grow-1">
        <!-- Main Page Section -->
        <main class="flex-grow-1">
            @yield('contents')
        </main>

        <!-- Footer -->
        <footer class="footer py-4" id="kt_footer">
            <div class="container-fluid text-center">
                <span class="text-muted fw-bold">© {{ date('Y') }} Elsner Technologies Pvt. Ltd. All rights reserved.</span>
            </div>
        </footer>
    </div>

</body>

</html>
