@extends('layouts.admin')
@section('title', $breadcrumb)
@section('content')
@php
$defaultSelection = [
    'ver_tv_senal_nacional' => 1,
    'ver_tv_cable' => 1,
    'ver_tv_internet' => 1,

];
@endphp

    <div class="container-fluid">
        <div class="row align-items-baseline">
            <div class="col-xl-4 ">
                <div class="body-left">
                    <h6>Net Reach</h6>

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
            <div class="row">
                <div class="col-12">
                    <div class="p-3 mt-3 rounded shadow-sm select-group bg-custom">
                        <h5 class="mb-3">Select Media Channels</h5>

                        <select class="js-example-basic-multiple" name="states[]" multiple="multiple">
                            <option value="ver_tv_senal_nacional" <?= isset($defaultSelection['ver_tv_senal_nacional']) ? 'selected' : '' ?>>ver_tv_senal_nacional</option>
                            <option value="ver_tv_cable" <?= isset($defaultSelection['ver_tv_cable']) ? 'selected' : '' ?>>ver_tv_cable</option>
                            <option value="ver_tv_internet" <?= isset($defaultSelection['ver_tv_internet']) ? 'selected' : '' ?>>ver_tv_internet</option>
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
            <div class="bg-white rounded-lg ">

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
    </div>

@endsection


@section('scripts')
<script>
   document.addEventListener("DOMContentLoaded", function () {
    const chartData = @json($chartData);

    const labels = chartData.labels || [];
    const datasets = (chartData.datasets && chartData.datasets.length > 0) ? chartData.datasets[0].data : [];
    const backgroundColor = chartData.datasets && chartData.datasets[0].backgroundColor || [];

    const ctx = document.getElementById('vennChart').getContext('2d');

    // Function to create chart config
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
                    labels: {
                        font: {
                            size: 14,
                        },
                    },
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
                        },
                    },
                },
            },
            layout: {
                padding: {
                    top: 20,
                    bottom: 20,
                    left: 20,
                    right: 20,
                },
            },
        },
        // plugins: [
        //     {
        //         id: 'percentageLabels',
        //         afterDatasetsDraw: function (chart) {
        //             const ctx = chart.ctx;
        //             chart.data.datasets.forEach((dataset, datasetIndex) => {
        //                 const meta = chart.getDatasetMeta(datasetIndex);
        //                 meta.data.forEach((element, index) => {
        //                     const label = chart.data.labels[index];
        //                     const percentageMatch = label.match(/\((\d+(\.\d+)?)%\)/);
        //                     if (percentageMatch) {
        //                         const percentage = percentageMatch[1] + '%';
        //                         const position = element.tooltipPosition();

        //                         ctx.save();
        //                         ctx.fillStyle = '#000';
        //                         ctx.font = '12px Arial';
        //                         ctx.textAlign = 'center';

        //                         ctx.fillText(percentage, position.x, position.y - 5);
        //                         ctx.restore();
        //                     }
        //                 });
        //             });
        //         },
        //     },
        // ],
    });

    let vennChart = new Chart(ctx, createChartConfig());

    $(document).ready(function () {

        $('.js-example-basic-multiple').on('select2:select select2:unselect', function (e) {
            const selectedValues = $(this).val();
            var data = {};

            selectedValues.forEach(option => {
                data[option] = 1;
            });

            if (selectedValues.length > 3) {
                const lastSelectedValue = e.params.data.id;
                $(this).val(selectedValues.filter(value => value !== lastSelectedValue)).trigger('change');

                alert('You can only select up to 3 categories.');
                return;
            }

            $.ajax({
                url: "{{ route('net-reach') }}",
                method: "GET",
                data: { top_row: selectedValues },
                success: function (response) {
                    if (response.chartData) {
                        // Check if response.chartData has the necessary properties
                        if (response.chartData.labels && response.chartData.datasets && response.chartData.datasets[0].data) {
                            // Update the chart with the new data
                            vennChart.data.labels = response.chartData.labels;
                            vennChart.data.datasets[0].data = response.chartData.datasets[0].data;
                            vennChart.data.datasets[0].backgroundColor = response.chartData.datasets[0].backgroundColor;

                            // Update tooltip callback function
                            vennChart.options.plugins.tooltip.callbacks.label = function (tooltipItem) {
                                const index = tooltipItem.dataIndex;
                                const percentageMatch = vennChart.data.labels[index].match(/\((\d+(\.\d+)?)%\)/);
                                if (percentageMatch) {
                                    return `${percentageMatch[1]}%`;
                                }
                                return 'N/A';
                            };

                            vennChart.update();
                        } else {
                            console.error("Invalid chart data structure:", response.chartData);
                            alert("Error: Invalid chart data.");
                        }
                    }
                },
                error: function (xhr, status, error) {
                    const errMsg = xhr.responseJSON ? xhr.responseJSON.message : "An error occurred.";
                    console.error("Error updating chart data:", errMsg);
                    alert(errMsg);
                }
            });
        });

        $('.js-example-basic-multiple').select2({
            placeholder: "Select Media Channels",
            width: '100%',
        });
    });
});

</script>


{{-- <script>
    document.addEventListener("DOMContentLoaded", function () {
        const chartData = @json($chartData);

        const labels = chartData.labels || [];
        const datasets = chartData.datasets && chartData.datasets.length > 0 ? chartData.datasets[0].data : [];
        const backgroundColor = chartData.datasets[0].backgroundColor || [];

        const ctx = document.getElementById('vennChart').getContext('2d');

        // Function to create chart config
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
                        labels: {
                            font: {
                                size: 14,
                            },
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const index = tooltipItem.dataIndex;
                                const percentageMatch = labels[index].match(/\((\d+(\.\d+)?)%\)/);
                                if (percentageMatch) {
                                    return `${percentageMatch[1]}%`; // Only show the percentage
                                }
                                return 'N/A';
                            },
                        },
                    },
                },
                layout: {
                    padding: {
                        top: 20,
                        bottom: 20,
                        left: 20,
                        right: 20,
                    },
                },
            },
            plugins: [
                {
                    id: 'percentageLabels',
                    afterDatasetsDraw: function (chart) {
                        const ctx = chart.ctx;
                        chart.data.datasets.forEach((dataset, datasetIndex) => {
                            const meta = chart.getDatasetMeta(datasetIndex);
                            meta.data.forEach((element, index) => {
                                const label = chart.data.labels[index]; // Only the percentage label
                                const percentageMatch = label.match(/\((\d+(\.\d+)?)%\)/);
                                if (percentageMatch) {
                                    const percentage = percentageMatch[1] + '%'; // Only show percentage
                                    const position = element.tooltipPosition();

                                    ctx.save();
                                    ctx.fillStyle = '#000'; // Black text for visibility
                                    ctx.font = '12px Arial';
                                    ctx.textAlign = 'center';

                                    ctx.fillText(percentage, position.x, position.y - 5); // Display percentage only
                                    ctx.restore();
                                }
                            });
                        });
                    },
                },
            ],
        });

        let vennChart = new Chart(ctx, createChartConfig());

        // Initialize Select2
        $(document).ready(function () {
            // Limit selection to 3 categories
            $('.js-example-basic-multiple').on('select2:select select2:unselect', function (e) {
                const selectedValues = $(this).val();
                var data = {};

                // Build the data object with selected values
                selectedValues.forEach(option => {
                    data[option] = 1;
                });

                if (selectedValues.length > 3) {
                    // Deselect the last selected item if the limit is exceeded
                    const lastSelectedValue = e.params.data.id;
                    $(this).val(selectedValues.filter(value => value !== lastSelectedValue)).trigger('change');

                    // Show alert or notification
                    alert('You can only select up to 3 categories.');
                    return;
                }

                // Trigger AJAX to fetch updated chart data
                $.ajax({
                    url: "{{ route('net-reach') }}",  // Make sure this is the correct route
                    method: "GET",
                    data: { top_row: selectedValues },  // Send selected categories
                    success: function (response) {
                        if (response.chartData) {
                            // Update chart data
                            vennChart.data.labels = response.chartData.labels;
                            vennChart.data.datasets[0].data = response.chartData.datasets[0].data;
                            vennChart.data.datasets[0].backgroundColor = response.chartData.datasets[0].backgroundColor;

                            // Update chart and render new percentage labels
                            vennChart.update();
                        }
                    },
                    error: function (xhr, status, error) {
                        const errMsg = xhr.responseJSON ? xhr.responseJSON.message : "An error occurred.";
                        console.error("Error updating chart data:", errMsg);
                        alert(errMsg);  // Show the error message in an alert
                    }
                });
            });

            $('.js-example-basic-multiple').select2({
                placeholder: "Select Media Channels",
                width: '100%',
            });
        });
    });
    </script> --}}


@endsection
