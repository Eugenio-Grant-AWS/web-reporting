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
                    <canvas id="canvas"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
    <script>
        // document.addEventListener('DOMContentLoaded', function() {
        //     // Get the venn data from the controller
        //     var vennData = @json($vennData); // Convert the PHP array to JavaScript

        //     // Create the Venn diagram inside the 'venn' div
        //     var chart = venn.VennDiagram();
        //     d3.select("#venn").datum(vennData).call(chart);

        //     // Export chart as image
        //     var downloadButton = document.getElementById('downloadChart');

        //     if (downloadButton) {
        //         downloadButton.addEventListener('click', function() {
        //             var svg = document.querySelector('svg');
        //             if (svg) {
        //                 var data = new XMLSerializer().serializeToString(svg);
        //                 var img = new Image();
        //                 img.src = 'data:image/svg+xml;base64,' + btoa(data);
        //                 var link = document.createElement('a');
        //                 link.href = img.src;
        //                 link.download = 'venn-diagram.svg';
        //                 link.click();
        //             }
        //         });
        //     }
        // });

        const vennData = @json($vennData);

        // Extract the sets using ChartVenn
        const data = ChartVenn.extractSets(
            vennData, {
                label: 'Sports',
            }
        );

        const ctx = document.getElementById('canvas').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'venn',
            data: data,
            options: {
                title: {
                    display: true,
                    text: 'Chart.js Venn Diagram Chart',
                },
            },
        });
    </script>
@endsection
