@extends('layouts.admin')
@section('title', $breadcrumb)

@section('content')

<div class="container-fluid">
    <div class="row align-items-baseline">
        <div class="col-xl-4">
            <div class="body-left">
                <h6>Reach x Attention Plot</h6>
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
                    <select class="bg-transparent border-0 form-select">
                        <option>Newest </option>
                        <option>Old </option>
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
                        <div id="chart"></div> <!-- This is where the chart will be rendered -->
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var chartData = @json($chartData);

        var options = {
            series: [{
                name: 'Reach x Attention',
                data: chartData.map(item => ({ x: item.x, y: item.y, label: item.label }))
            }],
            chart: {
                type: 'scatter',
                height: 700,
                width: 1000,
                toolbar: { show: false },
                zoom: { enabled: true }
            },
            xaxis: {
                title: {
                    text: 'Reach Exposure/Probability',
                    style: { color: '#D4421D', fontSize: '14px' }
                },
                labels: {
                    formatter: val => val.toFixed(0) + '%'
                },
                tickAmount: 12,
                min: 0,
                max: 120,
                axisBorder: { show: true }, // Keep x-axis line visible
                axisTicks: { show: true }   // Show x-axis ticks
            },
            yaxis: {
                title: {
                    text: 'Attention Touchpoints (Occasional or More Frequent)',
                    style: { color: '#1D4CD4', fontSize: '14px' }
                },
                labels: {
                    formatter: val => val.toFixed(0) + '%'
                },
                tickAmount: 20,
                min: 0,
                max: 100,
                axisBorder: { show: true }, // Keep y-axis line visible
                axisTicks: { show: true }   // Show y-axis ticks
            },
            grid: {
                show: true,  // Grid is enabled, but...
                xaxis: { lines: { show: false } }, // Remove inner vertical grid lines
                yaxis: { lines: { show: false } }
            },
            tooltip: {
                enabled: true,
                y: { formatter: val => val.toFixed(1) + '%' },
                x: { formatter: val => val.toFixed(1) + '%' }
            },
            annotations: {
                points: chartData.map(point => ({
                    x: point.x,
                    y: point.y,
                    marker: { size: 6, fillColor: '#FF0000' },
                    label: {
                        text: point.label,
                        offsetX: 10,
                        offsetY: -10,
                        style: {
                            fontSize: '12px',
                            background: '#fff',
                            padding: 4,
                            borderRadius: '4px',
                            border: '1px solid #ccc'
                        }
                    }
                }))
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        // Functionality to download chart as an image
        document.getElementById('downloadChart').addEventListener('click', function () {
            chart.dataURI().then(function (uri) {
                var link = document.createElement('a');
                link.href = uri.imgURI;
                link.download = 'attentive_exposure_chart.png';
                link.click();
            });
        });
    });
</script>

@endsection
