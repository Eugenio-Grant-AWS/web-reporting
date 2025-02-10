<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TouchpointInfluence;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class TouchpointInfluenceController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = 'Touchpoint Influence';

        $mediaTypes = TouchpointInfluence::distinct()->pluck('MediaType');
        $tpis = TouchpointInfluence::distinct()->pluck('tpi')->toArray();

        // Fetch the unique filter options
        $uniqueRespoSer = TouchpointInfluence::distinct()->pluck('RespoSer');
        $uniqueGender = TouchpointInfluence::distinct()->pluck('QuotGene');
        $uniqueAge = TouchpointInfluence::distinct()->pluck('QuotEdad');
        $uniqueQuoSegur = TouchpointInfluence::distinct()->pluck('QuoSegur');
        $uniqueMediaType = TouchpointInfluence::distinct()->pluck('MediaType');
        $uniqueIndex = TouchpointInfluence::distinct()->pluck('index');
        $uniqueInfluence = TouchpointInfluence::distinct()->pluck('Influence');
        $uniquetpi = TouchpointInfluence::distinct()->pluck('tpi');

        // Start query to fetch data
        $mediaData = TouchpointInfluence::query();

        // Aplicar based on selected options from the request
        if ($request->has('uniqueRespoSer') && !empty($request->uniqueRespoSer)) {
            $mediaData = $mediaData->whereIn('RespoSer', $request->uniqueRespoSer);
        }
        if ($request->has('uniqueGender') && !empty($request->uniqueGender)) {
            $mediaData = $mediaData->whereIn('QuotGene', $request->uniqueGender);
        }
        if ($request->has('uniqueAge') && !empty($request->uniqueAge)) {
            $mediaData = $mediaData->whereIn('QuotEdad', $request->uniqueAge);
        }
        if ($request->has('uniqueQuoSegur') && !empty($request->uniqueQuoSegur)) {
            $mediaData = $mediaData->whereIn('QuoSegur', $request->uniqueQuoSegur);
        }
        // UPDATED MediaType filter using REPLACE to remove newlines
        if ($request->has('uniqueMediaType') && !empty($request->uniqueMediaType)) {
            $filterValues = array_map('trim', $request->uniqueMediaType);
            $placeholders = implode(',', array_fill(0, count($filterValues), '?'));
            $mediaData = $mediaData->whereRaw(
                "REPLACE(REPLACE(MediaType, '\r', ''), '\n', '') IN ($placeholders)",
                $filterValues
            );
        }
        if ($request->has('uniquetpi') && !empty($request->uniquetpi)) {
            $mediaData = $mediaData->whereIn('tpi', $request->uniquetpi);
        }
        if ($request->has('uniqueInfluence') && !empty($request->uniqueInfluence)) {
            $mediaData = $mediaData->whereIn('Influence', $request->uniqueInfluence);
        }
        if ($request->has('uniqueindex') && !empty($request->uniqueindex)) {
            $mediaData = $mediaData->whereIn('index', $request->uniqueindex);
        }

        // Now apply the same aggregation logic as before
        $fixedTotals = $mediaData->select('MediaType', 'tpi', DB::raw('COUNT(*) as total'))
            ->groupBy('MediaType', 'tpi')
            ->get()
            ->groupBy('MediaType')
            ->map(function ($items) {
                return $items->pluck('total', 'tpi')->toArray();
            })
            ->toArray();

        $influenceCounts = $mediaData->where('influence', 1)
            ->select('MediaType', 'tpi', DB::raw('COUNT(*) as total'))
            ->groupBy('MediaType', 'tpi')
            ->get()
            ->groupBy('MediaType')
            ->map(function ($items) {
                return $items->pluck('total', 'tpi')->toArray();
            })
            ->toArray();

        // Continue with the same logic for computing the data
        $commercialQualityData = [];
        $columnWiseSums = array_fill_keys($tpis, 0);
        $rowCount = count($mediaTypes);

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

            if ($percentageSum > 0) {
                $firstTpi = reset($tpis);
                $commercialQualityData[$mediaType]['Grand Total Row %'] = round(
                    ($percentageSum / ($fixedTotals[$mediaType][$firstTpi] ?? 1)) * 100,
                    2
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
            $cleanedMediaType = trim($mediaType);
            $newMediaType = $mediaTypeMapping[$cleanedMediaType] ?? $cleanedMediaType;
            return [$newMediaType => $data];
        })->toArray();

        if ($request->ajax()) {
            return response()->json([
                'commercialQualityData' => $commercialQualityData,  // Send filtered data
                'dataMessage' => empty($commercialQualityData) ? "No data available" : null
            ]);
        }
        $commercialQualityData['Column Percentages'] = $columnWisePercentages;

        // Check if data exists
        $dataMessage = collect($commercialQualityData)->isEmpty() ? "No data available to display." : null;

        return view('touchpoint-influence', compact(
            'breadcrumb',
            'commercialQualityData',
            'dataMessage',
            'uniqueRespoSer',
            'uniqueGender',
            'uniqueAge',
            'uniqueQuoSegur',
            'uniqueMediaType',
            'uniqueIndex',
            'uniqueInfluence',
            'uniquetpi'
        ));
    }
}
