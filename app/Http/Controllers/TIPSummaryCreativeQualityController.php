<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndexedReview;
use Illuminate\Support\Facades\DB;

class TIPSummaryCreativeQualityController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = 'TIP Summary x Creative Quality';

        // Define filterable columns and their request parameter keys
        $filters = [
            'QuotGene'   => 'uniqueGender',
            'QuotEdad'   => 'uniqueAge',
            'QuoSegur'   => 'uniqueQuoSegur',
        ];
        $filterLabels = [
            'uniqueGender'   => 'Género',
            'uniqueAge'      => 'Edad',
            'uniqueQuoSegur' => 'Seguro', // Adjust the label as needed
        ];
        // Fetch distinct values for filters (for the dropdowns)
        $distinctValues = [];
        foreach ($filters as $column => $requestKey) {
            $distinctValues[$requestKey] = IndexedReview::distinct()->pluck($column);
        }

        // Handle AJAX request for filtering and calculations
        if ($request->ajax()) {
            // Build filtered query based on the inputs
            $filteredQuery = IndexedReview::query();
            foreach ($filters as $column => $requestKey) {
                if ($request->filled($requestKey)) {
                    $filterValues = $request->$requestKey;
                    // If filtering by MediaType, trim both the database value and the filter values
                    if ($column === 'MediaType') {
                        // Remove debugging and trim filter values
                        $filterValues = array_map('trim', $filterValues);
                        $placeholders = implode(',', array_fill(0, count($filterValues), '?'));
                    
                        // Remove newline characters from MediaType in the DB before comparing.
                        $filteredQuery->whereRaw(
                            "REPLACE(REPLACE(MediaType, '\r', ''), '\n', '') IN ($placeholders)",
                            $filterValues
                        );
                    } else {
                        $filteredQuery->whereIn($column, $filterValues);
                    }
                    
                    
                }
            }

            // Get distinct MediaTypes and tpis from the filtered data
            $mediaTypes = (clone $filteredQuery)->distinct()->pluck('MediaType');

            $tpis = (clone $filteredQuery)->distinct()->pluck('tpi')->toArray();

            // Calculate total counts per MediaType and tpi (using filtered data)
            $fixedTotals = (clone $filteredQuery)
                ->select('MediaType', 'tpi', DB::raw('COUNT(*) as total'))
                ->groupBy('MediaType', 'tpi')
                ->get()
                ->groupBy('MediaType')
                ->mapWithKeys(function ($items) {
                    return [$items->first()->MediaType => $items->pluck('total', 'tpi')->toArray()];
                })
                ->toArray();

            // Calculate sum of influence per MediaType and tpi (using filtered data)
            $influenceSums = (clone $filteredQuery)
                ->where('influence', '>', 0)
                ->select('MediaType', 'tpi', DB::raw('SUM(influence) as total_influence'))
                ->groupBy('MediaType', 'tpi')
                ->get()
                ->groupBy('MediaType')
                ->mapWithKeys(function ($items) {
                    return [$items->first()->MediaType => $items->pluck('total_influence', 'tpi')->toArray()];
                })
                ->toArray();

            // Perform the aggregated calculations (similar to initial load)
            $commercialQualityData = [];
            $columnWiseSums = array_fill_keys($tpis, 0);
            $rowCount = count($mediaTypes);

            foreach ($mediaTypes as $mediaType) {
                $percentageSum = 0;
                $commercialQualityData[$mediaType] = [];
                foreach ($tpis as $tpi) {
                    $influenceValue = $influenceSums[$mediaType][$tpi] ?? 0;
                    $commercialQualityData[$mediaType][$tpi] = round($influenceValue, 2);
                    if ($tpi !== '7. Ignoro' && !empty($fixedTotals[$mediaType][$tpi])) {
                        $percentage = ($influenceValue / $fixedTotals[$mediaType][$tpi]) * 100;
                        $commercialQualityData[$mediaType][$tpi . ' Percentage'] = round($percentage, 2);
                        $columnWiseSums[$tpi] += $percentage;
                        $percentageSum += $percentage;
                    }
                }
                if ($percentageSum > 0) {
                    $firstTpi = reset($tpis);
                    $commercialQualityData[$mediaType]['Grand Total Row %'] = round(
                        ($percentageSum / ($fixedTotals[$mediaType][$firstTpi] ?? 1)) * 100, 2
                    );
                }
            }

            // Calculate column-wise percentages
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
                    ($columnWiseGrandTotal / count($columnWisePercentages)), 2
                );
            }

            // Calculate adjusted percentages per MediaType
            foreach ($mediaTypes as $mediaType) {
                foreach ($tpis as $tpi) {
                    if (
                        isset($commercialQualityData[$mediaType][$tpi . ' Percentage']) &&
                        isset($columnWisePercentages[$tpi . ' Column Percentage']) &&
                        $columnWisePercentages[$tpi . ' Column Percentage'] > 0
                    ) {
                        $commercialQualityData[$mediaType][$tpi . ' Adjusted Percentage'] = round(
                            ($commercialQualityData[$mediaType][$tpi . ' Percentage'] /
                             $columnWisePercentages[$tpi . ' Column Percentage']) * 100,
                            2
                        );
                    }
                }
            }

            // Media Type mapping (if needed)
            $mediaTypeMapping = [
                "Abrir emails comerciales"    => "Emails comerciales",
                "Buscar aseguradoras en Google" => "Buscar aseguradoras",
                "Escuchar Spotify"             => "Spotify",
                "Ir al cine"                   => "Cine",
                "Jugar en el celular"          => "Jugar en el celular",
                "Leer periódico digital"       => "Periódico digital",
                "Leer periódico impreso"       => "Periódico impreso",
                "Leer revista digital"         => "Revista digital",
                "Leer revista impresa"         => "Revista impresa",
                "Oír radio"                    => "Radio",
                "Oír radio online"             => "Radio online",
                "Periódico por email"          => "Periódico email",
                "Usar Airbnb"                  => "Airbnb",
                "Usar Encuentra24"             => "Encuentra24",
                "Usar Facebook"                => "Facebook",
                "Usar Instagram"               => "Instagram",
                "Usar LinkedIn"                => "LinkedIn",
                "Usar listas de correo"        => "Listas de correo",
                "Usar Meet"                    => "Meet",
                "Usar TikTok"                  => "TikTok",
                "Usar Uber"                    => "Uber",
                "Usar WhatsApp"                => "WhatsApp",
                "Usar X (Twitter)"             => "Twitter",
                "Usar YouTube"                 => "YouTube",
                "Usar Zoom"                    => "Zoom",
                "Ver Netflix"                  => "Netflix",
                "Ver TV nacional"              => "TV nacional",
                "Ver TV por cable"             => "TV cable",
                "Ver TV por internet"          => "TV por internet",
                "Ver vallas publicitarias"     => "Vallas publicitarias",
                "Visitar centros comerciales"  => "Centros comerciales",
            ];

            $commercialQualityData = collect($commercialQualityData)->mapWithKeys(function ($data, $mediaType) use ($mediaTypeMapping) {
                $cleanedMediaType = trim($mediaType);
                $newMediaType = $mediaTypeMapping[$cleanedMediaType] ?? $cleanedMediaType;
                return [$newMediaType => $data];
            })->toArray();

            // Append the column percentages if needed
            $commercialQualityData['Column Percentages'] = $columnWisePercentages;

            // Prepare the rows for the table (formatted for the JS renderTable function)
            $rows = [];
            foreach ($commercialQualityData as $mediaChannel => $data) {
                if ($mediaChannel === 'Column Percentages') {
                    continue;
                }
                $rows[] = [
                    'MediaChannels'  => $mediaChannel,
                    'Awareness'      => isset($data['1. Awareness Percentage']) ? $data['1. Awareness Percentage'] . '%' : '0',
                    'Understanding'  => isset($data['2. Understanding Percentage']) ? $data['2. Understanding Percentage'] . '%' : '0',
                    'Trial'          => isset($data['3. Trial Percentage']) ? $data['3. Trial Percentage'] . '%' : '0',
                    'TopOfMind'      => isset($data['4. Top of Mind Percentage']) ? $data['4. Top of Mind Percentage'] . '%' : '0',
                    'Image'          => isset($data['5. Image Percentage']) ? $data['5. Image Percentage'] . '%' : '0',
                    'Loyalty'        => isset($data['6. Loyalty Percentage']) ? $data['6. Loyalty Percentage'] . '%' : '0',
                ];
            }

            return response()->json(['data' => $rows]);
        }

        // Non-AJAX: Initial page load (full dataset calculations)

        $mediaTypes = IndexedReview::distinct()->pluck('MediaType');
        $tpis = IndexedReview::distinct()->pluck('tpi')->toArray();

        $fixedTotals = IndexedReview::select('MediaType', 'tpi', DB::raw('COUNT(*) as total'))
            ->groupBy('MediaType', 'tpi')
            ->get()
            ->groupBy('MediaType')
            ->mapWithKeys(function ($items) {
                return [$items->first()->MediaType => $items->pluck('total', 'tpi')->toArray()];
            })
            ->toArray();

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

        foreach ($mediaTypes as $mediaType) {
            $percentageSum = 0;
            $commercialQualityData[$mediaType] = [];
            foreach ($tpis as $tpi) {
                $influenceValue = $influenceSums[$mediaType][$tpi] ?? 0;
                $commercialQualityData[$mediaType][$tpi] = round($influenceValue, 2);
                if ($tpi !== '7. Ignoro' && !empty($fixedTotals[$mediaType][$tpi])) {
                    $percentage = ($influenceValue / $fixedTotals[$mediaType][$tpi]) * 100;
                    $commercialQualityData[$mediaType][$tpi . ' Percentage'] = round($percentage, 2);
                    $columnWiseSums[$tpi] += $percentage;
                    $percentageSum += $percentage;
                }
            }
            if ($percentageSum > 0) {
                $firstTpi = reset($tpis);
                $commercialQualityData[$mediaType]['Grand Total Row %'] = round(
                    ($percentageSum / ($fixedTotals[$mediaType][$firstTpi] ?? 1)) * 100, 2
                );
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
        if ($columnWiseGrandTotal > 0) {
            $columnWisePercentages['Grand Total Column %'] = round(
                ($columnWiseGrandTotal / count($columnWisePercentages)), 2
            );
        }

        foreach ($mediaTypes as $mediaType) {
            foreach ($tpis as $tpi) {
                if (
                    isset($commercialQualityData[$mediaType][$tpi . ' Percentage']) &&
                    isset($columnWisePercentages[$tpi . ' Column Percentage']) &&
                    $columnWisePercentages[$tpi . ' Column Percentage'] > 0
                ) {
                    $commercialQualityData[$mediaType][$tpi . ' Adjusted Percentage'] = round(
                        ($commercialQualityData[$mediaType][$tpi . ' Percentage'] /
                         $columnWisePercentages[$tpi . ' Column Percentage']) * 100,
                        2
                    );
                }
            }
        }

        $mediaTypeMapping = [
            "Abrir emails comerciales"    => "Emails comerciales",
            "Buscar aseguradoras en Google" => "Buscar aseguradoras",
            "Escuchar Spotify"             => "Spotify",
            "Ir al cine"                   => "Cine",
            "Jugar en el celular"          => "Jugar en el celular",
            "Leer periódico digital"       => "Periódico digital",
            "Leer periódico impreso"       => "Periódico impreso",
            "Leer revista digital"         => "Revista digital",
            "Leer revista impresa"         => "Revista impresa",
            "Oír radio"                    => "Radio",
            "Oír radio online"             => "Radio online",
            "Periódico por email"          => "Periódico email",
            "Usar Airbnb"                  => "Airbnb",
            "Usar Encuentra24"             => "Encuentra24",
            "Usar Facebook"                => "Facebook",
            "Usar Instagram"               => "Instagram",
            "Usar LinkedIn"                => "LinkedIn",
            "Usar listas de correo"        => "Listas de correo",
            "Usar Meet"                    => "Meet",
            "Usar TikTok"                  => "TikTok",
            "Usar Uber"                    => "Uber",
            "Usar WhatsApp"                => "WhatsApp",
            "Usar X (Twitter)"             => "Twitter",
            "Usar YouTube"                 => "YouTube",
            "Usar Zoom"                    => "Zoom",
            "Ver Netflix"                  => "Netflix",
            "Ver TV nacional"              => "TV nacional",
            "Ver TV por cable"             => "TV cable",
            "Ver TV por internet"          => "TV por internet",
            "Ver vallas publicitarias"     => "Vallas publicitarias",
            "Visitar centros comerciales"  => "Centros comerciales",
        ];

        $commercialQualityData = collect($commercialQualityData)->mapWithKeys(function ($data, $mediaType) use ($mediaTypeMapping) {
            $cleanedMediaType = trim($mediaType);
            $newMediaType = $mediaTypeMapping[$cleanedMediaType] ?? $cleanedMediaType;
            return [$newMediaType => $data];
        })->toArray();

        $commercialQualityData['Column Percentages'] = $columnWisePercentages;
        $dataMessage = empty($commercialQualityData) ? "No data available to display." : null;

        return view('TIP_summary_creative_quality', compact('breadcrumb', 'commercialQualityData', 'dataMessage', 'distinctValues', 'mediaTypeMapping','filterLabels'));

    }
}
