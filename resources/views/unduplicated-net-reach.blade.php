@extends('layouts.admin')
@section('breadcrumb', $breadcrumb)
@section('content')
@php
    // Default top row selection for media channels (if not already set)
    $defaultSelection = [
        'ver_tv_senal_nacional' => 1,
        'escuchar_radio' => 1,
        'leer_periodico_impreso' => 1,
        'leer_revista_impresa' => 1,
    ];
@endphp
<div class="container-fluid">
    <!-- Top Row: Title, Search, Sort, Export -->
    <div class="row align-items-baseline">
        <div class="col-xl-4">
            <div class="body-left">
                <h6>Unduplicated Net Reach</h6>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="body-right">
                {{-- <div class="search-group bg-custom rounded-4">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search" class="bg-transparent border-0">
                </div> --}}
                <button id="downloadChart" class="export-btn">
                    Export Chart as Image <i class="fas fa-download"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Form -->


    <!-- Chart Container -->
    <div class="mt-3 bg-white rounded-lg">
        @if ($dataMessage)
            @component('components.no_data_message', ['message' => $dataMessage])
            @endcomponent
        @else
        <form id="filter-form">
            <!-- Top Row Selection: Select Media Channels (Choose 4) -->
            <div class="mt-3 row">
                <div class="col-12">
                    <div class="p-3 rounded shadow-sm select-group bg-custom">
                        <h5 class="mb-3">Select Media Channels</h5>
                        <select class="js-example-basic-multiple" name="top_row[]" multiple="multiple">
                            <option value="ver_tv_senal_nacional" {{ isset($selectedValues['ver_tv_senal_nacional']) ? 'selected' : '' }}>Ver TV nacional</option>
                            <option value="ver_tv_cable" {{ isset($selectedValues['ver_tv_cable']) ? 'selected' : '' }}>Ver TV por cable</option>
                            <option value="ver_tv_internet" {{ isset($selectedValues['ver_tv_internet']) ? 'selected' : '' }}>Ver TV por internet</option>
                            <option value="escuchar_radio" {{ isset($selectedValues['escuchar_radio']) ? 'selected' : '' }}>Escuchar Radio</option>
                            <option value="escuchar_radio_internet" {{ isset($selectedValues['escuchar_radio_internet']) ? 'selected' : '' }}>Escuchar Radio Internet</option>
                            <option value="leer_revista_impresa" {{ isset($selectedValues['leer_revista_impresa']) ? 'selected' : '' }}>Leer Revista Impresa</option>
                            <option value="leer_revista_digital" {{ isset($selectedValues['leer_revista_digital']) ? 'selected' : '' }}>Leer Revista Digital</option>
                            <option value="leer_periodico_impreso" {{ isset($selectedValues['leer_periodico_impreso']) ? 'selected' : '' }}>Leer Periódico Impreso</option>
                            <option value="leer_periodico_digital" {{ isset($selectedValues['leer_periodico_digital']) ? 'selected' : '' }}>Leer Periódico Digital</option>
                            <option value="leer_periodico_email" {{ isset($selectedValues['leer_periodico_email']) ? 'selected' : '' }}>Periódico por email</option>
                            <option value="vallas_publicitarias" {{ isset($selectedValues['vallas_publicitarias']) ? 'selected' : '' }}>Ver vallas publicitarias</option>
                            <option value="centros_comerciales" {{ isset($selectedValues['centros_comerciales']) ? 'selected' : '' }}>Visitar centros comerciales</option>
                            <option value="transitar_metrobuses" {{ isset($selectedValues['transitar_metrobuses']) ? 'selected' : '' }}>Usar metrobús</option>
                            <option value="ver_cine" {{ isset($selectedValues['ver_cine']) ? 'selected' : '' }}>Ir al cine</option>
                            <option value="entrar_sitios_web" {{ isset($selectedValues['entrar_sitios_web']) ? 'selected' : '' }}> Buscar aseguradoras en Google</option>
                            <option value="entrar_facebook" {{ isset($selectedValues['entrar_facebook']) ? 'selected' : '' }}>Usar Facebook</option>
                            <option value="entrar_twitter" {{ isset($selectedValues['entrar_twitter']) ? 'selected' : '' }}> Usar X (Twitter)</option>
                            <option value="entrar_instagram" {{ isset($selectedValues['entrar_instagram']) ? 'selected' : '' }}>Usar Instagram</option>
                            <option value="entrar_youtube" {{ isset($selectedValues['entrar_youtube']) ? 'selected' : '' }}> Usar YouTube</option>
                            <option value="entrar_linkedin" {{ isset($selectedValues['entrar_linkedin']) ? 'selected' : '' }}> Usar LinkedIn</option>
                            <option value="entrar_whatsapp" {{ isset($selectedValues['entrar_whatsapp']) ? 'selected' : '' }}> Usar WhatsApp</option>
                            <option value="ver_netflix" {{ isset($selectedValues['ver_netflix']) ? 'selected' : '' }}>Ver Netflix</option>
                            <option value="escuchar_spotify" {{ isset($selectedValues['escuchar_spotify']) ? 'selected' : '' }}>Escuchar Spotify</option>
                            <option value="entrar_tiktok" {{ isset($selectedValues['entrar_tiktok']) ? 'selected' : '' }}> Usar TikTok</option>
                            <option value="utilizar_mailing_list" {{ isset($selectedValues['utilizar_mailing_list']) ? 'selected' : '' }}>Utilizar Mailing List</option>
                            <option value="videojuegos_celular" {{ isset($selectedValues['videojuegos_celular']) ? 'selected' : '' }}>Jugar en el celular</option>
                            <option value="utilizar_uber" {{ isset($selectedValues['utilizar_uber']) ? 'selected' : '' }}>Usar Uber</option>
                            <option value="utilizar_pedidos_ya" {{ isset($selectedValues['utilizar_pedidos_ya']) ? 'selected' : '' }}>Usar PedidosYa</option>
                            <option value="utilizar_meet" {{ isset($selectedValues['utilizar_meet']) ? 'selected' : '' }}>Usar Meet</option>
                            <option value="utilizar_we_transfer" {{ isset($selectedValues['utilizar_we_transfer']) ? 'selected' : '' }}>Usar WeTransfert</option>
                            <option value="abrir_correos_companias" {{ isset($selectedValues['abrir_correos_companias']) ? 'selected' : '' }}>Usar listas de correo</option>

                            <option value="utilizar_zoom" {{ isset($selectedValues['utilizar_zoom']) ? 'selected' : '' }}>Usar Zoom</option>
                            <option value="utilizar_airbnb" {{ isset($selectedValues['utilizar_airbnb']) ? 'selected' : '' }}>Usar Airbnb</option>
                            <option value="entrar_encuentra24" {{ isset($selectedValues['entrar_encuentra24']) ? 'selected' : '' }}> Usar Encuentra24</option>
                            <option value="ir_a_sucursales_aseguradoras" {{ isset($selectedValues['ir_a_sucursales_aseguradoras']) ? 'selected' : '' }}>Visitar aseguradoras</option>
                        </select>

                    </div>
                </div>
            </div>

            <!-- Apply Filters Section -->
            <div class="mt-3 row">
                <div class="col-12">
                    <div class="p-3 rounded shadow-sm select-group bg-custom">
                        <h5 class="mb-3">Apply Filters</h5>
                        <div class="row">
                            @foreach($additionalFilterOptions as $col => $options)
                                <div class="mb-3 col-md-3">
                                    <label for="filter_{{ $col }}">
                                        <strong>{{ $filterLabelMapping[$col] ?? strtolower($col) }}</strong>
                                    </label>
                                    <select class="form-select js-additional-filter" name="filter_{{ $col }}[]" multiple="multiple" id="filter_{{ $col }}">
                                        @foreach($options as $option)
                                            <option value="{{ $option }}">
                                                {{ $optionTitleMapping[$col][$option] ?? $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </form>
            <div class="pt-5 d-flex justify-content-center">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-12">
                            <canvas id="reachChart" width="800" height="400"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<!-- Include necessary libraries -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- Include Chart.js and ChartDataLabels plugin if needed -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Initialize the chart using the initial data passed from PHP.
    const chartData = @json($commercialQualityData);
    if (chartData.labels.length && chartData.marginal.length && chartData.cumulative.length) {
        const data = {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Reach (%)',
                    data: chartData.cumulative,
                    type: 'line',
                    borderColor: '#FF5722',
                    borderWidth: 2,
                    fill: false,
                    pointBackgroundColor: '#FF5722',
                    pointRadius: 0,
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        color: '#000',
                        font: { weight: 'bold', size: 12 },
                        formatter: (value) => `${value.toFixed(2)}`
                    },
                },
                {
                    label: 'Marginal',
                    data: chartData.marginal,
                    backgroundColor: '#4CAF50',
                    datalabels: {
                        anchor: 'end',
                        align: 'start',
                        color: '#fff',
                        font: { weight: 'bold', size: 10 },
                        formatter: (value) => value > 0 ? `${value.toFixed(2)}` : ''
                    },
                },
            ],
        };

        const ctx = document.getElementById('reachChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.dataset.label}: ${context.raw.toFixed(2)}`;
                            },
                        },
                    },
                    datalabels: { display: true },
                },
                scales: {
                    y: {
                        min: 0, // Set minimum scale value to 100
                        max: 120,
                        stepSize: 10,
                         beginAtZero: true,
                         ticks: { callback: (value) => value }
                         },

                },
            },
            plugins: [ChartDataLabels]
        });

        // Attach change event to both top row and additional filter dropdowns.
        $('.js-example-basic-multiple, .js-additional-filter').on('select2:select select2:unselect', function (e) {
            // Enforce a maximum of 4 selections for top row.
            const topRowValues = $('.js-example-basic-multiple').val() || [];
            if (topRowValues.length > 10) {
                const lastSelectedValue = e.params.data.id;
                $('.js-example-basic-multiple').val(topRowValues.filter(value => value !== lastSelectedValue)).trigger('change');
                alert('You can only select up to 4 categories.');
                return;
            }
            // Serialize the entire form and send an AJAX request.
            const formData = $('#filter-form').serialize();
            $.ajax({
                url: "{{ route('unduplicated-net-reach') }}",
                method: "GET",
                data: formData,
                success: function(response) {
                    if (response.commercialQualityData) {
                        chart.data.labels = response.commercialQualityData.labels;
                        chart.data.datasets[0].data = response.commercialQualityData.cumulative;
                        chart.data.datasets[1].data = response.commercialQualityData.marginal;
                        chart.update();
                    }
                },
                error: function (xhr, status, error) {
                    const errMsg = xhr.responseJSON ? xhr.responseJSON.message : "An error occurred.";
                    alert(errMsg);
                }
            });
        });

        // Initialize Select2 on all filter dropdowns.
        $('.js-example-basic-multiple, .js-additional-filter').select2({
            placeholder: "Seleccionar",
            width: '50%'
        });
    } else {
        console.error('Invalid chart data: Labels, marginal, or cumulative data is missing.');
    }
});
</script>
@endsection
