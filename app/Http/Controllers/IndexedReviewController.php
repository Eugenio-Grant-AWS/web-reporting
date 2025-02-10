<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TouchpointInfluence;

class IndexedReviewController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = 'Indexed Review Of Stronger Drivers';

        // Define all filters (key = database column, value = request parameter key)
        $filters = [
            'QuotGene'  => 'uniqueGender',
            'QuotEdad'  => 'uniqueAge',
            'QuoSegur'  => 'uniqueQuoSegur',
        ];
        $filterLabels = [
            'uniqueGender'   => 'Género',
            'uniqueAge'      => 'Edad',
            'uniqueQuoSegur' => 'Seguro', // Adjust as needed
        ];
        // Get distinct values for each filter (to populate dropdowns)
        $distinctValues = [];
        foreach ($filters as $column => $requestKey) {
            $distinctValues[$requestKey] = TouchpointInfluence::distinct()->pluck($column);
        }

        // Build the base query and Aplicar (using whereIn for multiple select)
        $query = TouchpointInfluence::query();
        foreach ($filters as $column => $requestKey) {
            if ($request->filled($requestKey)) {
                $filterValues = $request->input($requestKey);
                if ($column === 'MediaType') {
                    // Trim form values and use TRIM() on the DB column to avoid mismatches
                    $filterValues = array_map('trim', $filterValues);
                    $placeholders = implode(',', array_fill(0, count($filterValues), '?'));
                    $query->whereRaw(
                        "REPLACE(REPLACE(MediaType, '\r', ''), '\n', '') IN ($placeholders)",
                        $filterValues
                    );
                } else {
                    $query->whereIn($column, $filterValues);
                }
            }
        }

        // Use the filtered query for your calculations
        $mediaTypes = (clone $query)->distinct()->pluck('MediaType');
        $tpis = (clone $query)->distinct()->pluck('tpi')->toArray();

        // Fetch counts per MediaType and tpi
        $fixedTotals = (clone $query)
            ->select('MediaType', 'tpi', DB::raw('COUNT(*) as total'))
            ->groupBy('MediaType', 'tpi')
            ->get()
            ->groupBy('MediaType')
            ->mapWithKeys(function ($items) {
                return [$items->first()->MediaType => $items->pluck('total', 'tpi')->toArray()];
            })
            ->toArray();

        // Fetch influence counts (only where influence = 1)
        $influenceCounts = (clone $query)
            ->where('influence', 1)
            ->select('MediaType', 'tpi', DB::raw('COUNT(*) as total'))
            ->groupBy('MediaType', 'tpi')
            ->get()
            ->groupBy('MediaType')
            ->mapWithKeys(function ($items) {
                return [$items->first()->MediaType => $items->pluck('total', 'tpi')->toArray()];
            })
            ->toArray();

        $commercialQualityData = [];
        $columnWiseSums = array_fill_keys($tpis, 0);
        $rowCount = count($mediaTypes);

        // Compute your data (calculations remain unchanged)
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

            // Compute Row-wise Grand Total Percentage
            if ($percentageSum > 0) {
                $firstTpi = reset($tpis);
                $commercialQualityData[$mediaType]['Grand Total Row %'] = round(
                    ($percentageSum / ($fixedTotals[$mediaType][$firstTpi] ?? 1)) * 100,
                    2
                );
            }
        }

        // Compute Column-wise Percentages
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

        // Apply the Excel-like Adjusted Percentage Formula
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

        // Media Type mapping for label replacement
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

        $commercialQualityData = collect($commercialQualityData)->mapWithKeys(function ($data, $mediaType) use ($mediaTypeMapping) {
            $cleanedMediaType = trim($mediaType);
            $newMediaType = $mediaTypeMapping[$cleanedMediaType] ?? $cleanedMediaType;
            return [$newMediaType => $data];
        })->toArray();

        $commercialQualityData['Column Percentages'] = $columnWisePercentages;
        $dataMessage = empty($commercialQualityData) ? "No data available to display." : null;

        // When an AJAX request is made, return only the table section HTML from this view.
        if ($request->ajax()) {
            // Render the section named "table" from this view.
            $html = view('index-chart', compact(
                'breadcrumb',
                'commercialQualityData',
                'dataMessage',
                'distinctValues',
                'mediaTypeMapping'
            ))->renderSections()['table'];
            return response()->json(['html' => $html]);
        }

        // For a normal request, render the full view.
        return view('index-chart', compact(
            'breadcrumb',
            'commercialQualityData',
            'dataMessage',
            'distinctValues',
            'mediaTypeMapping',
            'filterLabels'
        ));
    }
}
