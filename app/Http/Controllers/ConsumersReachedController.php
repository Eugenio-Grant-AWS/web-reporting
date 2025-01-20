<?php

namespace App\Http\Controllers;

use App\Models\ConsumersReached;
use Illuminate\Http\Request;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class ConsumersReachedController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = 'Net % of Consumers Reached';

        // Define default values for the columns
        $defaultValues = [
            'ver_tv_senal_nacional' => 1,
            'ver_tv_cable' => 0,
            'ver_tv_internet' => 0,
            'escuchar_radio' => 0,
            'escuchar_radio_internet' => 0,
            'leer_revista_impresa' => 0,
            'leer_revista_digital' => 0,
            'leer_periodico_impreso' => 0,
            'leer_periodico_digital' => 0,
            'leer_periodico_email' => 0,
            'vallas_publicitarias' => 0,
            'centros_comerciales' => 0,
            'transitar_metrobuses' => 0,
            'ver_cine' => 0,
            'abrir_correos_companias' => 0,
            'entrar_sitios_web' => 0,
            'entrar_facebook' => 0,
            'entrar_twitter' => 0,
            'entrar_instagram' => 0,
            'entrar_youtube' => 0,
            'entrar_linkedin' => 0,
            'entrar_whatsapp' => 0,
            'escuchar_spotify' => 0,
            'ver_netflix' => 0,
            'utilizar_mailing_list' => 0,
            'videojuegos_celular' => 0,
            'utilizar_we_transfer' => 0,
            'utilizar_waze' => 0,
            'utilizar_uber' => 0,
            'utilizar_pedidos_ya' => 0,
            'utilizar_meet' => 0,
            'utilizar_zoom' => 0,
            'utilizar_airbnb' => 0,
            'entrar_google' => 0,
            'entrar_encuentra24' => 0,
        ];

        $targetColumns = array_keys($defaultValues);

        $dataRows = ConsumersReached::select($targetColumns)->get();

        $columnResults = [];

        foreach ($targetColumns as $currentColumn) {
            $countZero = 0;
            $countOne = 0;

            foreach ($dataRows as $row) {
                $result = $row->$currentColumn;

                if ($result === 1) {
                    $countOne++;
                } else {
                    $countZero++;
                }
            }

            // Store the counts for the current column
            $columnResults[$currentColumn] = [
                'count_1' => $countOne,
                'count_0' => $countZero
            ];
        }

        $commercialQualityData = [
            'labels' => ['Not Reached', 'Reached'],
            'datasets' => []
        ];

        foreach ($columnResults as $key => $values) {
            $reachedCount = $values['count_1'];
            $notReachCount = $values['count_0'];
            $totalConsumers = $reachedCount + $notReachCount;

            $reachedPercentage = $totalConsumers > 0 ? round(($reachedCount / $totalConsumers) * 100, 2) : 0;
            $notReachedPercentage = $totalConsumers > 0 ? round(($notReachCount / $totalConsumers) * 100, 2) : 0;

            $commercialQualityData['datasets'][] = [
                'label' => ucfirst(str_replace('_', ' ', $key)),
                'data' => [$notReachedPercentage, $reachedPercentage],
                'backgroundColor' => ['#FF5733', '#33FF57'],
            ];
        }

        $dataMessage = count($dataRows) === 0 ? "No data available to display." : null;

        return view('consumers-reached', compact('commercialQualityData', 'breadcrumb', 'dataMessage'));
    }
}