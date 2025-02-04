@extends('layouts.admin')
@section('breadcrumb', $breadcrumb)

@section('content')
<div class="container-fluid">
    <div class="row align-items-baseline">
        <div class="col-xl-4">
            <div class="body-left">
                <h6>Reach Exposure - Probability with mean</h6>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="body-right">
                {{-- <div class="search-group bg-custom rounded-4">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search" class="bg-transparent border-0">
                </div>
                <div class="select-group bg-custom rounded-4">
                    <span class="flex-1">Sort by:</span>
                    <select class="bg-transparent border-0 form-select">
                        <option>Newest</option>
                        <option>Old</option>
                        <option>Alphabetical Order</option>
                    </select>
                </div> --}}
                <button id="downloadChart" class="export-btn">
                    Export Chart as Image <i class="fas fa-download"></i>
                </button>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="p-3 mt-3 rounded shadow-sm select-group bg-custom">
                    <h5 class="mb-3">Select Media Channels</h5>

                    <select class="js-example-basic-multiple" name="states[]" multiple="multiple">
                        <option value="ver_tv_senal_nacional" <?= isset($defaultSelection['ver_tv_senal_nacional']) ? 'selected' : '' ?>>ver_tv_senal_nacional</option>
                        <option value="ver_tv_cable">ver_tv_cable</option>
                        <option value="ver_tv_internet">ver_tv_internet</option>
                        <option value="escuchar_radio">escuchar_radio</option>
                        <option value="escuchar_radio_internet">escuchar_radio_internet</option>
                        <option value="leer_revista_impresa">leer_revista_impresa</option>
                        <option value="leer_revista_digital">leer_revista_digital</option>
                        <option value="leer_periodico_impreso">leer_periodico_impreso</option>

                        <option value="leer_periodico_digital">leer_periodico_digital</option>
                        <option value="leer_periodico_email">leer_periodico_email</option>
                        <option value="vallas_publicitarias">vallas_publicitarias</option>
                        <option value="centros_comerciales">centros_comerciales</option>
                        <option value="transitar_metrobuses">transitar_metrobuses</option>
                        <option value="ver_cine">ver_cine</option>
                        <option value="abrir_correos_companias">abrir_correos_companias</option>
                        <option value="entrar_sitios_web">entrar_sitios_web</option>
                        <option value="entrar_facebook">entrar_facebook</option>
                        <option value="entrar_twitter">entrar_twitter</option>
                        <option value="entrar_instagram">entrar_instagram</option>
                        <option value="entrar_youtube">entrar_youtube</option>
                        <option value="entrar_linkedin">entrar_linkedin</option>
                        <option value="entrar_whatsapp">entrar_whatsapp</option>
                        <option value="escuchar_spotify">escuchar_spotify</option>
                        <option value="ver_netflix">ver_netflix</option>
                        <option value="utilizar_mailing_list">utilizar_mailing_list</option>
                        <option value="videojuegos_celular">videojuegos_celular</option>
                        <option value="utilizar_we_transfer">utilizar_we_transfer</option>
                        <option value="utilizar_waze">utilizar_waze</option>
                        <option value="utilizar_uber">utilizar_uber</option>
                        <option value="utilizar_pedidos_ya">utilizar_pedidos_ya</option>
                        <option value="utilizar_meet">utilizar_meet</option>
                        <option value="utilizar_zoom">utilizar_zoom</option>
                        <option value="utilizar_airbnb">utilizar_airbnb</option>
                        <option value="entrar_google">entrar_google</option>
                        <option value="entrar_encuentra24">entrar_encuentra24</option>
                      </select>
                </div>
            </div>
            </div>
        </div>

        <div class="bg-white rounded-lg">
            @if ($dataMessage)
                @component('components.no_data_message', ['message' => $dataMessage])
                @endcomponent
            @else
                <div class="pt-5 d-flex justify-content-center">
                    <div class="flow-chart">
                        <div id="chart"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
<!-- CSV Upload Form -->
{{-- <div class="mt-4 csv-upload-container">
    <h5>Import CSV File</h5>
    <form action="{{ route('media-consumption.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="csv_file" class="form-label">Choose CSV file</label>
            <input type="file" name="csv_file" id="csv_file" class="form-control" required>
        </div>
        <button type="submit" class="mt-2 btn btn-primary">Import CSV</button>
    </form>
</div> --}}
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    var chartData = @json($chartData);

    if (chartData.series && chartData.categories) {
        var options = {
            series: chartData.series,
            chart: {
                type: 'bar',
                height: 1200,
                width: 1000,
                stacked: true,
                stackType: '100%',
                toolbar: { show: false }
            },
            plotOptions: { bar: { horizontal: true } },
            stroke: { width: 1, colors: ['#fff'] },
            title: { text: '' },
            xaxis: {
                categories: chartData.categories,
                labels: {
                    formatter: function (val) {
                        return val + '%';
                    }
                }
            },

            tooltip: {
                enabled: true,
                y: {
                    formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                        // Normalize the value for tooltip
                        const total = w.globals.stackedSeriesTotals[dataPointIndex];
                        const normalizedVal = (val / total) * 100;
                        return ` ${normalizedVal.toFixed(1)}%`;
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) {

                    return (val).toFixed(1) + '%';
                    // return val < 1 ? '< 2%' : `${val.toFixed(1)}%`;
                },
                style: {
                    fontSize: '12px',
                    colors: ['#fff']
                }
            },
            fill: { opacity: 1 },
            legend: {
                position: 'bottom',
                horizontalAlign: 'left',
                offsetX: 40
            },
            colors: chartData.colors
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    } else {
        console.error('Chart data or categories are undefined.');
    }

    document.getElementById('downloadChart').addEventListener('click', function() {
        chart.dataURI().then(function(uri) {
            var link = document.createElement('a');
            link.href = uri.imgURI;
            link.download = 'chart.png';
            link.click();
        }).catch(console.error);
    });
    // Select all matching <g> elements
    const elements = document.querySelectorAll('g.apexcharts-series[seriesName="0"]');

            // Loop through and hide each element
            elements.forEach(element => {
                element.style.display = 'none';
            });

    // Update chart dynamically when a dropdown value is selected
    $('.js-example-basic-multiple').on('change', function() {
            var selectedOptions = $(this).val();
            var data = {};
            selectedOptions.forEach(option => {
                data[option] = 1;
            });

            $.ajax({
                url: "{{ route('reach-exposure-probability-with-mean') }}",
                method: "GET",
                data: { top_row: data },
                success: function(response) {
                    chart.updateOptions({
                        series: response.commercialQualityData.datasets.Default.data
                    });
                }
            });
        });

        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: "Select an option", // Placeholder text
                width: '50%'
            });

        });

    });
</script>


@endsection
