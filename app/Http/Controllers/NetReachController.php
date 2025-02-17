<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NetReachController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = 'Net Reach';

        // --- Top Row Selection ---
        // Default selected categories (for the three media channel columns used in calculation)
        $defaultCategories = [
            'ver_tv_senal_nacional',
            'ver_tv_cable',
            'ver_tv_internet',
        ];

        // Save selected top row columns in session; require at least 3 selections.
        session()->forget('selected_top_row');
        $selectedCategories = $request->input('top_row', $defaultCategories);
        session(['selected_top_row' => $selectedCategories]);

        if (count($selectedCategories) < 3) {
            return response()->json(['message' => 'At least 3 categories must be selected.'], 400);
        }

        // --- Apply Filters ---
        // Define extra filterable columns.
        $additionalFilterColumns = [
            'QuotGene', // Ensure these match the case used in mappings
            'QuotEdad',
            'QuoSegur',
        ];
        // Retrieve distinct options (from the entire table) for each additional filter.
        $additionalFilterOptions = [];
        foreach ($additionalFilterColumns as $col) {
            $additionalFilterOptions[$col] = DB::table('consumers_reacheds')->distinct()->pluck($col);
        }
        // Define a mapping for display labels (change these to English)

        $filterLabelMapping = [
            'QuotGene'  => 'Género',
            'QuotEdad'  => 'Edad',
            'QuoSegur'  => 'Seguro',
        ];

        $optionTitleMapping = [
            'QuotGene' => [
                '1' => 'Masculino',
                '2' => 'Femenino',
            ],
            'QuotEdad' => [
                '2' => '25-34',
                '3' => '35-44',
                '4' => '45-54',
                '5' => '55-65',
            ],
            'QuoSegur' => [
                '1'  => 'Vida',
                '2'  => 'Salud',
                '3'  => 'Auto',
            ],
        ];
        // Build the consumers query and apply Apply Filters if present.
        $query = DB::table('consumers_reacheds');
        foreach ($additionalFilterColumns as $col) {
            // Expect the filter input name to be "filter_{column}"
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
        $consumers = $query->get();
        $total_responses = $consumers->count() ?: 1;

        // --- Calculation based on selected top row columns ---
        // Compute counts for each combination using the three selected media channels.
        $dataCounts = [];
        $dataCounts[$selectedCategories[0]] = $consumers->where($selectedCategories[0], 1)
            ->where($selectedCategories[1], 0)
            ->where($selectedCategories[2], 0)
            ->count();
        $dataCounts[$selectedCategories[1]] = $consumers->where($selectedCategories[0], 0)
            ->where($selectedCategories[1], 1)
            ->where($selectedCategories[2], 0)
            ->count();
        $dataCounts[$selectedCategories[2]] = $consumers->where($selectedCategories[0], 0)
            ->where($selectedCategories[1], 0)
            ->where($selectedCategories[2], 1)
            ->count();
        $dataCounts[$selectedCategories[0] . ' + ' . $selectedCategories[1]] = $consumers->where($selectedCategories[0], 1)
            ->where($selectedCategories[1], 1)
            ->where($selectedCategories[2], 0)
            ->count();
        $dataCounts[$selectedCategories[0] . ' + ' . $selectedCategories[2]] = $consumers->where($selectedCategories[0], 1)
            ->where($selectedCategories[1], 0)
            ->where($selectedCategories[2], 1)
            ->count();
        $dataCounts[$selectedCategories[1] . ' + ' . $selectedCategories[2]] = $consumers->where($selectedCategories[0], 0)
            ->where($selectedCategories[1], 1)
            ->where($selectedCategories[2], 1)
            ->count();
        $dataCounts['All Three'] = $consumers->where($selectedCategories[0], 1)
            ->where($selectedCategories[1], 1)
            ->where($selectedCategories[2], 1)
            ->count();

        // Calculate percentages for each combination
        $dataPercentages = [];
        foreach ($dataCounts as $key => $count) {
            $dataPercentages[$key] = round(($count / $total_responses) * 100, 1);
        }

        // Prepare chart data in the expected format.
        $chartData = [
            'labels' => array_map(function ($key) use ($dataPercentages) {
                return $key . ' (' . $dataPercentages[$key] . '%)';
            }, array_keys($dataCounts)),
            'datasets' => [
                [
                    'label' => 'Net Reach',
                    'data' => array_map(function ($key) use ($dataPercentages) {
                        return ['sets' => explode(' + ', $key), 'value' => $dataPercentages[$key]];
                    }, array_keys($dataPercentages)),
                    'backgroundColor' => ['#ffdcae', '#a5d7a7', '#cba3ff', '#8ba9a7', '#a5c67f', '#e5aeae', '#bfccaf'],
                ],
            ],
        ];

        // For AJAX requests, return the new chart data as JSON.
        if ($request->ajax()) {
            return response()->json(['chartData' => $chartData]);
        }
        // dd($optionTitleMapping);
        $dataMessage = $consumers->isEmpty() ? "No data available to display." : null;

        return view('net-reach', compact(
            'chartData',
            'dataMessage',
            'breadcrumb',
            'selectedCategories',
            'additionalFilterOptions',
            'filterLabelMapping',
            'optionTitleMapping'
        ));
    }
}