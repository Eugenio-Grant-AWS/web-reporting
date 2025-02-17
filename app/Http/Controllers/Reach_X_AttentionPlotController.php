<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Reach_X_AttentionPlotController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = 'Reach x Attention Plot';

        // Define filters for this plot.
        // (Assuming the reach_attention_plots table has columns: MediaType, Reach, and Attention.)
        $filters = [
            'QuotGene'  => 'uniqueGender',
            'QuotEdad'  => 'uniqueAge',
            'QuoSegur'  => 'uniqueQuoSegur',
        ];
        $filterLabels = [
            'uniqueGender'   => 'Género',
            'uniqueAge'      => 'Edad',
            'uniqueQuoSegur' => 'Seguro', // Change 'Seguro' to the appropriate label as needed.
        ];
        // Get distinct values for each filter (to populate the dropdowns)
        $distinctValues = [];
        foreach ($filters as $column => $reqKey) {
            $distinctValues[$reqKey] = DB::table('reach_attention_plots')
                ->distinct()
                ->pluck($column);
        }

        // Build the base query from the reach_attention_plots table
        $query = DB::table('reach_attention_plots');

        // Aplicar if present
        foreach ($filters as $column => $reqKey) {
            if ($request->filled($reqKey)) {
                $filterValues = $request->input($reqKey); // expecting an array
                if ($column === 'MediaType') {
                    // Trim form values and use TRIM() on the DB column to avoid mismatches
                    $filterValues = array_map('trim', $filterValues);
                    $placeholders = implode(',', array_fill(0, count($filterValues), '?'));
                    $query->whereRaw("TRIM(MediaType) IN ($placeholders)", $filterValues);
                } else {
                    $query->whereIn($column, $filterValues);
                }
            }
        }

        // Fetch aggregated data:
        // Calculate reachPercentage and attentionPercentage for each MediaType.
        $mediaData = $query->select(
            'MediaType',
            DB::raw('ROUND(100.0 * COUNT(CASE WHEN Reach = "Reached" THEN 1 END) / COUNT(*), 2) as reachPercentage'),
            DB::raw('ROUND(100.0 * COUNT(CASE WHEN Attention = "Yes" THEN 1 END) / COUNT(*), 2) as attentionPercentage')
        )
            ->groupBy('MediaType')
            ->get();

        // Define a mapping for MediaType labels (if needed)
        $mediaTypeMapping = [
            "Abrir emails comerciales"          => "Emails comerciales",
            "Buscar aseguradoras en Google"       => "Buscar aseguradoras",
            "Escuchar Spotify"                    => "Spotify",
            "Ir al cine"                          => "Cine",
            "Jugar en el celular"                 => "Jugar en el celular",
            "Leer periódico digital"              => "Periódico digital",
            "Leer periódico impreso"              => "Periódico impreso",
            "Leer revista digital"                => "Revista digital",
            "Leer revista impresa"                => "Revista impresa",
            "Oír radio"                           => "Radio",
            "Oír radio online"                    => "Radio online",
            "Periódico por email"                 => "Periódico email",
            "Usar Airbnb"                         => "Airbnb",
            "Usar Encuentra24"                    => "Encuentra24",
            "Usar Facebook"                       => "Facebook",
            "Usar Instagram"                      => "Instagram",
            "Usar LinkedIn"                       => "LinkedIn",
            "Usar listas de correo"               => "Listas de correo",
            "Usar Meet"                           => "Meet",
            "Usar metrobús"                       => "Metrobús",
            "Usar PedidosYa"                      => "PedidosYa",
            "Usar TikTok"                         => "TikTok",
            "Usar Uber"                           => "Uber",
            "Usar WeTransfer"                     => "WeTransfer",
            "Usar WhatsApp"                       => "WhatsApp",
            "Usar X (Twitter)"                    => "Twitter",
            "Usar YouTube"                        => "YouTube",
            "Usar Zoom"                           => "Zoom",
            "Ver Netflix"                         => "Netflix",
            "Ver TV nacional"                     => "TV nacional",
            "Ver TV por cable"                    => "TV cable",
            "Ver TV por internet"                 => "TV por internet",
            "Ver vallas publicitarias"            => "Vallas publicitarias",
            "Visitar centros comerciales"         => "Centros comerciales",
        ];

        // Map MediaType values using the mapping if available
        $mediaData = $mediaData->map(function ($item) use ($mediaTypeMapping) {
            if (isset($mediaTypeMapping[$item->MediaType])) {
                $item->MediaType = $mediaTypeMapping[$item->MediaType];
            }
            return $item;
        });

        // Convert the aggregated data into the format required for the chart.
        $chartData = $mediaData->map(function ($item) {
            return [
                'x'     => (float) $item->reachPercentage,      // X-axis: Reach Percentage
                'y'     => (float) $item->attentionPercentage,    // Y-axis: Attention Percentage
                'label' => $item->MediaType                     // Label for annotation
            ];
        });

        $dataMessage = ($chartData->isEmpty()) ? "No data available to display." : null;

        // If the request is AJAX, return the new chart data as JSON.
        if ($request->ajax()) {
            return response()->json([
                'chartData'   => $chartData,
                'dataMessage' => $dataMessage,
            ]);
        }

        // For a normal (non-AJAX) request, pass all necessary data to the view.
        return view('reach-x-attention-plot', compact(
            'chartData',
            'breadcrumb',
            'dataMessage',
            'distinctValues',
            'mediaTypeMapping',
            'filterLabels'
        ));
    }
}