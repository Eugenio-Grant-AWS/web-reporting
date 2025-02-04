<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TouchpointInfluence;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class TouchpointInfluenceController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Touchpoint Influence';

        $mediaTypes = TouchpointInfluence::distinct()->pluck('MediaType');

        $tpis = TouchpointInfluence::distinct()->pluck('tpi')->toArray();

        $fixedTotals = TouchpointInfluence::select('MediaType', 'tpi', DB::raw('COUNT(*) as total'))
            ->groupBy('MediaType', 'tpi')
            ->get()
            ->groupBy('MediaType')
            ->map(function ($items) {
                return $items->pluck('total', 'tpi')->toArray();
            })
            ->toArray();


        // Fetch influence counts in a single query
        $influenceCounts = TouchpointInfluence::where('influence', 1)
            ->select('MediaType', 'tpi', DB::raw('COUNT(*) as total'))
            ->groupBy('MediaType', 'tpi')
            ->get()
            ->groupBy('MediaType')
            ->map(function ($items) {
                return $items->pluck('total', 'tpi')->toArray();
            })
            ->toArray();

        $commercialQualityData = [];
        $columnWiseSums = array_fill_keys($tpis, 0);
        $rowCount = count($mediaTypes);

        // Compute data with optimized loops
        foreach ($mediaTypes as $mediaType) {
            $percentageSum = 0;
            $commercialQualityData[$mediaType] = [];

            foreach ($tpis as $tpi) {
                $count = $influenceCounts[$mediaType][$tpi] ?? 0;
                $commercialQualityData[$mediaType][$tpi] = $count;

                if ($tpi !== '7. Ignoro' && !empty($fixedTotals[$mediaType][$tpi])) {
                    $percentage = ($count / $fixedTotals[$mediaType][$tpi]) * 100;
                    $commercialQualityData[$mediaType][$tpi . ' Percentage'] = round($percentage, 2);
                    $columnWiseSums[$tpi] += $percentage;
                    $percentageSum += $percentage;
                }
            }

            // Compute Row-wise Grand Total %
            if ($percentageSum > 0) {
                $firstTpi = reset($tpis); // Get the first TPI as a reference
                $commercialQualityData[$mediaType]['Grand Total Row %'] = round(($percentageSum / ($fixedTotals[$mediaType][$firstTpi] ?? 1)) * 100, 2);
            }
        }

        $columnWisePercentages = [];
        $columnWiseGrandTotal = 0;

        foreach ($tpis as $tpi) {
            if ($tpi !== '7. Ignoro' && !empty($columnWiseSums[$tpi])) {
                $columnPercentage = round(($columnWiseSums[$tpi] / $rowCount), 2);
                $columnWisePercentages[$tpi . ' Column Percentage'] = $columnPercentage;
                $columnWiseGrandTotal += $columnPercentage;
            }
        }

        // Compute Grand Total Column-Wise Percentage
        if ($columnWiseGrandTotal > 0) {
            $columnWisePercentages['Grand Total Column %'] = round(($columnWiseGrandTotal / count($columnWisePercentages)), 2);
        }


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

        $commercialQualityData = collect($commercialQualityData)->mapWithKeys(function ($data, $mediaType) use ($mediaTypeMapping) {
            // Clean the mediaType by trimming spaces
            $cleanedMediaType = trim($mediaType);

            // Use the mapping to replace old labels with new labels
            $newMediaType = $mediaTypeMapping[$cleanedMediaType] ?? $cleanedMediaType; // Default to the original if no mapping found

            return [$newMediaType => $data];
        })->toArray();

        $commercialQualityData['Column Percentages'] = $columnWisePercentages;

        // dd($commercialQualityData);
        $dataMessage = collect($commercialQualityData)->isEmpty() ? "No data available to display." : null;

        return view('touchpoint-influence', compact('breadcrumb', 'commercialQualityData', 'dataMessage'));
    }
}