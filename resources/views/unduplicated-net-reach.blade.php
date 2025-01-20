@extends('layouts.admin')

@section('breadcrumb', $breadcrumb)

@section('content')

    <div class="container-fluid">
        <div class="row align-items-baseline">
            <div class="col-xl-4 ">
                <div class="body-left">
                    <h6> Unduplicated Net Reach</h6>

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
            <div class="bg-white rounded-lg">

                @if ($dataMessage)
                    @component('components.no_data_message', ['message' => $dataMessage])
                    @endcomponent
                @else
                    <div class="pt-5 d-flex justify-content-center">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xl-12">
                                    <canvas id="reachChart" width="800" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>


@endsection
<!-- Add Chart.js if not already included -->


<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartData = @json($commercialQualityData);

    if (chartData.labels.length && chartData.marginal.length && chartData.cumulative.length) {
        const data = {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Reach (%)',
                    data: chartData.cumulative,
                    type: 'line',
                    borderColor: '#FF5722',
                    borderWidth: 2,
                    fill: false,
                    pointBackgroundColor: '#FF5722',
                    pointRadius: 0,
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        color: '#000',
                        font: {
                            weight: 'bold',
                            size: 12
                        },
                        formatter: (value) => `${value.toFixed(1)}`,
                    },
                },
                {
                    label: 'Marginal',
                    data: chartData.marginal,
                    backgroundColor: '#4CAF50',
                    datalabels: {
                        anchor: 'end',
                        align: 'start',
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        formatter: (value) => value > 0 ? `${value.toFixed(1)}` : '',
                    },
                },
            ],
        };

        const ctx = document.getElementById('reachChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.dataset.label}: ${context.raw.toFixed(1)}`;
                            },
                        },
                    },
                    datalabels: {
                        display: true,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return value;
                            }
                        }
                    },
                },
            },
            plugins: [ChartDataLabels]
        });
    } else {
        console.error('Invalid chart data: Labels, marginal, or cumulative data is missing.');
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('downloadChart').addEventListener('click', function() {
            var canvas = document.querySelector('canvas');
            // Get the canvas context
            var ctx = canvas.getContext('2d');


            ctx.save();
            ctx.globalCompositeOperation =
                'destination-over';
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Now generate the image with the white background
            var image = canvas.toDataURL('image/jpg');
            var link = document.createElement('a');
            link.href = image;
            link.download = 'chart.jpg';
            link.click();

            ctx.restore();

        });
    });
});

</script>


