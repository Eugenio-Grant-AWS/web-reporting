@extends('layouts.admin')
@section('title', $breadcrumb)
@section('content')


    <div class="container-fluid">
        <div class="row align-items-baseline">
            <div class="col-xl-4 ">
                <div class="body-left">
                    <h6>Indexed Review of Stronger Drivers</h6>
                </div>
            </div>
            <div class="col-xl-8 ">
                <div class="body-right">
                    <div class="search-group bg-custom rounded-4">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search" class="bg-transparent border-0">
                    </div>
                    <div class="select-group bg-custom rounded-4">
                        <span class="flex-1">Sort by:</span>
                        <select class="form-select  bg-transparent border-0">
                            <option>Newest</option>
                            <option>Old</option>
                            <option>Alphabetical Order</option>
                        </select>
                    </div>
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
            <div id="chart" class="mt-3"></div>
        @endif
    </div>


@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // The data is now formatted as an array of objects for ApexCharts
            var data = @json($commercialQualityData).map(function(item) {
                return {
                    x: item[0],
                    y: item[1]
                };
            });

            var options = {
                series: [{
                    name: 'Tool Prices',
                    data: data
                }],
                chart: {
                    type: 'area', // Type of chart (area chart)
                    stacked: false,
                    height: 500,
                    zoom: {
                        type: 'x',
                        enabled: false,
                        autoScaleYaxis: false
                    },
                    toolbar: {
                        autoSelected: 'zoom',
                        show: false
                    },

                },
                dataLabels: {
                    enabled: false
                },
                markers: {
                    size: 6, // Adjust the size of the points (markers)

                    strokeColor: '#ffffff', // Optional: add white stroke to the points
                    strokeWidth: 2
                },
                markers: {
                    size: 5,
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        inverseColors: false,
                        opacityFrom: 0.5,
                        opacityTo: 0,
                        stops: [0, 90, 100],
                        colorStops: [{
                            offset: 0,
                            color: '#66ba69', // Shadow color
                            opacity: 0.2
                        }]
                    },
                },
                stroke: {
                    curve: 'smooth', // Smooth line curve
                    width: 3, // Line width
                    colors: ['#e1af50'] // Line color
                },
                yaxis: {
                    min: 0, // Start the y-axis from 0
                    max: 150,
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(0); // No need for the million conversion
                        },
                    },
                    title: {
                        text: 'Price'
                    },
                },
                xaxis: {

                    type: 'category', // Set x-axis to 'category' for tool names
                    categories: @json(array_column($commercialQualityData, 0)), // Tool names for X-axis labels
                },
                tooltip: {
                    shared: false,
                    y: {
                        formatter: function(val) {
                            return val.toFixed(0); // Display price as is
                        }
                    }
                },



            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

            document.getElementById('downloadChart').addEventListener('click', function() {
                chart.dataURI().then(function(uri) {
                    var link = document.createElement('a');
                    link.href = uri.imgURI;
                    link.download = 'chart.png';
                    link.click();
                }).catch(function(error) {
                    console.error("Error generating image URI:", error);
                });
            });

        });
    </script>
@endsection
