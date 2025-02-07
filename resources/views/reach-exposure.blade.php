@extends('layouts.admin')
@section('breadcrumb', $breadcrumb)

@section('content')
<div class="container-fluid">
    <div class="row align-items-baseline mb-4">
        <div class="col-xl-4">
            <div class="body-left">
                <h6>Reach Exposure - Probability with Mean</h6>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="body-right text-end">
                <button id="downloadChart" class="export-btn btn btn-primary mb-3">
                    Export Chart as Image <i class="fas fa-download"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section mb-4">
        <form method="GET" id="filter-form">
            <div class="row">
                <div class="col-md-4">
                    <div class="filter-group">
                        <label for="uniqueGender">Gender</label>
                        <select name="uniqueGender[]" id="uniqueGender" class="form-select" multiple>
                            @foreach($uniqueGender as $gender)
                                <option value="{{ $gender }}">{{ $gender }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="filter-group">
                        <label for="uniqueAge">Age</label>
                        <select name="uniqueAge[]" id="uniqueAge" class="form-select" multiple>
                            @foreach($uniqueAge as $age)
                                <option value="{{ $age }}">{{ $age }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="filter-group">
                        <label for="uniqueValue">Value</label>
                        <select name="uniqueValue[]" id="uniqueValue" class="form-select" multiple>
                            @foreach($uniqueValue as $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4 mt-3">
                    <div class="filter-group">
                        <label for="uniqueAdjusted_value">Adjusted Value</label>
                        <select name="uniqueAdjusted_value[]" id="uniqueAdjusted_value" class="form-select" multiple>
                            @foreach($uniqueAdjusted_value as $adjusted_value)
                                <option value="{{ $adjusted_value }}">{{ $adjusted_value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4 mt-3">
                    <div class="filter-group">
                        <label for="uniqueMediaType">Media Type</label>
                        <select name="uniqueMediaType[]" id="uniqueMediaType" class="form-select" multiple>
                            @foreach($uniqueMediaType as $media)
                                <option value="{{ $media }}">{{ $media }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4 mt-3 text-end">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-lg">
        @if ($dataMessage)
            @component('components.no_data_message', ['message' => $dataMessage])
            @endcomponent
        @else
            <div class="pt-5 d-flex justify-content-center">
                <div class="flow-chart">
                    <div id="chart"></div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    var chartData = @json($chartData);

    // Initial chart rendering
    var chart = null;

    function renderChart(chartData) {
        var options = {
            series: chartData.series,
            chart: {
                type: 'bar',
                height: 1200,
                width: 1000,
                stacked: true,
                stackType: '100%',
                toolbar: { show: false }
            },
            plotOptions: { bar: { horizontal: true } },
            stroke: { width: 1, colors: ['#fff'] },
            title: { text: '' },
            xaxis: {
                categories: chartData.categories,
                labels: {
                    formatter: function (val) {
                        return val + '%';
                    }
                }
            },
            tooltip: {
                enabled: true,
                y: {
                    formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                        const total = w.globals.stackedSeriesTotals[dataPointIndex];
                        const normalizedVal = (val / total) * 100;
                        return `${normalizedVal.toFixed(1)}%`;
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return (val).toFixed(1) + '%';
                },
                style: { fontSize: '12px', colors: ['#fff'] }
            },
            fill: { opacity: 1 },
            legend: { position: 'bottom', horizontalAlign: 'left', offsetX: 40 },
            colors: chartData.colors
        };

        chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    }

    renderChart(chartData);

    // Handle the form submission for filters
    $('#filter-form').submit(function(e) {
        e.preventDefault();  // Prevent form submission

        var formData = $(this).serialize();  // Collect form data

        $.ajax({
            url: "{{ route('reach-exposure-filter') }}",  // Adjust the route as needed
            method: "GET",
            data: formData,
            success: function(response) {
                var newChartData = response.chartData;

                // Update the chart with new data
                chart.updateOptions({
                    series: newChartData.series,
                    categories: newChartData.categories
                });
            }
        });
    });

    // Initialize Select2 for multi-select dropdowns
    $('select').select2({
        placeholder: "Select options",  // Placeholder text
        width: '100%'  // Make the dropdowns full width
    });
});
</script>
@endsection
