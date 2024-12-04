@extends('layouts.admin')
@section('title', $breadcrumb)
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="body-left">
                    <h6>Indexed Review of Stronger Drivers</h6>
                </div>
            </div>
            <div class="col-md-6">
                <div class="gap-3 d-flex align-items-center justify-content-end">
                    <div class="p-2 search-group bg-custom">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search" class="bg-transparent border-0">
                    </div>
                    <div class="px-3 py-3 mt-3 select-group bg-custom rounded-4 mt-lg-0">
                        <span>Sort by:</span>
                        <select class="bg-transparent border-0">
                            <option>Newest </option>
                            <option>Old </option>
                            <option>Alphabatical Order</option>
                        </select>
                    </div>
                </div>
            </div>


        </div>

        <button id="downloadChart" class="btn btn-primary">
            Export Chart as Image
        </button>
        <div id="chart"></div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var options = {
                series: [{
                    name: 'XYZ MOTORS',
                    data: @json($data) // Inject the PHP data here
                }],
                chart: {
                    type: 'area',
                    stacked: false,
                    height: 350,
                    zoom: {
                        type: 'x',
                        enabled: false,
                        autoScaleYaxis: false
                    },
                    toolbar: {
                        autoSelected: 'zoom',
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                markers: {
                    size: 0,
                },
                // title: {
                //     align: 'left'
                // },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        inverseColors: false,
                        opacityFrom: 0.5,
                        opacityTo: 0,
                        stops: [0, 90, 100]
                    },
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return (val / 1000000).toFixed(0);
                        },
                    },
                    title: {
                        text: 'Price'
                    },
                },
                xaxis: {
                    type: 'datetime',
                },
                tooltip: {
                    shared: false,
                    y: {
                        formatter: function(val) {
                            return (val / 1000000).toFixed(0)
                        }
                    }
                }
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
