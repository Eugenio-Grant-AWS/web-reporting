<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConsumersReached;
use Illuminate\Support\Facades\DB;

class UnduplicatedNetReachController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = ' Unduplicated Net Reach';

        $defaultSelection = [

            'ver_tv_senal_nacional' => 1,
            'escuchar_radio' => 1,
            'leer_periodico_impreso' => 1,
            'leer_revista_impresa' => 1,
        ];

        session()->forget('selected_top_row');


        $selectedValues = $request->input('top_row', $defaultSelection);
        session(['selected_top_row' => $selectedValues]);

        $labels = array_keys($selectedValues);


        $dataRows = DB::table('consumers_reacheds')
            ->select($labels)
            ->get();

        $cumulativePercentages = [];
        $marginalPercentages = [];

        $previousReach = 0;
        $totalCount = count($dataRows);
        $cumulativeTracker = array_fill(0, $totalCount, 0);

        $reachCounts = [];

        foreach ($selectedValues as $column => $topValue) {
            $reachCount = 0;
            foreach ($dataRows as $rowIndex => $row) {
                $rowArray = (array) $row;
                if (($rowArray[$column] ?? 0) * $topValue > 0) {
                    $reachCount++;
                }
            }
            $reachCounts[$column] = $reachCount;
        }


        arsort($reachCounts);

        // Step 3: Process columns in sorted order
        $cumulativeTracker = array_fill(0, $totalCount, 0);
        $cumulativePercentages = [];
        $marginalPercentages = [];
        $previousReach = 0;

        foreach ($reachCounts as $column => $reachCount) {
            foreach ($dataRows as $rowIndex => $row) {
                $rowArray = (array) $row;
                if (($rowArray[$column] ?? 0) * $selectedValues[$column] > 0) {
                    if ($cumulativeTracker[$rowIndex] == 0) {
                        $cumulativeTracker[$rowIndex] = 1;
                    }
                }
            }

            $cumulativeReach = array_sum($cumulativeTracker);
            $marginalReach = $cumulativeReach - $previousReach;

            $marginalPercentages[] = round(($marginalReach / $totalCount) * 100, 2);
            $cumulativePercentages[] = round(($cumulativeReach / $totalCount) * 100, 2);

            // Update the previous reach for the next iteration
            $previousReach = $cumulativeReach;
        }

        $commercialQualityData = [
            'labels' => array_keys($reachCounts),
            'marginal' => $marginalPercentages,
            'cumulative' => $cumulativePercentages,
        ];

        if ($request->ajax()) {
            return response()->json([
                'commercialQualityData' => $commercialQualityData
            ]);
        }

        $dataMessage = count($dataRows) === 0 ? "No data available to display." : null;

        return view('unduplicated-net-reach', compact('commercialQualityData', 'dataMessage', 'breadcrumb'));
    }
}
