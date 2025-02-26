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


    <!-- Chart Container -->
    <div class="mt-3 bg-white rounded-lg">
        @if ($dataMessage)
            @component('components.no_data_message', ['message' => $dataMessage])
            @endcomponent
        @else
        <form id="filter-form">
            <!-- Top Row Selection: Select Media Channels (Choose 3) -->
            <div class="mt-3 row">
                <div class="col-12">
                    <div class="p-3 rounded shadow-sm select-group bg-custom">
                        <h5 class="mb-3">Select Media Channels (Choose 3)</h5>
                        <select class="js-example-basic-multiple" name="top_row[]" multiple="multiple">
                            <option value="ver_tv_senal_nacional" {{ in_array('ver_tv_senal_nacional', $selectedCategories) ? 'selected' : '' }}>Ver TV nacional</option>
                            <option value="ver_tv_cable" {{ in_array('ver_tv_cable', $selectedCategories) ? 'selected' : '' }}>Ver TV por cable</option>
                            <option value="ver_tv_internet" {{ in_array('ver_tv_internet', $selectedCategories) ? 'selected' : '' }}>Ver TV por internet</option>
                            <option value="escuchar_radio">Escuchar Radio</option>
                            <option value="escuchar_radio_internet">Escuchar Radio Internet</option>
                            <option value="leer_revista_impresa">Leer Revista Impresa</option>
                            <option value="leer_revista_digital">Leer Revista Digital</option>
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
                        <option value="utilizar_we_transfer" {{ isset($selectedValues['utilizar_we_transfer']) ? 'selected' : '' }}>Usar WeTransfert</option>
                        <option value="utilizar_uber" {{ isset($selectedValues['utilizar_uber']) ? 'selected' : '' }}>Usar Uber</option>
                        <option value="utilizar_pedidos_ya" {{ isset($selectedValues['utilizar_pedidos_ya']) ? 'selected' : '' }}>Usar PedidosYa</option>
                        <option value="abrir_correos_companias" {{ isset($selectedValues['abrir_correos_companias']) ? 'selected' : '' }}>Usar listas de correo</option>
                        <option value="utilizar_meet" {{ isset($selectedValues['utilizar_meet']) ? 'selected' : '' }}>Usar Meet</option>
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

    document.getElementById('downloadChart').addEventListener('click', function() {
        if (vennChart) {  // Ensure chart is initialized
            const imageUrl = vennChart.toBase64Image();
            var link = document.createElement('a');
            link.href = imageUrl;
            link.download = 'venn_chart.png';
            link.click();
        } else {
            console.error('Chart is not initialized.');
        }
    });
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
