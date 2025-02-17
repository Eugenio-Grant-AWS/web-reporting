@extends('layouts.admin')
@section('title', $breadcrumb)

@section('content')

    <div class="container-fluid">
        <div class="row align-items-baseline">
            <div class="col-xl-4">
                <div class="body-left">
                    <h6>Attentive Exposure</h6>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="body-right">
                    {{-- <div class="search-group bg-custom rounded-4">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search" class="bg-transparent border-0">
                    </div>
                    <div class="select-group bg-custom rounded-4">
                        <span class="flex-1">Sort by:</span>
                        <select class="bg-transparent border-0 form-select">
                            <option>Newest</option>
                            <option>Old</option>
                            <option>Alphabatical Order</option>
                        </select>
                    </div> --}}
                    <button id="downloadChart" class="export-btn">
                        Export Chart as Image <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>


        @if ($dataMessage)
            @component('components.no_data_message', ['message' => $dataMessage])
            @endcomponent
        @else
        <div class="mt-3 row">
            <div class="col-12">
                <div class="p-3 rounded shadow-sm select-group bg-custom">
                    <h5 class="mb-3">Apply Filters</h5>
                    <form method="GET" id="filter-form">
                        <div class="row">
                            <!-- Gender Filter -->
                            <div class="col-md-3">
                                <div class="filter-group">
                                    <label for="uniqueGender"><strong>Género</strong></label>
                                    <select name="uniqueGender[]" id="uniqueGender" class="form-select js-example-basic-multiple" multiple>
                                        @foreach($uniqueGender as $gender)
                                            <option value="{{ $gender }}">{{ $gender }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Age Filter -->
                            <div class="col-md-3">
                                <div class="filter-group">
                                    <label for="uniqueAge"><strong>Edad</strong></label>
                                    <select name="uniqueAge[]" id="uniqueAge" class="form-select js-example-basic-multiple" multiple>
                                        @foreach($uniqueAge as $age)
                                            <option value="{{ $age }}">{{ $age }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Value Filter -->
                            <!-- <div class="col-md-4">
                                <div class="filter-group">
                                    <label for="uniqueValue">Value</label>
                                    <select name="uniqueValue[]" id="uniqueValue" class="form-select js-example-basic-multiple" multiple>
                                        @foreach($uniqueValue as $value)
                                            <option value="{{ $value }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->

                            <!-- Response Service Filter -->
                            <!-- <div class="mt-3 col-md-4">
                                <div class="filter-group">
                                    <label for="uniqueRespoSer">Response Service</label>
                                    <select name="uniqueRespoSer[]" id="uniqueRespoSer" class="form-select js-example-basic-multiple" multiple>
                                        @foreach($uniqueRespoSer as $respoSer)
                                            <option value="{{ $respoSer }}">{{ $respoSer }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> -->
                            <!-- Security Quote Filter -->
                            <div class="col-md-3">
                                <div class="filter-group">
                                    <label for="uniqueQuoSegur"><strong>Seguro</strong> </label>
                                    <select name="uniqueQuoSegur[]" id="uniqueQuoSegur" class="form-select js-example-basic-multiple" multiple>
                                        @foreach($uniqueQuoSegur as $quoSegur)
                                            <option value="{{ $quoSegur }}">{{ $quoSegur }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Aplicar Button -->
                            <div class="mt-3 col-md-3">
                                <button type="submit" class="btn btn-primary">Aplicar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
            <!-- Chart Container -->
            <div id="chart" class="mt-3"></div>
        @endif
    </div>

@endsection

@section('scripts')
    <!-- Include jQuery, ApexCharts, and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Select2 for all multi-select fields
            $('.js-example-basic-multiple').select2({
                placeholder: "Seleccionar",
                width: '100%'
            });
        });

        // On initial page load, render the chart if chartData exists
        var chartData = @json($chartData);
        var chart; // Global chart variable

        function renderChart(chartData) {
            var options = {
                series: [{
                    name: 'Attentive Exposure',
                    data: chartData.percentages
                }],
                chart: {
                    type: 'bar',
                    height: 1200,
                    width: 1000,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        barHeight: '70%',
                        dataLabels: {
                            position: 'top',
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val + '%';
                    },
                    offsetX: 24,
                    style: {
                        fontSize: '12px',
                        colors: ['#000']
                    }
                },
                xaxis: {
                    categories: chartData.categories,
                    labels: {
                        show: false
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '14px'
                        }
                    }
                },
                title: {
                    text: 'Attentive Exposure',
                    align: 'center',
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold'
                    }
                },
                colors: ['#6a5acd'],
                tooltip: {
                    enabled: true,
                    y: {
                        formatter: function (val) {
                            return val + '%';
                        }
                    }
                }
            };

            // Destroy previous chart instance if exists
            if (chart) {
                chart.destroy();
            }
            chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        }

        // Render chart on page load if chartData is provided
        if(chartData && chartData.categories.length > 0){
            renderChart(chartData);
        }

        // Download chart as image functionality
        document.getElementById('downloadChart').addEventListener('click', function () {
            if(chart) {
                chart.dataURI().then(function (uri) {
                    var link = document.createElement('a');
                    link.href = uri.imgURI;
                    link.download = 'attentive_exposure_chart.png';
                    link.click();
                });
            }
        });

        // Intercept form submission and Aplicar via AJAX
        $(document).ready(function () {
            $('#filter-form').on('submit', function (e) {
                e.preventDefault();  // Prevent page reload
                console.log('Form submission intercepted');
                var formData = $(this).serialize();
                $.ajax({
                    url: '{{ route("attentive-exposure") }}',
                    method: 'GET',
                    data: formData,
                    success: function (response) {
                        console.log('AJAX success', response);
                        if(response.chartData) {
                            renderChart(response.chartData);
                        } else {
                            alert("No data available");
                        }
                        // Optionally, handle dataMessage here if needed.
                    },
                    error: function () {
                        alert('Error fetching filtered data');
                    }
                });
            });
        });
    </script>

    <style>
        /* Optional: Hide default DataTable search if needed */
        div.dt-container div.dt-layout-row:has(.dt-search) {
            display: none !important;
        }
    </style>
@endsection
