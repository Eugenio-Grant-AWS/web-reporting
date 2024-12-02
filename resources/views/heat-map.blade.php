@extends('layouts.admin')
@section('title', $breadcrumb)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="body-left">
                    <h6>Attentive Exposure</h6>
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
                        <select>
                            <option>Newest</option>
                            <option>Old</option>
                            <option>Alphabetical Order</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!-- Export Button -->
        <button id="downloadChart" class="btn btn-primary">
            Export Chart as Image
        </button>

        <!-- Chart Container -->
        <div id="chart"></div>
    </div>
@endsection

@section('scripts')
    <script>
        var data = @json($data);
        var categories = @json($categories);

        var options = {
            series: data,
            chart: {
                height: 400,
                type: 'heatmap',
                toolbar: {
                    show: false
                }
            },

            dataLabels: {
                enabled: false
            },
            colors: ["#F3B415", "#F27036", "#663F59", "#6A6E94", "#4E88B4", "#00A7C6", "#18D8D8", '#A9D794',
                '#46AF78', '#A93F55', '#8C5E58', '#2176FF', '#33A1FD', '#7A918D', '#BAFF29'
            ],
            xaxis: {
                type: 'category',
                categories: categories
            },
            title: {
                text: ''
            },
            grid: {
                padding: {
                    right: 20
                }
            }
        };

        // Create chart instance
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();


        document.getElementById('downloadChart').addEventListener('click', function() {

            chart.dataURI().then(function(uri) {

                var link = document.createElement('a');
                link.href = uri.imgURI;
                link.download = 'chart.png';
                link.click();
            }).catch(function(error) {
                console.error("Error generating image URI:", error);
            });
        });
    </script>
@endsection
