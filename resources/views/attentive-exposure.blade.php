@extends('layouts.admin')
@section('title', $breadcrumb)

@section('content')


    <div class="container-fluid">
        <div class="row align-items-baseline">
            <div class="col-xl-4 ">
                <div class="body-left">
                    <h6>Attentive Exposure</h6>

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
        </div>

        @if ($dataMessage)
            @component('components.no_data_message', ['message' => $dataMessage])
            @endcomponent
        @else
            <!-- Chart Container -->
            <div id="chart" class="mt-3"></div>
        @endif
    </div>
@endsection


@section('scripts')
    <script>
        var options = {
            series: @json($data),
            chart: {
                height: 500,
                type: 'heatmap',
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: false
            },
            colors: ["#F3B415", "#F27036", "#663F59", "#6A6E94", "#4E88B4", "#00A7C6", "#18D8D8", "#A9D794", "#46AF78",
                "#A93F55", "#8C5E58", "#2176FF", "#33A1FD", "#7A918D", "#BAFF29"
            ],
            xaxis: {
                type: 'category',
                categories: @json($categories)
            },
            yaxis: {
                show: true
            },
            title: {
                text: ''
            },
            grid: {
                padding: {
                    right: 20
                }
            },
            responsive: [{
                breakpoint: 1200,
                options: {
                    chart: {
                        height: 350
                    }
                }
            }]
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        // Create Gradient Bar
        var gradientContainer = document.createElement('div');
        gradientContainer.style.cssText =
            'height:50px;background:linear-gradient(to right, #F3B415, #F27036, #663F59, #6A6E94, #4E88B4, #00A7C6, #18D8D8, #A9D794, #46AF78, #A93F55, #8C5E58, #2176FF, #33A1FD, #7A918D, #BAFF29);margin-top:20px;margin-left:70px;border-radius:8px;width:40%';

        // Create Labels for Gradient with Color Matching
        var labelContainer = document.createElement('div');
        labelContainer.style.cssText =
            'font-size:12px;margin-top:10px;margin-left:70px;width:40%;display:flex;justify-content:space-between;';

        // Colors for the percentage labels, matching the gradient colors
        var colorLabels = ["#F3B415", "#F27036", "#663F59", "#6A6E94", "#4E88B4", "#00A7C6", "#18D8D8", "#A9D794",
            "#46AF78", "#A93F55", "#8C5E58", "#2176FF", "#33A1FD", "#7A918D", "#BAFF29"
        ];

        // Create percentage labels with the corresponding color
        colorLabels.forEach(function(color, index) {
            var label = document.createElement('span');
            label.style.color = color;
            label.style.fontSize = '12px';
            label.innerText = (index * 7) + "%"; // Display 0%, 7%, 14%, ..., 100%
            labelContainer.appendChild(label);
        });

        // Append the gradient and labels to the chart
        document.querySelector("#chart").appendChild(gradientContainer);
        document.querySelector("#chart").appendChild(labelContainer);

        // Download Button
        document.getElementById('downloadChart').addEventListener('click', function() {
            chart.dataURI().then(function(uri) {
                var link = document.createElement('a');
                link.href = uri.imgURI;
                link.download = 'chart.png';
                link.click();
            }).catch(console.error);
        });
    </script>
@endsection
