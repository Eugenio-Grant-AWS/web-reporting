@extends('layouts.admin')

@section('breadcrumb', $breadcrumb)

@section('content')

    <div class="container-fluid">
        <div class="row align-items-baseline">
            <div class="col-xl-4 ">
                <div class="body-left">
                    <h6> Unduplicated Net Reach</h6>

                </div>
            </div>
            <div class="col-xl-8 ">
                <div class="body-right">
                    <div class=" search-group bg-custom rounded-4">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search" class="bg-transparent border-0">
                    </div>
                    <button id="downloadChart" class="export-btn">
                        Export Chart as Image <i class="fas fa-download"></i>
                    </button>
                </div>
            </div>
            <div class="p-3 mt-3 select-group bg-custom rounded-4">
                <select class="js-example-basic-multiple" name="states[]" multiple="multiple">
                    <option value="ver_tv_senal_nacional">ver_tv_senal_nacional</option>
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
            <div class="bg-white rounded-lg">

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
    </div>


@endsection
<!-- Add Chart.js if not already included -->

<script>
document.addEventListener('DOMContentLoaded', function () {
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
                        font: {
                            weight: 'bold',
                            size: 12
                        },
                        formatter: (value) => `${value.toFixed(2)}`,
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
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        formatter: (value) => value > 0 ? `${value.toFixed(2)}` : '',
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
                    legend: {
                        display: true,
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                return `${context.dataset.label}: ${context.raw.toFixed(2)}`;
                            },
                        },
                    },
                    datalabels: {
                        display: true,
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function (value) {
                                return value;
                            }
                        }
                    },
                },
            },
            plugins: [ChartDataLabels]
        });

        // Handle dropdown change event
        $('.js-example-basic-multiple').on('change', function () {
            var selectedOptions = $(this).val();
            var data = {};
            selectedOptions.forEach(option => {
                data[option] = 1;
            });
            $.ajax({
                url: "{{ route('unduplicated-net-reach') }}",
                method: "GET",
                data: { top_row: data },
                success: function(response) {
                    if (response.commercialQualityData) {
                        chart.data.labels = response.commercialQualityData.labels; // Update labels
                        chart.data.datasets[0].data = response.commercialQualityData.cumulative; // Update cumulative
                        chart.data.datasets[1].data = response.commercialQualityData.marginal; // Update marginal
                        chart.update(); // Re-render chart with updated data
                    }
                }
            });
        });

        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });

        // Export Chart Functionality
        document.getElementById('downloadChart').addEventListener('click', function() {
            var canvas = document.getElementById('reachChart'); // Target correct chart
            var ctx = canvas.getContext('2d');

            // Add white background to the canvas before export
            ctx.save();
            ctx.globalCompositeOperation = 'destination-over';
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Generate image from canvas
            var image = canvas.toDataURL('image/jpeg', 1.0);
            var link = document.createElement('a');
            link.href = image;
            link.download = 'chart.jpg';
            link.click();

            ctx.restore();
        });

        // Initialize Select2
        // $('#multiple-select-field').select2({
        //     theme: 'bootstrap-5',
        //     width: '100%',
        //     placeholder: 'Choose anything',
        //     closeOnSelect: false,
        // });
    } else {
        console.error('Invalid chart data: Labels, marginal, or cumulative data is missing.');
    }
});


</script>


