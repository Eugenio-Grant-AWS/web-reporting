@extends('layouts.admin')
@section('title', $breadcrumb)
@section('content')




    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="body-left">

                    <h6>Net % of Consumers Reached</h6>
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

                <button id="downloadChart" class="btn btn-primary">
                    Export Chart as Image
                </button>
                <div style="width:45%;">
                    <x-chartjs-component :chart="$chart" id="bar-chart" />
                </div>
            </div>
        </div>
    </div>

@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('downloadChart').addEventListener('click', function() {
            var canvas = document.querySelector('canvas');
            var image = canvas.toDataURL('image/png');

            var link = document.createElement('a');
            link.href = image;
            link.download = 'chart.png';
            link.click();
        });
    });
</script>
