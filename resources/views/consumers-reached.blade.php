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


    @if ($dataMessage)
    @component('components.no_data_message', ['message' => $dataMessage])
    @endcomponent
@else

    <!-- Filters Section -->
    <div class="row">
        <div class="col-12">
            <div class="p-3 mt-3 rounded shadow-sm select-group bg-custom">
                <h5 class="mb-3">Select Media Channels</h5>
                <form method="GET" id="filter-form">
                    <select class="js-example-basic-multiple" name="states[]" multiple="multiple">
                        <option value="ver_tv_senal_nacional" <?= isset($defaultSelection['ver_tv_senal_nacional']) ? 'selected' : '' ?>>ver_tv_senal_nacional</option>
                        <option value="ver_tv_cable" <?= isset($defaultSelection['ver_tv_cable']) ? 'selected' : '' ?>>ver_tv_cable</option>
                        <option value="ver_tv_internet" <?= isset($defaultSelection['ver_tv_internet']) ? 'selected' : '' ?>>ver_tv_internet</option>
                        <option value="escuchar_radio" <?= isset($defaultSelection['escuchar_radio']) ? 'selected' : '' ?>>escuchar_radio</option>
                        <option value="escuchar_radio_internet" <?= isset($defaultSelection['escuchar_radio_internet']) ? 'selected' : '' ?>>escuchar_radio_internet</option>
                        <option value="leer_revista_impresa" <?= isset($defaultSelection['leer_revista_impresa']) ? 'selected' : '' ?>>leer_revista_impresa</option>
                        <option value="leer_revista_digital" <?= isset($defaultSelection['leer_revista_digital']) ? 'selected' : '' ?>>leer_revista_digital</option>
                        <option value="leer_periodico_impreso" <?= isset($defaultSelection['leer_periodico_impreso']) ? 'selected' : '' ?>>leer_periodico_impreso</option>
                        <option value="leer_periodico_digital" <?= isset($defaultSelection['leer_periodico_digital']) ? 'selected' : '' ?>>leer_periodico_digital</option>
                        <option value="leer_periodico_email" <?= isset($defaultSelection['leer_periodico_email']) ? 'selected' : '' ?>>leer_periodico_email</option>
                        <option value="vallas_publicitarias" <?= isset($defaultSelection['vallas_publicitarias']) ? 'selected' : '' ?>>vallas_publicitarias</option>
                        <option value="centros_comerciales" <?= isset($defaultSelection['centros_comerciales']) ? 'selected' : '' ?>>centros_comerciales</option>
                        <option value="transitar_metrobuses" <?= isset($defaultSelection['transitar_metrobuses']) ? 'selected' : '' ?>>transitar_metrobuses</option>
                        <option value="ver_cine" <?= isset($defaultSelection['ver_cine']) ? 'selected' : '' ?>>ver_cine</option>
                        <option value="abrir_correos_companias" <?= isset($defaultSelection['abrir_correos_companias']) ? 'selected' : '' ?>>abrir_correos_companias</option>
                        <option value="entrar_sitios_web" <?= isset($defaultSelection['entrar_sitios_web']) ? 'selected' : '' ?>>entrar_sitios_web</option>
                        <option value="entrar_facebook" <?= isset($defaultSelection['entrar_facebook']) ? 'selected' : '' ?>>entrar_facebook</option>
                        <option value="entrar_twitter" <?= isset($defaultSelection['entrar_twitter']) ? 'selected' : '' ?>>entrar_twitter</option>
                        <option value="entrar_instagram" <?= isset($defaultSelection['entrar_instagram']) ? 'selected' : '' ?>>entrar_instagram</option>
                        <option value="entrar_youtube" <?= isset($defaultSelection['entrar_youtube']) ? 'selected' : '' ?>>entrar_youtube</option>
                        <option value="entrar_linkedin" <?= isset($defaultSelection['entrar_linkedin']) ? 'selected' : '' ?>>entrar_linkedin</option>
                        <option value="entrar_whatsapp" <?= isset($defaultSelection['entrar_whatsapp']) ? 'selected' : '' ?>>entrar_whatsapp</option>
                        <option value="escuchar_spotify" <?= isset($defaultSelection['escuchar_spotify']) ? 'selected' : '' ?>>escuchar_spotify</option>
                        <option value="ver_netflix" <?= isset($defaultSelection['ver_netflix']) ? 'selected' : '' ?>>ver_netflix</option>
                        <option value="utilizar_mailing_list" <?= isset($defaultSelection['utilizar_mailing_list']) ? 'selected' : '' ?>>utilizar_mailing_list</option>
                        <option value="videojuegos_celular" <?= isset($defaultSelection['videojuegos_celular']) ? 'selected' : '' ?>>videojuegos_celular</option>
                        <option value="utilizar_we_transfer" <?= isset($defaultSelection['utilizar_we_transfer']) ? 'selected' : '' ?>>utilizar_we_transfer</option>
                        <option value="utilizar_waze" <?= isset($defaultSelection['utilizar_waze']) ? 'selected' : '' ?>>utilizar_waze</option>
                        <option value="utilizar_uber" <?= isset($defaultSelection['utilizar_uber']) ? 'selected' : '' ?>>utilizar_uber</option>
                        <option value="utilizar_pedidos_ya" <?= isset($defaultSelection['utilizar_pedidos_ya']) ? 'selected' : '' ?>>utilizar_pedidos_ya</option>
                        <option value="utilizar_meet" <?= isset($defaultSelection['utilizar_meet']) ? 'selected' : '' ?>>utilizar_meet</option>
                        <option value="utilizar_zoom" <?= isset($defaultSelection['utilizar_zoom']) ? 'selected' : '' ?>>utilizar_zoom</option>
                        <option value="utilizar_airbnb" <?= isset($defaultSelection['utilizar_airbnb']) ? 'selected' : '' ?>>utilizar_airbnb</option>
                        <option value="entrar_google" <?= isset($defaultSelection['entrar_google']) ? 'selected' : '' ?>>entrar_google</option>
                        <option value="entrar_encuentra24" <?= isset($defaultSelection['entrar_encuentra24']) ? 'selected' : '' ?>>entrar_encuentra24</option>
                        <option value="entrar_tiktok" <?= isset($defaultSelection['entrar_tiktok']) ? 'selected' : '' ?>>entrar_tiktok</option>
                        <option value="ir_a_sucursales_aseguradoras" <?= isset($defaultSelection['ir_a_sucursales_aseguradoras']) ? 'selected' : '' ?>>Ir a Sucursales de las aseguradoras</option>
                    </select>
                </form>

            </div>
        </div>
    </div>



    <div class="mt-3 row">
        <div class="col-12">
            <div class="p-3 rounded shadow-sm select-group bg-custom">
                <h5 class="mb-3">Apply Filters</h5>
                <div class="row">
                    <!-- QuotGene Filter -->
                    <div class="mb-3 col-sm-3">
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
                    <div class="mb-3 col-sm-3">
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
                    <div class="mb-3 col-sm-3">
                        <div class="filter-group">
                            <label for="quosegur"><strong>Seguro</strong></label>
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
                    <div class="mt-3 col-3 ">
                        <button type="button" class="btn btn-primary" id="applyFiltersBtn">Aplicar</button>
                    </div>
            </div>
            </div>
        </div>
    </div>



    <!-- Chart Section -->
    <div class="bg-white rounded-lg">
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
