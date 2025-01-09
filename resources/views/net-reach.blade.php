@extends('layouts.admin')
@section('title', $breadcrumb)
@section('content')


    <div class="container-fluid">
        <div class="row align-items-baseline">
            <div class="col-xl-4 ">
                <div class="body-left">
                    <h6>Net Reach</h6>

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
                        <select class="form-select  bg-transparent border-0">
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

            <div class="bg-white rounded-lg ">

                @if ($dataMessage)
                    @component('components.no_data_message', ['message' => $dataMessage])
                    @endcomponent
                @else
                    <div class="pt-5 d-flex justify-content-center">
                        <div class="flow-chart">
                            <canvas id="vennChart"></canvas>
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
            // Ensure chartData is available before creating chart

            const chartData = @json($chartData);

            const labels = chartData.labels || [];
            const datasets = chartData.datasets && chartData.datasets.length > 0 ? chartData.datasets[0].data : [];
            const backgroundColor = chartData.datasets[0].backgroundColor;

            const ctx = document.getElementById('vennChart').getContext('2d');

            const config = {
                type: 'euler',
                data: {
                    labels: labels,
                    datasets: [{
                        label: labels,
                        data: datasets,
                        backgroundColor: backgroundColor
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                    },
                },

            };

            const vennChart = new Chart(ctx, config);

            // Export chart as image
            document.getElementById('downloadChart').addEventListener('click', function() {
                var canvas = document.querySelector('canvas');
                // Get the canvas context
                var ctx = canvas.getContext('2d');

                // Fill the canvas with a white background (only if it has transparent pixels)
                ctx.save(); // Save the current state of the canvas
                ctx.globalCompositeOperation =
                    'destination-over'; // Ensure we don't overwrite the existing chart
                ctx.fillStyle = 'white'; // Set background color to white
                ctx.fillRect(0, 0, canvas.width, canvas.height); // Fill the entire canvas

                // Now generate the image with the white background
                var image = canvas.toDataURL('image/jpg');
                var link = document.createElement('a');
                link.href = image;
                link.download = 'chart.jpg';
                link.click();

                ctx.restore(); // Restore the canvas to its original state

            });;

        });
    </script>
@endsection
