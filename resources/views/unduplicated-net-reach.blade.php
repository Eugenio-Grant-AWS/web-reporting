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
    <form id="filter-form">
        <!-- Top Row Selection: Select Media Channels (Choose 4) -->
        <div class="mt-3 row">
            <div class="col-12">
                <div class="p-3 rounded shadow-sm select-group bg-custom">
                    <h5 class="mb-3">Select Media Channels</h5>
                    <select class="js-example-basic-multiple" name="top_row[]" multiple="multiple">
                        <option value="ver_tv_senal_nacional" {{ isset($selectedValues['ver_tv_senal_nacional']) ? 'selected' : '' }}>ver_tv_senal_nacional</option>
                        <option value="escuchar_radio" {{ isset($selectedValues['escuchar_radio']) ? 'selected' : '' }}>escuchar_radio</option>
                        <option value="leer_periodico_impreso" {{ isset($selectedValues['leer_periodico_impreso']) ? 'selected' : '' }}>leer_periodico_impreso</option>
                        <option value="leer_revista_impresa" {{ isset($selectedValues['leer_revista_impresa']) ? 'selected' : '' }}>leer_revista_impresa</option>
                        <option value="ver_tv_cable">ver_tv_cable</option>
                        <option value="ver_tv_internet">ver_tv_internet</option>
                        <option value="escuchar_radio_internet">escuchar_radio_internet</option>
                        <option value="leer_revista_digital">leer_revista_digital</option>
                        <option value="Tiktok">TikTok</option>
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

    <!-- Chart Container -->
    <div class="mt-3 bg-white rounded-lg">
        @if ($dataMessage)
            @component('components.no_data_message', ['message' => $dataMessage])
            @endcomponent
        @else
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
                    y: { beginAtZero: true, ticks: { callback: (value) => value } },
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
