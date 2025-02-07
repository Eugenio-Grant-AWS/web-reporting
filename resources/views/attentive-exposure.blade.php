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
            <div class="filter-section mb-4">
                <form method="GET" id="filter-form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="uniqueGender">Gender</label>
                                <select name="uniqueGender[]" id="uniqueGender" class="form-select js-example-basic-multiple" multiple>
                                    @foreach($uniqueGender as $gender)
                                        <option value="{{ $gender }}">{{ $gender }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="uniqueAge">Age</label>
                                <select name="uniqueAge[]" id="uniqueAge" class="form-select js-example-basic-multiple" multiple>
                                    @foreach($uniqueAge as $age)
                                        <option value="{{ $age }}">{{ $age }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="uniqueValue">Value</label>
                                <select name="uniqueValue[]" id="uniqueValue" class="form-select js-example-basic-multiple" multiple>
                                    @foreach($uniqueValue as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <div class="filter-group">
                                <label for="uniqueMediaType">Media Type</label>
                                <select name="uniqueMediaType[]" id="uniqueMediaType" class="form-select js-example-basic-multiple" multiple>
                                    @foreach($uniqueMediaType as $media)
                                        <option value="{{ $media }}">{{ $media }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <div class="filter-group">
                                <label for="uniqueRespoSer">Response Service</label>
                                <select name="uniqueRespoSer[]" id="uniqueRespoSer" class="form-select js-example-basic-multiple" multiple>
                                    @foreach($uniqueRespoSer as $respoSer)
                                        <option value="{{ $respoSer }}">{{ $respoSer }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <div class="filter-group">
                                <label for="uniqueQuoSegur">Security Quote</label>
                                <select name="uniqueQuoSegur[]" id="uniqueQuoSegur" class="form-select js-example-basic-multiple" multiple>
                                    @foreach($uniqueQuoSegur as $quoSegur)
                                        <option value="{{ $quoSegur }}">{{ $quoSegur }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <div class="filter-group">
                                <label for="uniqueReach">Reach</label>
                                <select name="uniqueReach[]" id="uniqueReach" class="form-select js-example-basic-multiple" multiple>
                                    @foreach($uniqueReach as $reach)
                                        <option value="{{ $reach }}">{{ $reach }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3">
                            <div class="filter-group">
                                <label for="uniqueattentive_exposure">Attentive Exposure</label>
                                <select name="uniqueattentive_exposure[]" id="uniqueattentive_exposure" class="form-select js-example-basic-multiple" multiple>
                                    @foreach($uniqueattentive_exposure as $attentive_exposure)
                                        <option value="{{ $attentive_exposure }}">{{ $attentive_exposure }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mt-3 text-end">
                            <button type="submit" class="btn btn-primary">Apply Filters</button>
                        </div>
                    </div>
                </form>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: "Select an option", // Placeholder text
                width: '50%'
            });
        });
    // Ensure chartData is available and valid
    var chartData = @json($chartData);


        var options = {
            series: [{
                name: 'Attentive Exposure',
                data: chartData.percentages
            }],
            chart: {
                type: 'bar',
                height: 1200,
                width: 1000,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '70%',
                    dataLabels: {
                        position: 'top',
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val + '%';
                },
                offsetX: 24,
                style: {
                    fontSize: '12px',
                    colors: ['#000'] // Set color for better visibility outside bars
                }
            },
            xaxis: {
                categories: chartData.categories,
                labels: {
                    show: false // Disable x-axis labels
                },
                axisBorder: {
                    show: false // Remove x-axis border
                },
                axisTicks: {
                    show: false // Remove x-axis ticks
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '14px'
                    }
                }
            },
            title: {
                text: 'Attentive Exposure',
                align: 'center',
                style: {
                    fontSize: '18px',
                    fontWeight: 'bold'
                }
            },
            colors: ['#6a5acd'],
            tooltip: {
                enabled: true,
                y: {
                    formatter: function (val) {
                        return val + '%';
                    }
                }
            }
        };


        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        // Functionality to download chart as image
        document.getElementById('downloadChart').addEventListener('click', function () {
            chart.dataURI().then(function (uri) {
                var link = document.createElement('a');
                link.href = uri.imgURI;
                link.download = 'attentive_exposure_chart.png';
                link.click();
            });
        });
        $(document).ready(function () {
      // Intercept form submission
      $('#filter-form').on('submit', function (e) {
            e.preventDefault();  // Prevent page reload
            console.log('Form submission intercepted');

            var formData = $(this).serialize();

            $.ajax({
                url: '{{ route("attentive-exposure") }}',
                method: 'GET',
                data: formData,
                success: function (response) {
                    console.log('AJAX success');
                    updateChart(response.chartData);

                    if (response.dataMessage) {
                        $('#no-data-message').text(response.dataMessage).show();
                    } else {
                        $('#no-data-message').hide();
                    }
                },
                error: function () {
                    alert('Error fetching filtered data');
                }
            });
        });

        // Function to update chart with new data
        function updateChart(chartData) {
            var options = {
                series: [{
                    name: 'Attentive Exposure',
                    data: chartData.percentages
                }],
                chart: {
                    type: 'bar',
                    height: 1200,
                    width: 1000,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        barHeight: '70%',
                        dataLabels: {
                            position: 'top',
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val + '%';
                    },
                    offsetX: 24,
                    style: {
                        fontSize: '12px',
                        colors: ['#000']
                    }
                },
                xaxis: {
                    categories: chartData.categories,
                    labels: {
                        show: false
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            fontSize: '14px'
                        }
                    }
                },
                title: {
                    text: 'Attentive Exposure',
                    align: 'center',
                    style: {
                        fontSize: '18px',
                        fontWeight: 'bold'
                    }
                },
                colors: ['#6a5acd'],
                tooltip: {
                    enabled: true,
                    y: {
                        formatter: function (val) {
                            return val + '%';
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        }
});

</script>


@endsection
