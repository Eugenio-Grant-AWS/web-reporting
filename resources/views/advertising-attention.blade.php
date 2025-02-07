@extends('layouts.admin')
@section('title', $breadcrumb)
@section('content')

    <div class="container-fluid">
        <div class="row align-items-baseline">
            <div class="col-xl-4">
                <div class="body-left">
                    <h6>Advertising Attention by Touchpoint</h6>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="body-right">
                    <button id="downloadChart" class="export-btn">
                        Export Chart as Image <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="row-12">
            <div class="filter-section mb-4">
                <form method="GET" id="filter-form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="uniqueGender">Gender</label>
                                <select name="uniqueGender[]" id="uniqueGender" class="js-example-basic-multiple form-select" multiple>
                                    @foreach($uniqueGender as $gender)
                                        <option value="{{ $gender }}" 
                                            @if(in_array($gender, request('uniqueGender', []))) selected @endif>
                                            {{ $gender }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="uniqueAge">Age</label>
                                <select name="uniqueAge[]" id="uniqueAge" class="js-example-basic-multiple form-select" multiple>
                                    @foreach($uniqueAge as $age)
                                        <option value="{{ $age }}"
                                            @if(in_array($age, request('uniqueAge', []))) selected @endif>
                                            {{ $age }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="uniqueValue">Value</label>
                                <select name="uniqueValue[]" id="uniqueValue" class="js-example-basic-multiple form-select" multiple>
                                    @foreach($uniqueValue as $value)
                                        <option value="{{ $value }}"
                                            @if(in_array($value, request('uniqueValue', []))) selected @endif>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <div class="filter-group">
                                <label for="uniqueMediaType">Media Type</label>
                                <select name="uniqueMediaType[]" id="uniqueMediaType" class="js-example-basic-multiple form-select" multiple>
                                    @foreach($uniqueMediaType as $media)
                                        <option value="{{ $media }}"
                                            @if(in_array($media, request('uniqueMediaType', []))) selected @endif>
                                            {{ $media }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initial chart render
        function renderChart(chartData) {
            if (chartData.series && chartData.categories) {
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
                                return ` ${normalizedVal.toFixed(1)}%`;
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            return (val).toFixed(1) + '%';
                        },
                        style: {
                            fontSize: '12px',
                            colors: ['#fff']
                        }
                    },
                    fill: { opacity: 1 },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'left',
                        offsetX: 40
                    },
                    colors: chartData.colors
                };

                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            } else {
                console.error('Chart data or categories are undefined.');
            }
        }

        // Handle form submission without page reload
        $('#filter-form').on('submit', function(event) {
            event.preventDefault(); // Prevent page reload

            $.ajax({
                url: $(this).attr('action'), // Form action URL
                type: 'GET',
                data: $(this).serialize(), // Form data
                success: function(response) {
                    // Assuming response contains the new chart data
                    renderChart(response.chartData);
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        });

        // Initial rendering of the chart with the existing data
        var chartData = @json($chartData);
        renderChart(chartData);

        document.getElementById('downloadChart').addEventListener('click', function() {
            chart.dataURI().then(function(uri) {
                var link = document.createElement('a');
                link.href = uri.imgURI;
                link.download = 'chart.png';
                link.click();
            }).catch(console.error);
        });

        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: "Select an option", // Placeholder text
                width: '50%'
            });
        });
    });
</script>
@endsection
