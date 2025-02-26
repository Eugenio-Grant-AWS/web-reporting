<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Default CSS from Laravel Users --}}
    @if(config('laravelusers.enableBootstrapCssCdn'))
        <link rel="stylesheet" type="text/css" href="{{ config('laravelusers.bootstrapCssCdn') }}">
    @endif
    @if(config('laravelusers.enableAppCss'))
        <link rel="stylesheet" type="text/css" href="{{ asset(config('laravelusers.appCssPublicFile')) }}">
    @endif
    @yield('template_linked_css')

    <link href="{{ asset('assets/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- Include GoJS from the CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gojs/2.1.0/go.js"></script>
    {{-- <title>{{ config('app.name', 'Web') }}</title> --}}
    <title>@yield('breadcrumb', config('app.name', 'Web'))</title>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include the Data Labels plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .c-pagination ul {
            list-style: none;
            padding: 0;
        }

        .c-pagination li {
            display: inline-block;
        }

        .c-pagination li a {
            text-decoration: none;
            padding: 8px 16px;
            border: 1px solid #ddd;
            color: #333;
        }

        .c-pagination li.active a {
            background-color: #007bff;
            color: #fff;
        }

        .c-pagination li.disabled a {
            color: #ccc;
            cursor: not-allowed;
        }

        .c-pagination li a:hover {
            background-color: #f0f0f0;
        }

    </style>
</head>

<body>
    <section class="overflow-hidden admin-dashboard vh-100">
        <div class="container-fluid">
            <div class="row">
                <div class="p-0 col-xl-2 col-lg-3 col-md-4 sidebar-col">
                    @include('partials.sidebar')
                </div>
                <div
                    class="h-auto p-0 overflow-y-auto col-xl-10 offset-xl-2 offset-lg-3 offset-md-4 col-lg-9 col-md-8 col-12 bgb-body">
                    @include('partials.header')
                    <div class="p-3 pt-4 m-4 bg-white body-content rounded-4 ">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </section>

    @yield('scripts')

     {{-- Default JS from Laravel Users --}}
     @if(config('laravelusers.enablejQueryCdn'))
            <script src="{{ asset(config('laravelusers.jQueryCdn')) }}"></script>
        @endif
        @if(config('laravelusers.enableBootstrapPopperJsCdn'))
            <script src="{{ asset(config('laravelusers.bootstrapPopperJsCdn')) }}"></script>
        @endif
        @if(config('laravelusers.enableBootstrapJsCdn'))
            <script src="{{ asset(config('laravelusers.bootstrapJsCdn')) }}"></script>
        @endif
        @if(config('laravelusers.enableAppJs'))
            <script src="{{ asset(config('laravelusers.appJsPublicFile')) }}"></script>
        @endif
        @include('laravelusers::scripts.toggleText')

        @yield('template_scripts')



    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/font-awesome.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-venn"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



</body>

</html>
