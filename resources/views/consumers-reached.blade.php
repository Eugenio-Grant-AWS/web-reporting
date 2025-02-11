@extends('layouts.admin')
@section('title', $breadcrumb)
@section('content')

@php
$defaultSelection = [
    'ver_tv_senal_nacional' => 1,
];
@endphp

<div class="container-fluid">
    <div class="row align-items-baseline">
        <div class="col-xl-4">
            <div class="body-left">
                <h6>Net % of Consumers Reached</h6>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="body-right">
                {{-- <div class="mb-3 search-group bg-custom rounded-4">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search" class="bg-transparent border-0 form-control search-input">
                </div> --}}

                <button id="downloadChart" class="mt-3 export-btn">
                    Export Chart as Image <i class="fas fa-download"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row">
        <div class="col-12">
            <div class="p-3 mt-3 rounded shadow-sm select-group bg-custom">
                <h5 class="mb-3">Select Media Channels</h5>
                <form method="GET" id="filter-form">
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
                </form>
            </div>
        </div>
    </div>



    <div class="row mt-3">
        <div class="col-12">
            <div class="p-3 rounded shadow-sm select-group bg-custom">
                <h5 class="mb-3">Apply Filters</h5>
                <div class="row">
                    <!-- QuotGene Filter -->
                    <div class="col-sm-3 mb-3">
                        <div class="filter-group">
                        <label for="uniqueGender"><strong>Género</strong></label>
                            <select name="quotgene[]" id="quotgene" class="form-select js-example-basic-multiple " multiple >
                                @foreach($quotgeneValues as $quotgene)
                                    <option value="{{ $quotgene }}">
                                        @if($quotgene == 1)
                                        Femenino
                                        @elseif($quotgene == 2)
                                            Masculino
                                        @else
                                            {{ $quotgene }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- QuotEdad Filter -->
                    <div class="col-sm-3 mb-3">
                        <div class="filter-group">
                        <label for="uniqueAge"><strong>Edad</strong></label>
                            <select name="quotedad[]" id="quotedad" class="form-select js-example-basic-multiple" multiple>
                                @foreach($quotedadValues as $quotedad)
                                    <option value="{{ $quotedad }}">

                                        @if($quotedad == 2)
                                        25-34
                                        @elseif($quotedad == 3)
                                        35-44
                                        @elseif($quotedad == 4)
                                            45-54
                                        @elseif($quotedad == 5)
                                            55-65
                                        @else
                                            {{ $quotedad }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- QuoSegur Filter -->
                    <div class="col-sm-3 mb-3">
                        <div class="filter-group">
                            <label for="quosegur"><strong>Tipo Seguro</strong></label>
                            <select name="quosegur[]" id="quosegur" class="form-select js-example-basic-multiple" multiple>
                                @foreach($quosegurValues as $quosegur)
                                    <option value="{{ $quosegur }}">

                                        @if($quosegur == 1)
                                            Vida
                                        @elseif($quosegur == 2)
                                            Salud
                                        @elseif($quosegur == 3)
                                            Auto
                                        @else
                                            {{ $quosegur }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Aplicar Button -->
                    <div class="col-3 mt-3 ">
                        <button type="button" class="btn btn-primary" id="applyFiltersBtn">Aplicar</button>
                    </div>
            </div>
            </div>
        </div>
    </div>
</div>


    <!-- Chart Section -->
    <div class="bg-white rounded-lg">
        @if ($dataMessage)
            @component('components.no_data_message', ['message' => $dataMessage])
            @endcomponent
        @else
            <div class="pt-5 d-flex justify-content-center">
                <div class="flow-chart">
                    <div id="pieChartTest"></div>
                </div>
            </div>
        @endif
    </div>
</div>

@endsection

@section('scripts')

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var commercialQualityData = @json($commercialQualityData);

        var options = {
            chart: {
                type: 'pie',
                width: '700',
                height: '700'
            },
            labels: commercialQualityData['labels'],
            series: commercialQualityData['datasets']['Default'].data,
            colors: ['#4e81bd', '#bf504d' ],
            tooltip: {
                enabled: true,
                y: {
                    formatter: function(val) {
                        return val.toFixed(1) + '%';
                    }
                }
            },
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: true
            },
            responsive: [
                {
                    breakpoint: 1025,
                    options: {
                        chart: { width: '500', height: '500' },
                        legend: { position: 'top' }
                    }
                },
                {
                    breakpoint: 500,
                    options: {
                        chart: { width: '300', height: '300' },
                        legend: { fontSize: '12px', position: 'bottom' }
                    }
                }
            ]
        };

        var chart = new ApexCharts(document.querySelector("#pieChartTest"), options);
        chart.render();
 // Update chart dynamically when a dropdown value is selected
 $('.js-example-basic-multiple').on('change', function() {
            var selectedOptions = $(this).val();
            var data = {};
            selectedOptions.forEach(option => {
                data[option] = 1;
            });

            $.ajax({
                url: "{{ route('net-percentage-of-consumers-reached') }}",
                method: "GET",
                data: { top_row: data },
                success: function(response) {
                    chart.updateOptions({
                        series: response.commercialQualityData.datasets.Default.data
                    });
                }
            });
        });
        // Update chart dynamically when a dropdown value is selected
        $('#applyFiltersBtn').on('click', function() {
    var quotgeneSelected = $('#quotgene').val();
    var quotedadSelected = $('#quotedad').val();
    var quosegurSelected = $('#quosegur').val();

    // Make sure to send the correct data in the AJAX request
    $.ajax({
        url: "{{ route('net-percentage-of-consumers-reached') }}",
        method: "GET",
        data: {
            quotgene: quotgeneSelected,
            quotedad: quotedadSelected,
            quosegur: quosegurSelected
        },
        success: function(response) {
            // Update the chart with the new data from the response
            chart.updateOptions({
                series: response.commercialQualityData.datasets.Default.data
            });
        },
        error: function(error) {
            console.log('Error fetching data:', error);
        }
    });
});

        // Download chart as an image
        document.getElementById('downloadChart').addEventListener('click', function() {
            chart.dataURI().then(function(uri) {
                var link = document.createElement('a');
                link.href = uri.imgURI;
                link.download = 'chart.png';
                link.click();
            });
        });

        $(document).ready(function() {
            $('.js-example-basic-multiple').select2({
                placeholder: "Seleccionar", // Placeholder text
                width: '50%'
            });
        });
    });
    $(document).ready(function() {
    // Initialize select2 for better multi-select functionality
    $('.form-select').select2({
        placeholder: "Seleccionar",  // Placeholder text
        width: '100%'  // Full width for select dropdowns
    });
});

</script>

@endsection
