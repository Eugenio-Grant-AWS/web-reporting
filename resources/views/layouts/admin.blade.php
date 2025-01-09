<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- Include GoJS from the CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gojs/2.1.0/go.js"></script>
    {{-- <title>{{ config('app.name', 'Web') }}</title> --}}
    <title>@yield('breadcrumb', config('app.name', 'Web'))</title>


    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Include the Data Labels plugin -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <section class="admin-dashboard vh-100 overflow-hidden">
        <div class="container-fluid">
            <div class="row">
                <div class="p-0 col-xl-2 col-lg-3 col-md-4 sidebar-col">
                    @include('partials.sidebar')
                </div>
                <div
                    class="p-0 col-xl-10 offset-xl-2 offset-lg-3 offset-md-4 col-lg-9 col-md-8 col-12 bgb-body  h-auto overflow-y-auto">
                    @include('partials.header')
                    <div class="p-3 pt-4 m-4 bg-white body-content  rounded-4 ">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </section>


    @yield('scripts')

    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/font-awesome.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-venn"></script>

</body>

</html>
