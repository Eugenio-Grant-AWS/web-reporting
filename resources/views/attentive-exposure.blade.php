@extends('layouts.admin')
@section('title', $breadcrumb)

@section('content')


    <div class="container-fluid">
        <div class="row align-items-baseline">
            <div class="col-xl-4 ">
                <div class="body-left">
                    <h6>Attentive Exposure</h6>

                </div>
            </div>
            <div class="col-xl-8 ">
                <div class="body-right">
                    <div class=" search-group bg-custom rounded-4">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search" class="bg-transparent border-0">
                    </div>
                    <div class="select-group bg-custom rounded-4 ">
                        <span class="flex-1">Sort by:</span>
                        <select class="bg-transparent border-0 form-select">
                            <option>Newest </option>
                            <option>Old </option>
                            <option>Alphabatical Order</option>
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
            <!-- Chart Container -->
            <div id="chart" class="mt-3"></div>
        @endif
    </div>
@endsection


@section('scripts')
<script>
    // Ensure chartData is available and valid
    var chartData = @json($chartData);


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
                    colors: ['#000'] // Set color for better visibility outside bars
                }
            },
            xaxis: {
                categories: chartData.categories,
                labels: {
                    show: false // Disable x-axis labels
                },
                axisBorder: {
                    show: false // Remove x-axis border
                },
                axisTicks: {
                    show: false // Remove x-axis ticks
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


        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        // Functionality to download chart as image
        document.getElementById('downloadChart').addEventListener('click', function () {
            chart.dataURI().then(function (uri) {
                var link = document.createElement('a');
                link.href = uri.imgURI;
                link.download = 'attentive_exposure_chart.png';
                link.click();
            });
        });

</script>


@endsection
