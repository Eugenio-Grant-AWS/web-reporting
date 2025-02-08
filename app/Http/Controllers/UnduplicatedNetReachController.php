<?php

namespace App\Http\Controllers;

use App\Models\ConsumersReached;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnduplicatedNetReachController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = 'Unduplicated Net Reach';

        // --- Top Row Selection ---
        // Default selection for the media channel columns used in calculation.
        $defaultSelection = [
            'ver_tv_senal_nacional' => 1,
            'escuchar_radio' => 1,
            'leer_periodico_impreso' => 1,
            'leer_revista_impresa' => 1,
        ];

        session()->forget('selected_top_row');
        $selectedValues = $request->input('top_row', $defaultSelection);

        // If $selectedValues is an indexed array, transform it into an associative array with multiplier 1.
        if (array_keys($selectedValues) === range(0, count($selectedValues) - 1)) {
            $selectedValues = array_combine($selectedValues, array_fill(0, count($selectedValues), 1));
        }
        session(['selected_top_row' => $selectedValues]);

        // Determine the labels (i.e. column names) from the selected values.
        $labels = array_keys($selectedValues);

        // --- Additional Filters ---
        // Define extra filterable columns.
        $additionalFilters = ['RespoSer', 'QuotGene', 'QuotEdad', 'QuoSegur'];
        $additionalFilterOptions = [];
        foreach ($additionalFilters as $col) {
            $additionalFilterOptions[$col] = DB::table('consumers_reacheds')->distinct()->pluck($col);
        }
        $filterLabelMapping = [
            'RespoSer'  => 'Responder',
            'QuotGene'  => 'Gender',
            'QuotEdad'  => 'Age',
            'QuoSegur'  => 'Insurance',
        ];

        // Build the query using the selected top row columns and additional filters.
        $query = DB::table('consumers_reacheds')->select($labels);
        foreach ($additionalFilters as $col) {
            $param = 'filter_' . $col;
            if ($request->filled($param)) {
                $value = $request->input($param);
                if (is_array($value)) {
                    $query->whereIn($col, $value);
                } else {
                    $query->where($col, $value);
                }
            }
        }
        $dataRows = $query->get();
        $totalCount = count($dataRows);

        // --- Calculation of Unduplicated Reach ---
        $reachCounts = [];
        foreach ($selectedValues as $column => $topValue) {
            $reachCount = 0;
            foreach ($dataRows as $rowIndex => $row) {
                $rowArray = (array)$row;
                // Explicitly convert both values to int.
                if (intval($rowArray[$column] ?? 0) * intval($selectedValues[$column]) > 0) {
                    $reachCount++;
                }
            }
            $reachCounts[$column] = $reachCount;
        }

        arsort($reachCounts);

        // Compute cumulative and marginal percentages.
        $cumulativeTracker = array_fill(0, $totalCount, 0);
        $cumulativePercentages = [];
        $marginalPercentages = [];
        $previousReach = 0;

        foreach ($reachCounts as $column => $reachCount) {
            foreach ($dataRows as $rowIndex => $row) {
                $rowArray = (array)$row;
                if (intval($rowArray[$column] ?? 0) * intval($selectedValues[$column]) > 0) {
                    if ($cumulativeTracker[$rowIndex] == 0) {
                        $cumulativeTracker[$rowIndex] = 1;
                    }
                }
            }
            $cumulativeReach = array_sum($cumulativeTracker);
            $marginalReach = $cumulativeReach - $previousReach;
            $marginalPercentages[] = round(($marginalReach / $totalCount) * 100, 2);
            $cumulativePercentages[] = round(($cumulativeReach / $totalCount) * 100, 2);
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

        $dataMessage = $totalCount === 0 ? "No data available to display." : null;

        return view('unduplicated-net-reach', compact(
            'commercialQualityData',
            'dataMessage',
            'breadcrumb',
            'selectedValues',
            'additionalFilterOptions',
            'filterLabelMapping'
        ));
    }
}
