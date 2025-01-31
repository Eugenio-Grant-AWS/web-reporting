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

        // Fetch counts for all media types and TPIs in one query
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

        // Compute Grand Total Column-Wise Percentage
        if ($columnWiseGrandTotal > 0) {
            $columnWisePercentages['Grand Total Column %'] = round(($columnWiseGrandTotal / count($columnWisePercentages)), 2);
        }

        $commercialQualityData['Column Percentages'] = $columnWisePercentages;

        // dd($commercialQualityData);
        $dataMessage = collect($commercialQualityData)->isEmpty() ? "No data available to display." : null;

        return view('touchpoint-influence', compact('breadcrumb', 'commercialQualityData', 'dataMessage'));
    }
}