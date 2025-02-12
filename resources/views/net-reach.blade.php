@extends('layouts.admin')
@section('title', $breadcrumb)
@section('content')
@php
    // Default top row selection for media channels (if not already set)
    $defaultSelection = [
        'ver_tv_senal_nacional' => 1,
        'ver_tv_cable' => 1,
        'ver_tv_internet' => 1,
    ];
@endphp

<div class="container-fluid">
    <!-- Top Row: Title, Search, Sort, Export -->
    <div class="row align-items-baseline">
        <div class="col-xl-4">
            <div class="body-left">
                <h6>Net Reach</h6>
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
    </div>

    <!-- Filter Form -->
    <form id="filter-form">
        <!-- Top Row Selection: Select Media Channels (Choose 3) -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="p-3 rounded shadow-sm select-group bg-custom">
                    <h5 class="mb-3">Select Media Channels (Choose 3)</h5>
                    <select class="js-example-basic-multiple" name="top_row[]" multiple="multiple">
                        <option value="ver_tv_senal_nacional" {{ in_array('ver_tv_senal_nacional', $selectedCategories) ? 'selected' : '' }}>ver_tv_senal_nacional</option>
                        <option value="ver_tv_cable" {{ in_array('ver_tv_cable', $selectedCategories) ? 'selected' : '' }}>ver_tv_cable</option>
                        <option value="ver_tv_internet" {{ in_array('ver_tv_internet', $selectedCategories) ? 'selected' : '' }}>ver_tv_internet</option>
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

        <!-- Apply Filters Section -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="p-3 rounded shadow-sm select-group bg-custom">
                    <h5 class="mb-3">Apply Filters</h5>
                    <div class="row">
                        @foreach($additionalFilterOptions as $col => $options)
                        <div class="col-md-3 mb-3">
                            <label for="filter_{{ $col }}">
                                <strong>{{ $filterLabelMapping[$col] ?? ucfirst(strtolower($col)) }}</strong>
                            </label>

                            <select class="form-select js-additional-filter" name="filter_{{ $col }}[]" multiple="multiple" id="filter_{{ $col }}">
                                @foreach($options as $option)
                                    @php
                                        $optionKey = (string) $option; // Ensure type consistency
                                    @endphp
                                    <option value="{{ $option }}">
                                        {{ $optionTitleMapping[$col][$optionKey] ?? $option }}
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
    <div class="bg-white rounded-lg mt-3">
        @if ($dataMessage)
            @component('components.no_data_message', ['message' => $dataMessage])
            @endcomponent
        @else
            <div class="pt-5 d-flex justify-content-center">
                <div class="flow-chart">
                    <canvas id="vennChart"></canvas>
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
<!-- Include Chart.js (and the Venn plugin if required) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- (Ensure that any required Chart.js Venn plugin is loaded) -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Initialize the Venn chart using the initial chartData from PHP.
    const chartData = @json($chartData);
    const labels = chartData.labels || [];
    const datasets = (chartData.datasets && chartData.datasets.length > 0) ? chartData.datasets[0].data : [];
    const backgroundColor = (chartData.datasets && chartData.datasets[0].backgroundColor) ? chartData.datasets[0].backgroundColor : [];

    const ctx = document.getElementById('vennChart').getContext('2d');

    // Function to create the chart config
    const createChartConfig = () => ({
        type: 'venn',
        data: {
            labels: labels,
            datasets: [{
                label: "Net Reach",
                data: datasets,
                backgroundColor: backgroundColor,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: { font: { size: 14 } }
                },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            const index = tooltipItem.dataIndex;
                            const percentageMatch = labels[index].match(/\((\d+(\.\d+)?)%\)/);
                            if (percentageMatch) {
                                return `${percentageMatch[1]}%`;
                            }
                            return 'N/A';
                        }
                    }
                }
            },
            layout: {
                padding: { top: 20, bottom: 20, left: 20, right: 20 }
            },
        }
    });

    let vennChart = new Chart(ctx, createChartConfig());

    // Initialize Select2 on all filter dropdowns
    $('.js-example-basic-multiple, .js-additional-filter').select2({
        placeholder: "Seleccionar",
        allowClear: true,
        width: '100%'
    });

    // Attach event handler to both top row and Apply Filters.
    $('.js-example-basic-multiple, .js-additional-filter').on('select2:select select2:unselect', function (e) {
        // For top row, enforce maximum of 3 selections.
        const topRowValues = $('.js-example-basic-multiple').val() || [];
        if (topRowValues.length > 3) {
            const lastSelectedValue = e.params.data.id;
            $('.js-example-basic-multiple').val(topRowValues.filter(value => value !== lastSelectedValue)).trigger('change');
            alert('You can only select up to 3 categories.');
            return;
        }
        // Serialize the entire form and send AJAX request.
        const formData = $('#filter-form').serialize();
        $.ajax({
            url: "{{ route('net-reach') }}",
            method: "GET",
            data: formData,
            success: function (response) {
                if (response.chartData) {
                    // Update chart data with new values.
                    vennChart.data.labels = response.chartData.labels;
                    vennChart.data.datasets[0].data = response.chartData.datasets[0].data;
                    vennChart.data.datasets[0].backgroundColor = response.chartData.datasets[0].backgroundColor;
                    vennChart.update();
                }
            },
            error: function (xhr, status, error) {
                const errMsg = xhr.responseJSON ? xhr.responseJSON.message : "An error occurred.";
                alert(errMsg);
            }
        });
    });
});
</script>
@endsection
