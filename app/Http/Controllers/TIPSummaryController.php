<?php

namespace App\Http\Controllers;

use App\Models\IndexedReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TIPSummaryController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = 'TIP Summary';

        // Define the filterable columns (using the same keys as your old code)
        $filters = [
            'QuotGene'  => 'uniqueGender',
            'QuotEdad'  => 'uniqueAge',
            'QuoSegur'  => 'uniqueQuoSegur',
        ];
        $filterLabels = [
            'uniqueGender'   => 'Género',
            'uniqueAge'      => 'Edad',
            'uniqueQuoSegur' => 'Seguro', // Adjust the label as needed
        ];
        // Get distinct values for each filter (for the dropdowns)
        $distinctValues = [];
        foreach ($filters as $column => $requestKey) {
            $distinctValues[$requestKey] = IndexedReview::distinct()->pluck($column);
        }

        // Build the base query and Aplicar if provided
        $query = IndexedReview::query();
        foreach ($filters as $column => $requestKey) {
            if ($request->filled($requestKey)) {
                $filterValues = $request->$requestKey;
                // If filtering by MediaType, trim both the filter values and remove newlines from the DB value.
                if ($column === 'MediaType') {
                    $filterValues = array_map('trim', $filterValues);
                    $placeholders = implode(',', array_fill(0, count($filterValues), '?'));
                    // Remove newline characters from MediaType in the DB before comparing.
                    $query->whereRaw(
                        "REPLACE(REPLACE(MediaType, '\r', ''), '\n', '') IN ($placeholders)",
                        $filterValues
                    );
                } else {
                    $query->whereIn($column, $filterValues);
                }
            }
        }

        // Use the filtered query for all calculations (if no filters, it returns all records)
        $mediaTypes = (clone $query)->distinct()->pluck('MediaType');
        $tpis = (clone $query)->distinct()->pluck('tpi')->toArray();

        // Get counts (fixed totals) per MediaType and tpi
        $fixedTotals = (clone $query)
            ->select('MediaType', 'tpi', DB::raw('COUNT(*) as total'))
            ->groupBy('MediaType', 'tpi')
            ->get()
            ->groupBy('MediaType')
            ->mapWithKeys(function ($items) {
                return [$items->first()->MediaType => $items->pluck('total', 'tpi')->toArray()];
            })
            ->toArray();

        // Get the SUM of influence values (only where influence > 0)
        $influenceSums = (clone $query)
            ->where('influence', '>', 0)
            ->select('MediaType', 'tpi', DB::raw('SUM(influence) as total_influence'))
            ->groupBy('MediaType', 'tpi')
            ->get()
            ->groupBy('MediaType')
            ->mapWithKeys(function ($items) {
                return [$items->first()->MediaType => $items->pluck('total_influence', 'tpi')->toArray()];
            })
            ->toArray();

        // The following calculations are exactly as in your new code
        $commercialQualityData = [];
        $columnWiseSums = array_fill_keys($tpis, 0);
        $rowCount = count($mediaTypes);

        // Compute percentages using SUM(influence) divided by COUNT(*) for each media type and tpi
        foreach ($mediaTypes as $mediaType) {
            $percentageSum = 0;
            $commercialQualityData[$mediaType] = [];

            foreach ($tpis as $tpi) {
                $influenceValue = $influenceSums[$mediaType][$tpi] ?? 0;
                $commercialQualityData[$mediaType][$tpi] = round($influenceValue, 2);

                if ($tpi !== '7. Ignoro' && !empty($fixedTotals[$mediaType][$tpi])) {
                    // Calculate the percentage based on influence / count
                    $percentage = ($influenceValue / $fixedTotals[$mediaType][$tpi]) * 100;
                    $commercialQualityData[$mediaType][$tpi . ' Percentage'] = round($percentage, 2);
                    $columnWiseSums[$tpi] += $percentage;
                    $percentageSum += $percentage;
                }
            }

            // Compute Row-wise Grand Total Percentage
            if ($percentageSum > 0) {
                $firstTpi = reset($tpis);
                $commercialQualityData[$mediaType]['Grand Total Row %'] = round(
                    ($percentageSum / ($fixedTotals[$mediaType][$firstTpi] ?? 1)) * 100,
                    2
                );
            }
        }

        // Compute Column-wise Percentages (average of row percentages)
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
            $columnWisePercentages['Grand Total Column %'] = round(
                ($columnWiseGrandTotal / count($columnWisePercentages)),
                2
            );
        }

        // Calculate Adjusted Percentages using an Excel-like formula
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

        // Media Type mapping
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

        // Remap media type names
        $commercialQualityData = collect($commercialQualityData)->mapWithKeys(function ($data, $mediaType) use ($mediaTypeMapping) {
            $cleanedMediaType = trim($mediaType);
            $newMediaType = $mediaTypeMapping[$cleanedMediaType] ?? $cleanedMediaType;
            return [$newMediaType => $data];
        })->toArray();

        // Append the column percentages if needed
        $commercialQualityData['Column Percentages'] = $columnWisePercentages;
        $dataMessage = empty($commercialQualityData) ? "No data available to display." : null;

        // If the request is an AJAX call, return JSON data for the table
        if ($request->ajax()) {
            $dataRows = [];
            foreach ($commercialQualityData as $mediaType => $data) {
                if ($mediaType === 'Column Percentages') {
                    continue;
                }
                $dataRows[] = [
                    'MediaChannels'   => $mediaType,
                    'Awareness'       => round($data['1. Awareness Percentage'] ?? 0, 2) . '%',
                    'Understanding'   => round($data['2. Understanding Percentage'] ?? 0, 2) . '%',
                    'Trial'           => round($data['3. Trial Percentage'] ?? 0, 2) . '%',
                    'TopOfMind'       => round($data['4. Top of Mind Percentage'] ?? 0, 2) . '%',
                    'Image'           => round($data['5. Image Percentage'] ?? 0, 2) . '%',
                    'Loyalty'         => round($data['6. Loyalty Percentage'] ?? 0, 2) . '%',
                ];
            }
            return response()->json(['data' => $dataRows]);
        }

        // Return the view for non-AJAX requests
        return view('tip-summary', compact(
            'breadcrumb',
            'commercialQualityData',
            'dataMessage',
            'distinctValues',
            'mediaTypeMapping',
            'filterLabels'
        ));
    }
}
