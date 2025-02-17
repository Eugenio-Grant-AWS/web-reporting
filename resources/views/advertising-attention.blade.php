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



    <div class="bg-white rounded-lg">
        @if ($dataMessage)
            @component('components.no_data_message', ['message' => $dataMessage])
            @endcomponent
        @else
        <div class="mt-3 row">
            <div class="col-12">
                <div class="p-3 rounded shadow-sm select-group bg-custom">
                    <h5 class="mb-3">Apply Filters</h5>
                <form method="GET" id="filter-form" action="{{ route('advertising-attention-by-touchpoint') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="filter-group">
                                 <label for="uniqueGender"><strong>Género</strong></label>
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

                        <div class="col-md-3">
                            <div class="filter-group">
                            <label for="uniqueAge"><strong>Edad</strong></label>
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

                        <div class="col-md-3">
                            <div class="filter-group">
                            <label for="uniqueQuoSegur"><strong>Seguro</strong> </label>
                                <select name="uniqueQuoSegur[]" id="uniqueQuoSegur" class="js-example-basic-multiple form-select" multiple>
                                    @foreach($uniqueQuoSegur as $value)
                                        <option value="{{ $value }}"
                                            @if(in_array($value, request('uniqueQuoSegur', []))) selected @endif>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- <div class="mt-3 col-md-4">
                            <div class="filter-group">
                            <label for="uniqueMediaType">Tipo de medio</label>
                                <select name="uniqueMediaType[]" id="uniqueMediaType" class="js-example-basic-multiple form-select" multiple>
                                    @foreach($uniqueMediaType as $media)
                                        <option value="{{ $media }}"
                                            @if(in_array($media, request('uniqueMediaType', []))) selected @endif>
                                            {{ $media }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div> -->

                        <div class="mt-3 col-md-3">
                            <button type="submit" class="btn btn-primary">Aplicar</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
        </div>
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
<!-- Include jQuery, ApexCharts and (if not already included in your layout) Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- Make sure to include Select2's JS & CSS in your layout or here -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Declare chart as a global variable so it can be accessed in other functions
    let chart;

    // Function to render or re-render the chart
    function renderChart(chartData) {
        if (chartData.series && chartData.categories) {
            const options = {
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
                        formatter: function (val, { w }) {
                            const total = w.globals.stackedSeriesTotals;
                            // Note: customize the tooltip formatter as needed
                            return val;
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val.toFixed(1) + '%';
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
                colors: chartData.colors // Global color palette from controller
            };

            // If a chart already exists, destroy it before creating a new one.
            if (chart) {
                chart.destroy();
            }
            chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        } else {
            console.error('Chart data or categories are undefined.');
        }
    }

    // Handle filter form submission via AJAX to update the chart without a page reload.
    $('#filter-form').on('submit', function(event) {
        event.preventDefault(); // Prevent full page reload
        $.ajax({
            url: $(this).attr('action'),
            type: 'GET',
            data: $(this).serialize(),
            success: function(response) {
                // Re-render the chart with the updated data.
                renderChart(response.chartData);
            },
            error: function(error) {
                console.error('Error fetching data:', error);
            }
        });
    });

    // Initial rendering of the chart using server-provided data.
    const chartData = @json($chartData);
    renderChart(chartData);

    // Handle exporting the chart as an image.
    document.getElementById('downloadChart').addEventListener('click', function() {
        if(chart) {
            chart.dataURI().then(function(uri) {
                const link = document.createElement('a');
                link.href = uri.imgURI;
                link.download = 'chart.png';
                link.click();
            }).catch(console.error);
        }
    });

    // Initialize Select2 on your multiple select elements.
    $(document).ready(function() {
        $('.js-example-basic-multiple').select2({
            placeholder: "Seleccionar",
            width: '50%'
        });
    });
});
</script>
@endsection
