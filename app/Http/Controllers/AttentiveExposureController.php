<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AttentiveExposureController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Media Exposure';

        // Define the mapping of old MediaType labels to new ones
        $mediaTypeMapping = [
            "Ver TV nacional" => "TV nacional",
            "Ver TV por cable" => "TV por cable",
            "Ver TV por internet" => "TV por internet",
            "O?r radio" => "Radio",
            "O?r radio online" => "Radio online",
            "Leer revista impresa" => "Revista impresa",
            "Leer revista digital" => "Revista digital",
            "Leer peri?dico impreso" => "Periódico impreso",
            "Leer peri?dico digital" => "Periódico digital",
            "Peri?dico por email" => "Periódico por email",
            "Ver vallas publicitarias" => "Vallas publicitarias",
            "Visitar centros comerciales" => "Centros comerciales",
            "Usar metrobús" => "Metrobús",
            "Ir al cine" => "Cine",
            "Abrir emails comerciales" => "Emails comerciales",
            "Buscar aseguradoras en Google" => "Buscar aseguradoras",
            "Usar Facebook" => "Facebook",
            "Usar X (Twitter)" => "Twitter",
            "Usar Instagram" => "Instagram",
            "Usar YouTube" => "YouTube",
            "Usar LinkedIn" => "LinkedIn",
            "Usar WhatsApp" => "WhatsApp",
            "Escuchar Spotify" => "Spotify",
            "Ver Netflix" => "Netflix",
            "Usar listas de correo" => "Listas de correo",
            "Jugar en el celular" => "Jugar en el celular",
            "Usar WeTransfer" => "WeTransfer",
            "Usar Uber" => "Uber",
            "Usar PedidosYa" => "PedidosYa",
            "Usar Meet" => "Meet",
            "Usar Zoom" => "Zoom",
            "Usar Airbnb" => "Airbnb",
            "Usar TikTok" => "TikTok",
            "Usar Encuentra24" => "Encuentra24",
        ];

        // Fetch data from the attentive_exposures table
        $mediaData = DB::table('attentive_exposures')
            ->select('MediaType', 'attentive_exposure')
            ->get();
        // Ensure proper UTF-8 encoding for special characters
        $mediaData->transform(function ($item) use ($mediaTypeMapping) {
            // Map MediaType to the new label if it exists in the mapping
            if (isset($mediaTypeMapping[$item->MediaType])) {
                $item->MediaType = $mediaTypeMapping[$item->MediaType];
            }
            // Ensure correct UTF-8 encoding
            // $item->MediaType = mb_convert_encoding($item->MediaType, 'UTF-8', 'auto');
            return $item;
        });

        // Group the data by MediaType
        $groupedData = $mediaData->groupBy('MediaType');
        // dd($groupedData);
        // Calculate the average exposure for each group
        $averages = $groupedData->map(function ($group) {
            $total = $group->sum('attentive_exposure');
            $count = $group->count();
            return $count > 0 ? round(($total / $count) * 100, 2) : 0;
        });

        $sortedAverages = $averages->sortDesc();
        // Prepare data for the chart
        $chartData = [
            'categories' => $sortedAverages->keys()->toArray(), // Media types
            'percentages' => $sortedAverages->values()->toArray(), // Average exposure percentages
        ];

        // Check if data is available
        $dataMessage = empty($chartData['categories']) ? "No data available to display." : null;

        // Pass data to the view
        return view('attentive-exposure', compact('breadcrumb', 'chartData', 'dataMessage'));
    }
}