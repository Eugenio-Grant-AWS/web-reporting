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

    {{-- <title>{{ config('app.name', 'Web') }}</title> --}}
    <title>@yield('breadcrumb', config('app.name', 'Web'))</title>




    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <section class="overflow-hidden admin-dashboard vh-100">
        <div class="container-fluid">
            <div class="row">
                <div class="p-0 col-xl-2 col-lg-3 col-md-4 sidebar-col">
                    @include('partials.sidebar')
                </div>
                <div class="p-0 col-xl-10 col-lg-9 col-md-8 col-12 bg-custom ">
                    @include('partials.header')
                    <div class="p-1 pt-2 m-4 bg-white body-content h-100 rounded-4">
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-chart-venn"></script>

</body>

</html>
