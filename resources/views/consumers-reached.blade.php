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
                    <select class="bg-transparent border-0 form-select" id="labelSelect">
                        @foreach($commercialQualityData['datasets'] as $key => $dataset)
                            <option value="{{ $key }}">{{ $dataset['label']  ?? ucfirst(str_replace('_', ' ', $key)) }}</option>
                        @endforeach
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
<script>
    document.addEventListener("DOMContentLoaded", function() {

        var commercialQualityData = @json($commercialQualityData);

        // Set up initial chart data (from the first dataset)
        var selectedLabel = Object.keys(commercialQualityData['datasets'])[0];
        var initialData = commercialQualityData['datasets'][selectedLabel].data;

        var options = {
            chart: {
                type: 'pie',
                width: '700',
                height: '700'
            },
            labels: commercialQualityData['labels'],
            series: initialData,
            colors: ['#bf504d', '#4e81bd'],
            tooltip: {
                enabled: true,
                y: {
                    formatter: function(val) {
                        return val.toFixed(1) + '%';
                    },
                    title: {
                        formatter: (seriesName) => seriesName
                    }
                }
            },
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: true
            },
            responsive: [
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

        var chart = new ApexCharts(document.querySelector("#pieChartTest"), options);
        chart.render();

        document.getElementById('labelSelect').addEventListener('change', function(e) {
            var selectedLabel = e.target.value;
            var newData = commercialQualityData['datasets'][selectedLabel].data;

            chart.updateOptions({
                series: newData
            });
        });

        // Download chart as an image
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
