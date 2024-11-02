<!DOCTYPE html>
<html lang="en">

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ config('app.name', 'Laravel') }}</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
<!-- Theme style -->
{{-- <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}"> --}}
@yield('styles')

<style>
    body {
        /* background-color: brown; */
        /* overflow:inherit; */
    }

    .wrapper {
        /* background-color: chartreuse; */
        display: flex;
        /* width: 100vw; */
        /* Full width of the viewport */
        height: 100vh;
        /* Full viewport height */
        /* overflow: hidden; Prevents page scroll */

    }

    .main-sidebar {
        background-color: rgba(77, 189, 237, 0.833);
        width: 250px;
        /* position: fixed; */
        height: auto;
        transition: width 0.8s ease;

    }

    /* Minimized Sidebar */
    .main-sidebar.minimized {
        width: 70px;
    }

    .main-sidebar.minimized .nav-item .nav-link i {
        margin: auto;
        font-size: 1.5rem;
    }

    /* Centering the Icons in Minimized View */
    .main-sidebar.minimized .nav-item .nav-link p {
        display: none;
        /* Hide text labels */
    }

    .main-sidebar.minimized .sidebar a {
        width: 70px;
    }

    .main-sidebar span {
        /* list-style: none; */
        text-decoration: none;
        font-weight: 500;
        font-size: 20px;
        color: white;
        padding: 10px;
    }

    /* Adjust font size when in column layout */
    .brand-link.column span {
        display: none;
        /* Hide the text */
    }

    .main-sidebar a.logo {
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        /* background-color: blue; */
        transition: all 0.3s ease;
        /* Transition for smooth effect */
    }

    .main-sidebar img {
        width: 30px;
        height: 30px;
    }

    .content-wrapper {
        /* uses flex: 1 to expand and occupy the remaining space on the right side of the sidebar,
             while maintaining scroll capability if the content overflows */
        /* flex: 1; */
        /* background-color: aqua; */
        padding: 20px;
        /* overflow-y: auto; */
        min-height: 0;
        /* Allow it to shrink based on content */
        flex-grow: 1;
        /* Take up the remaining space */
        /* margin-left: 250px; */
        transition: margin-left 0.5s ease;
        margin-bottom: 4px;

    }

    .content-wrapper.minimized {
        margin-left: 70px;
        /* Minimized sidebar width */
    }

    /* Class to switch flex-direction to column */
    .logo.column {
        flex-direction: column;
        padding-bottom: 10px;
        padding-top: 10px;

    }
</style>

</head>

<body class="">
    <!-- Navbar -->
    <nav
        class="main-header navbar navbar-expand navbar-white navbar-light sticky-top d-flex justify-content-between bg-info px-5">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" id="toggle-sidebar" data-widget="pushmenu" href="#" role="button"><i
                        class="fas fa-bars"></i></a>
            </li>
        </ul>

        <div class="d-flex justify-content-between align-items-center text-secondary ">

            <!-- Messages Icon with Dropdown -->
            @include('components.messages_notifications', [
                'unreadNotifications' => $unreadNotifications,
                'notifications' => $notifications,
            ])
            <!-- End Messages Dropdown Items -->

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle fw-semibold" data-bs-toggle="dropdown" href="#"
                        aria-expanded="false">
                        {{ Auth::user()->name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" style="left: inherit; right: 0px;">
                        <a href="{{ route('profile.show') }}" class="dropdown-item">
                            <i class="mr-2 fas fa-file"></i>
                            {{ __('My profile') }}
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" class="dropdown-item"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="mr-2 fas fa-sign-out-alt"></i>
                                {{ __('Log Out') }}
                            </a>
                        </form>
                    </div>
                </li>
            </ul>

        </div>
    </nav>
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <div class="wrapper">
        <aside class="main-sidebar" id="sidebar">
            <!-- Brand Logo -->
            <a href="/" class="brand-link logo" id="brand-link">
                <img src="{{ asset('images/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle">
                <span class="brand-text">VIP NOTES</span>
            </a>

            <div>
                @include('layouts.navigation')
            </div>
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="container content-wrapper " id="content-wrapper">
            @yield('content')
        </div>
    </div>
    <!-- REQUIRED SCRIPTS -->
    <!-- jQuery (Make sure you only load one version) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Popper.js (Required for dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>

    <!-- Bootstrap JS (Ensure only one version of Bootstrap is loaded) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    <!-- AdminLTE App -->
    {{-- <script src="{{ asset('js/adminlte.min.js') }}" defer></script> --}}
    <script>
        // JavaScript to toggle sidebar visibility
        document.getElementById("toggle-sidebar").addEventListener("click", function() {
            // Toggle the 'minimized' class for both sidebar and content-wrapper
            const sidebar = document.getElementById("sidebar").classList.toggle("minimized");
            // document.getElementById("content-wrapper").classList.toggle("minimized");
            // Toggle column layout for brand link
            const brandLink = document.getElementById("brand-link").classList.toggle("column");

            // Toggle 'minimized' class and store state in localStorage
            sidebar.classList.toggle("minimized");
            brandLink.classList.toggle("column");

        });

        // Function to toggle sidebar automatically based on window width
        function autoToggleSidebar() {
            if (window.innerWidth < 1034) { // Adjust the breakpoint as needed
                document.getElementById("sidebar").classList.add("minimized");
                document.getElementById("brand-link").classList.add("column");
            } else {
                document.getElementById("sidebar").classList.remove("minimized");
                document.getElementById("brand-link").classList.remove("column");
            }
        }

        // Call the autoToggleSidebar function on load and resize events
        window.addEventListener("resize", autoToggleSidebar);
        window.addEventListener("load", autoToggleSidebar);
    </script>

    @yield('scripts')

</body>

</html>
