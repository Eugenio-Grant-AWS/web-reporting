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
        console.log("Chart Data:", chartData);

        const labels = chartData.labels || [];
        const datasets = chartData.datasets[0].data || [];
        const backgroundColor = chartData.datasets[0].backgroundColor;

        // Parse dataset percentages as numeric values
        const datasetValues = datasets.map(d => {
            // Remove the '%' and convert to number
            return parseFloat(d.percentage.replace('%', ''));
        });
        console.log("Dataset Values (Numeric):", datasetValues);

        // Calculate the total value of the dataset
        const totalValue = datasetValues.reduce((acc, val) => acc + val, 0);

        // Calculate percentages based on totalValue (optional, you can use the dataset values directly too)
        const datasetPercentages = datasetValues.map(value => ((value / totalValue) * 100).toFixed(2) + '%');
        console.log("Dataset Percentages:", datasetPercentages);

        const ctx = document.getElementById('vennChart').getContext('2d');

        const config = {
            type: 'venn',  // Using the Venn diagram chart type
            data: {
                labels: labels,
                datasets: [{
                    label: 'Net Reach',
                    data: datasetValues,  // Use numeric values here
                    backgroundColor: backgroundColor,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `${labels[tooltipItem.dataIndex]}: ${datasetPercentages[tooltipItem.dataIndex]}`;
                            }
                        }
                    },

                    // Use the datalabels plugin to display the percentages inside the Venn diagram
                    datalabels: {
                        display: true,
                        formatter: function(value, context) {
                            // Display percentage in the Venn diagram
                            return datasetPercentages[context.dataIndex]; // Show percentage for each Venn section
                        },
                        color: 'black',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        align: 'center',
                        anchor: 'center'
                    }
                }
            },
        };
        console.log(config);

        // Create the Venn chart
        const vennChart = new Chart(ctx, config);

        // Export chart as image
        document.getElementById('downloadChart').addEventListener('click', function() {
            var canvas = document.querySelector('canvas');
            var ctx = canvas.getContext('2d');

            // Fill the canvas with a white background (only if it has transparent pixels)
            ctx.save();
            ctx.globalCompositeOperation = 'destination-over';
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Now generate the image with the white background
            var image = canvas.toDataURL('image/jpg');
            var link = document.createElement('a');
            link.href = image;
            link.download = 'chart.jpg';
            link.click();

            ctx.restore(); // Restore the canvas to its original state
        });
    });
</script>

@endsection


