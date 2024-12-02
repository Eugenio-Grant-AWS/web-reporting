@extends('layouts.admin')

@section('breadcrumb', $breadcrumb)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="body-left">

                    <h6>Unduplicated Net Reach</h6>
                </div>
            </div>
            <div class="col-md-6">
                <div class="gap-3 d-flex align-items-center justify-content-end">
                    <div class="p-2 search-group bg-custom">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search" class="bg-transparent border-0">
                    </div>
                    <div class="select-group bg-custom py-3 px-3 rounded-4 mt-lg-0 mt-3 ">
                        <span>Sort by:</span>
                        <select class="border-0 bg-transparent">
                            <option>Newest </option>
                            <option>Old </option>
                            <option>Alphabatical Order</option>
                        </select>
                    </div>
                </div>



            </div>
            <div class="bg-white rounded-lg ">
                <h5 class="mb-4 text-lg font-medium text-gray-700">Sales Data for the Year</h5>

                <button id="export-chart-btn" class="btn btn-primary">
                    Export Chart as Image
                </button>

                <div style="width:75%;">
                    <x-chartjs-component :chart="$chart" id="lineChart" />
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- Add Chart.js if not already included -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const exportBtn = document.getElementById('export-chart-btn');
        const chartConvas = document.getElementById('lineChart').getElementsByTagName('canvas')[0];

        exportBtn.addEventListener('click', function() {

            if (chartCanvas) {
                // Get the chart's image in base64 format
                const imageUrl = chartCanvas.toDataURL('image/png');

                // Create a temporary download link
                const downloadLink = document.createElement('a');
                downloadLink.href = imageUrl;
                downloadLink.download = 'chart-image.png';

                // Trigger the download
                downloadLink.click();
            }
        });
    })
</script>
