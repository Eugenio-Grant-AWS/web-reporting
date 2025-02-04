<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndexedReview;
use Illuminate\Support\Facades\DB;

class TIPSummaryCreativeQualityController extends Controller
{
    public function index()
    {

        $breadcrumb = 'TIP Summary x Creative Quality';

        // Fetch distinct media types and TPI values
        $mediaTypes = IndexedReview::distinct()->pluck('MediaType');
        $tpis = IndexedReview::distinct()->pluck('tpi')->toArray();

        // Fetch counts for all media types and TPIs
        $fixedTotals = IndexedReview::select('MediaType', 'tpi', DB::raw('COUNT(*) as total'))
            ->groupBy('MediaType', 'tpi')
            ->get()
            ->groupBy('MediaType')
            ->mapWithKeys(function ($items) {
                return [$items->first()->MediaType => $items->pluck('total', 'tpi')->toArray()];
            })
            ->toArray();

        // Fetch SUM of influence instead of COUNT
        $influenceSums = IndexedReview::where('influence', '>', 0)
            ->select('MediaType', 'tpi', DB::raw('SUM(influence) as total_influence'))
            ->groupBy('MediaType', 'tpi')
            ->get()
            ->groupBy('MediaType')
            ->mapWithKeys(function ($items) {
                return [$items->first()->MediaType => $items->pluck('total_influence', 'tpi')->toArray()];
            })
            ->toArray();

        $commercialQualityData = [];
        $columnWiseSums = array_fill_keys($tpis, 0);
        $rowCount = count($mediaTypes);

        // Compute probabilities instead of direct counts
        foreach ($mediaTypes as $mediaType) {
            $percentageSum = 0;
            $commercialQualityData[$mediaType] = [];

            foreach ($tpis as $tpi) {
                $influenceValue = $influenceSums[$mediaType][$tpi] ?? 0;
                $commercialQualityData[$mediaType][$tpi] = round($influenceValue, 2);

                if ($tpi !== '7. Ignoro' && !empty($fixedTotals[$mediaType][$tpi])) {
                    // Calculate percentage using SUM(influence) instead of COUNT
                    $percentage = ($influenceValue / $fixedTotals[$mediaType][$tpi]) * 100;
                    $commercialQualityData[$mediaType][$tpi . ' Percentage'] = round($percentage, 2);
                    $columnWiseSums[$tpi] += $percentage;
                    $percentageSum += $percentage;
                }
            }

            // Compute Row-wise Grand Total %
            if ($percentageSum > 0) {
                $firstTpi = reset($tpis);
                $commercialQualityData[$mediaType]['Grand Total Row %'] = round(($percentageSum / ($fixedTotals[$mediaType][$firstTpi] ?? 1)) * 100, 2);
            }
        }

        // Compute Column-wise percentage as an average of row-wise percentages
        $columnWisePercentages = [];
        $columnWiseGrandTotal = 0;

        foreach ($tpis as $tpi) {
            if ($tpi !== '7. Ignoro' && !empty($columnWiseSums[$tpi])) {
                $columnPercentage = round(($columnWiseSums[$tpi] / $rowCount), 2);
                $columnWisePercentages[$tpi . ' Column Percentage'] = $columnPercentage;
                $columnWiseGrandTotal += $columnPercentage;
            }
        }

        if ($columnWiseGrandTotal > 0) {
            $columnWisePercentages['Grand Total Column %'] = round(($columnWiseGrandTotal / count($columnWisePercentages)), 2);
        }

        // Apply Excel-like formula (X7/X$42) * 100 using probability-based influence
        foreach ($mediaTypes as $mediaType) {
            foreach ($tpis as $tpi) {
                if (
                    isset($commercialQualityData[$mediaType][$tpi . ' Percentage']) &&
                    isset($columnWisePercentages[$tpi . ' Column Percentage']) &&
                    $columnWisePercentages[$tpi . ' Column Percentage'] > 0
                ) {
                    $commercialQualityData[$mediaType][$tpi . ' Adjusted Percentage'] = round(
                        ($commercialQualityData[$mediaType][$tpi . ' Percentage'] / $columnWisePercentages[$tpi . ' Column Percentage']) * 100,
                        2
                    );
                }
            }
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

            $cleanedMediaType = trim($mediaType);
            $newMediaType = $mediaTypeMapping[$cleanedMediaType] ?? $cleanedMediaType; // Default to the original if no mapping found

            return [$newMediaType => $data];
        })->toArray();

        $commercialQualityData['Column Percentages'] = $columnWisePercentages;

        $dataMessage = empty($commercialQualityData) ? "No data available to display." : null;

        return view('TIP_summary_creative_quality', compact('breadcrumb', 'commercialQualityData', 'dataMessage'));
    }
}