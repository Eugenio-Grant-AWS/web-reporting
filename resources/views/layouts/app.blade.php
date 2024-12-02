<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">

    <title>{{ config('app.name', 'Web') }}</title>

    <style>
        /* Remove the color from nav-link and inherit the color from its parent */
        .nav-link {
            display: block;
            padding: .5rem 1rem;
            color: inherit;
            /* Inherit color from parent (e.g., nav-item) */
            text-decoration: none;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out;
        }

        /* Override active state color for nav-link */
        .sidebar .nav-item.active .nav-link {
            background-color: blue;
            color: white !important;
            border-radius: 5px;
            /* Ensure text is white when active */
        }

        /* Optionally, you can also customize the hover or focus state */
        .nav-link:hover,
        .nav-link:focus {
            color: inherit;
            /* Keep color inheritance on hover/focus */
        }
    </style>
    

 
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">
    <!-- Header -->
    {{-- @include('partials.header') <!-- Your updated header here -->

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        @include('partials.sidebar') <!-- Sidebar on the left -->

        <!-- Main content area -->
        <div class="flex-1 p-6 bg-gray-100">
            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
    </div> --}}
    
    
        
            @yield('content')
        
  

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/font-awesome.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

</body>





</html>
