@extends('layouts.admin')
@section('breadcrumb', $breadcrumb)

@section('content')
<div class="container-fluid">
    <div class="row align-items-baseline">
        <div class="col-xl-4">
            <div class="body-left">
                <h6>Reach Exposure - Probability with mean</h6>
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
</div>
<!-- CSV Upload Form -->
<div class="mt-4 csv-upload-container">
    <h5>Import CSV File</h5>
    <form action="{{ route('media-consumption.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="csv_file" class="form-label">Choose CSV file</label>
            <input type="file" name="csv_file" id="csv_file" class="form-control" required>
        </div>
        <button type="submit" class="mt-2 btn btn-primary">Import CSV</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    var chartData = @json($chartData);

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
                        // Normalize the value for tooltip
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
                    // return val < 1 ? '< 2%' : `${val.toFixed(1)}%`;
                },
                style: {
                    fontSize: '12px',
                    colors: ['#fff']
                }
            },
            fill: { opacity: 1 },
            legend: {
                position: 'bottom',
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

    document.getElementById('downloadChart').addEventListener('click', function() {
        chart.dataURI().then(function(uri) {
            var link = document.createElement('a');
            link.href = uri.imgURI;
            link.download = 'chart.png';
            link.click();
        }).catch(console.error);
    });
    // Select all matching <g> elements
const elements = document.querySelectorAll('g.apexcharts-series[seriesName="0"]');

// Loop through and hide each element
elements.forEach(element => {
    element.style.display = 'none';
});

</script>


@endsection
