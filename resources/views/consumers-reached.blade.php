@extends('layouts.admin')
@section('title', $breadcrumb)
@section('content')

    <div class="container-fluid">
        <div class="row align-items-baseline">
            <div class="col-xl-4">
                <div class="body-left">
                    <h6>Net % of Consumers Reached</h6>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="body-right">
                    <div class="search-group bg-custom rounded-4">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search" class="bg-transparent border-0">
                    </div>
                    <div class="select-group bg-custom rounded-4">
                        <span class="flex-1">Sort by:</span>
                        <select class="form-select bg-transparent border-0">
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

            <div class="bg-white rounded-lg">
                @if ($dataMessage)
                    @component('components.no_data_message', ['message' => $dataMessage])
                    @endcomponent
                @else
                    <div class="pt-5 d-flex justify-content-center">
                        <div class="flow-chart">
                            <div id="pieChartTest"></div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Prepare data for ApexCharts
            var options = {
                chart: {
                    type: 'pie', 
                    width: '700',
                    height: '700'
                },
                labels: @json($commercialQualityData['labels']), // Pass labels from controller
                series: @json(array_column($commercialQualityData['datasets'], 'data')[0]), // Extract data from datasets
                tooltip: {
                    enabled: true
                },
                legend: {
                    position: 'right'
                },
                dataLabels: {
                    enabled: true
                },responsive: [
                    {
                breakpoint: 1025, 
                options: {
                    chart: {
                        width: '500',
                    height: '500'
                    },
                    legend: {
                        position: 'top'
                    }
                }
            },
            
            {
                breakpoint: 500, 
                options: {
                    chart: {
                        width: '300',
                    height: '300'
                    },
                    legend: {
                        fontSize: '12px',
                        position: 'bottom'
                    }
                }
            }
        ]
            };

            // Create the chart
            var chart = new ApexCharts(document.querySelector("#pieChartTest"), options);
            chart.render();

            // Export chart as an image
            document.getElementById('downloadChart').addEventListener('click', function() {
                chart.dataURI().then(function(uri) {
                    var link = document.createElement('a');
                    link.href = uri.imgURI;
                    link.download = 'chart.png';
                    link.click();
                });
            });
        });
    </script>
@endsection
