@extends('layouts.admin')
@section('title', $breadcrumb)
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="body-left">

                    <h6>Net Reach</h6>
                </div>
            </div>
            <div class="col-md-6">
                <div class="gap-3 d-flex align-items-center justify-content-end">
                    <div class="p-2 search-group bg-custom">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search" class="bg-transparent border-0">
                    </div>
                    <div class="px-3 py-3 mt-3 select-group bg-custom rounded-4 mt-lg-0 ">
                        <span>Sort by:</span>
                        <select class="bg-transparent border-0">
                            <option>Newest </option>
                            <option>Old </option>
                            <option>Alphabatical Order</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg ">

                <button id="downloadChart" class="btn btn-primary">
                    Export Chart as Image
                </button>
                <div style="width:75%;">
                    <canvas id="vennChart" width="200" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('vennChart').getContext('2d');

            const config = {
                type: 'venn',
                data: {
                    labels: @json($chartData['labels']), // Pass labels dynamically
                    datasets: [{
                        label: 'Sports',
                        data: @json($chartData['datasets'][0]['data']), // Pass dataset dynamically
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                    },
                }
            };

            const vennChart = new Chart(ctx, config);

            document.getElementById('downloadChart').addEventListener('click', function() {
                const imageUrl = vennChart.toBase64Image();

                const link = document.createElement('a');
                link.href = imageUrl;
                link.download = 'venn-chart.png';
                link.click();
            });
        });
    </script>
@endsection
