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
          <!-- Filter Section -->
    <form id="filter-form" class="mb-3">
        <div class="row">
            @foreach ($distinctValues as $key => $values)
                <div class="col-md-3 mb-3">
                <strong>{{ $filterLabels[$key] ?? ucwords(str_replace('unique', '', $key)) }}</strong>
                    <select name="{{ $key }}[]" id="{{ $key }}" class="form-select js-multiple-filter" multiple>
                        @foreach ($values as $value)
                            @if($key === 'uniqueMediaType')
                                @php $cleanValue = trim($value); @endphp
                                <option value="{{ $cleanValue }}">{{ $mediaTypeMapping[$cleanValue] ?? $cleanValue }}</option>
                            @else
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            @endforeach
            <div class="col-md-3 align-self-end mb-3">
                <button type="submit" class="btn btn-primary">Aplicar</button>
            </div>
        </div>
    </form>
    </div>
  

    <!-- Top Row: Title, Search, Sort, Export (unchanged) -->
  

    <!-- Chart Container -->
    <div class="bg-white rounded-lg">
        @if ($dataMessage)
            @component('components.no_data_message', ['message' => $dataMessage])
            @endcomponent
        @else
            <div class="pt-5 d-flex justify-content-center">
                <div class="flow-chart">
                    <div id="chart"></div> <!-- Chart will be rendered here -->
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<!-- Include necessary libraries -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- Include ApexCharts if not already included in your layout -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Initialize chart with initial data from PHP.
        var initialChartData = @json($chartData);
        var options = {
            series: [{
                name: 'Reach x Attention',
                data: initialChartData.map(item => ({ x: item.x, y: item.y, label: item.label }))
            }],
            chart: {
                type: 'scatter',
                height: 700,
                width: 1000,
                toolbar: { show: false },
                zoom: { enabled: true }
            },
            xaxis: {
                title: { text: 'Reach Exposure/Probability', style: { color: '#D4421D', fontSize: '14px' } },
                labels: { formatter: val => val.toFixed(0) + '%' },
                tickAmount: 12,
                min: 0,
                max: 120,
                axisBorder: { show: true },
                axisTicks: { show: true }
            },
            yaxis: {
                title: { text: 'Attention Touchpoints (Occasional or More Frequent)', style: { color: '#1D4CD4', fontSize: '14px' } },
                labels: { formatter: val => val.toFixed(0) + '%' },
                tickAmount: 20,
                min: 0,
                max: 100,
                axisBorder: { show: true },
                axisTicks: { show: true }
            },
            grid: {
                show: true,
                xaxis: { lines: { show: false } },
                yaxis: { lines: { show: false } }
            },
            tooltip: {
                enabled: true,
                y: { formatter: val => val.toFixed(1) + '%' },
                x: { formatter: val => val.toFixed(1) + '%' }
            },
            annotations: {
                points: initialChartData.map(point => ({
                    x: point.x,
                    y: point.y,
                    marker: { size: 6, fillColor: '#FF0000' },
                    label: {
                        text: point.label,
                        offsetX: 10,
                        offsetY: -10,
                        style: { fontSize: '12px', background: '#fff', padding: 4, borderRadius: '4px', border: '1px solid #ccc' }
                    }
                }))
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        // Download chart as image functionality
        document.getElementById('downloadChart').addEventListener('click', function () {
            chart.dataURI().then(function (uri) {
                var link = document.createElement('a');
                link.href = uri.imgURI;
                link.download = 'attentive_exposure_chart.png';
                link.click();
            });
        });

        // Initialize Select2 on all filter dropdowns
        $('.js-multiple-filter').select2({
            placeholder: "Seleccionar",
            allowClear: true,
            width: "100%"
        });

        // AJAX submission for the filter form (prevents page reload)
        $('#filter-form').on('submit', function (e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                url: "{{ route('reach-attention-plot') }}", // Adjust the route if needed
                type: "GET",
                data: formData,
                success: function (response) {
                    // Update chart series with the new data from the response
                    var newChartData = response.chartData;
                    chart.updateSeries([{
                        name: 'Reach x Attention',
                        data: newChartData.map(item => ({ x: item.x, y: item.y, label: item.label }))
                    }]);
                    // Update annotations if needed
                    chart.updateOptions({
                        annotations: {
                            points: newChartData.map(point => ({
                                x: point.x,
                                y: point.y,
                                marker: { size: 6, fillColor: '#FF0000' },
                                label: {
                                    text: point.label,
                                    offsetX: 10,
                                    offsetY: -10,
                                    style: { fontSize: '12px', background: '#fff', padding: 4, borderRadius: '4px', border: '1px solid #ccc' }
                                }
                            }))
                        }
                    });
                },
                error: function () {
                    alert("Failed to Aplicar. Please try again.");
                }
            });
        });
    });
</script>
@endsection
