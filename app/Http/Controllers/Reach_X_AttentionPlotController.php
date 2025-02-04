<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class Reach_X_AttentionPlotController extends Controller
{

    public function index()
    {
        $breadcrumb = 'Reach x Attention Plot';

        // Define the mapping of old MediaType labels to new ones
        $mediaTypeMapping = [
            "Abrir emails comerciales" => "Emails comerciales",
            "Buscar aseguradoras en Google" => "Buscar aseguradoras",
            "Escuchar Spotify" => "Spotify",
            "Ir al cine" => "Cine",
            "Jugar en el celular" => "Jugar en el celular",
            "Leer periódico digital" => "Periódico digital",
            "Leer periódico impreso" => "Periódico impreso",
            "Leer revista digital" => "Revista digital",
            "Leer revista impresa" => "Revista impresa",
            "Oír radio" => "Radio",
            "Oír radio online" => "Radio online",
            "Periódico por email" => "Periódico email",
            "Usar Airbnb" => "Airbnb",
            "Usar Encuentra24" => "Encuentra24",
            "Usar Facebook" => "Facebook",
            "Usar Instagram" => "Instagram",
            "Usar LinkedIn" => "LinkedIn",
            "Usar listas de correo" => "Listas de correo",
            "Usar Meet" => "Meet",
            "Usar metrobús" => "Metrobús",
            "Usar PedidosYa" => "PedidosYa",
            "Usar TikTok" => "TikTok",
            "Usar Uber" => "Uber",
            "Usar WeTransfer" => "WeTransfer",
            "Usar WhatsApp" => "WhatsApp",
            "Usar X (Twitter)" => "Twitter",
            "Usar YouTube" => "YouTube",
            "Usar Zoom" => "Zoom",
            "Ver Netflix" => "Netflix",
            "Ver TV nacional" => "TV nacional",
            "Ver TV por cable" => "TV cable",
            "Ver TV por internet" => "TV por internet",
            "Ver vallas publicitarias" => "Vallas publicitarias",
            "Visitar centros comerciales" => "Centros comerciales",
        ];

        // Fetch data from the reach_attention_plots table
        $mediaData = DB::table('reach_attention_plots')
            ->select(
                'MediaType',
                DB::raw('ROUND(100.0 * COUNT(CASE WHEN Reach = "Reached" THEN 1 END) / COUNT(*), 2) as reachPercentage'),
                DB::raw('ROUND(100.0 * COUNT(CASE WHEN Attention = "Yes" THEN 1 END) / COUNT(*), 2) as attentionPercentage')
            )
            ->groupBy('MediaType')
            ->get();

        // Map the MediaType values to the new labels
        $mediaData = $mediaData->map(function ($data) use ($mediaTypeMapping) {
            // Check if the current MediaType has a new label and update it
            if (isset($mediaTypeMapping[$data->MediaType])) {
                $data->MediaType = $mediaTypeMapping[$data->MediaType];
            }

            return $data;
        });

        // Convert data to an array formatted for the chart
        $chartData = $mediaData->map(function ($data) {
            return [
                'x' => (float) $data->reachPercentage,  // X-axis (Reach)
                'y' => (float) $data->attentionPercentage,  // Y-axis (Attention)
                'label' => $data->MediaType, // Label for annotation
            ];
        });

        // Check if data is available
        $dataMessage = empty($chartData) ? "No data available to display." : null;

        return view('reach-x-attention-plot', compact('chartData', 'breadcrumb', 'dataMessage'));
    }
}