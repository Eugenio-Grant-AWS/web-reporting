<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NetReachController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = 'Net Reach';

        // Default selected categories
        $defaultCategories = [
            'ver_tv_senal_nacional',
            'ver_tv_cable',
            'ver_tv_internet',
        ];

        session()->forget('selected_top_row');
        $selectedCategories = $request->input('top_row', $defaultCategories);
        session(['selected_top_row' => $selectedCategories]);


        if (count($selectedCategories) < 3) {
            return response()->json(['message' => 'At least 3 categories must be selected.'], 400);
        }

        // Fetch consumers data
        $consumers = DB::table('consumers_reacheds')->get();
        $total_responses = $consumers->count() ?: 1;

        // Compute data dynamically based on selected categories
        $dataCounts = [];
        $dataCounts[$selectedCategories[0]] = $consumers->where($selectedCategories[0], 1)->where($selectedCategories[1], 0)->where($selectedCategories[2], 0)->count();
        $dataCounts[$selectedCategories[1]] = $consumers->where($selectedCategories[0], 0)->where($selectedCategories[1], 1)->where($selectedCategories[2], 0)->count();
        $dataCounts[$selectedCategories[2]] = $consumers->where($selectedCategories[0], 0)->where($selectedCategories[1], 0)->where($selectedCategories[2], 1)->count();
        $dataCounts[$selectedCategories[0] . ' + ' . $selectedCategories[1]] = $consumers->where($selectedCategories[0], 1)->where($selectedCategories[1], 1)->where($selectedCategories[2], 0)->count();
        $dataCounts[$selectedCategories[0] . ' + ' . $selectedCategories[2]] = $consumers->where($selectedCategories[0], 1)->where($selectedCategories[1], 0)->where($selectedCategories[2], 1)->count();
        $dataCounts[$selectedCategories[1] . ' + ' . $selectedCategories[2]] = $consumers->where($selectedCategories[0], 0)->where($selectedCategories[1], 1)->where($selectedCategories[2], 1)->count();
        $dataCounts['All Three'] = $consumers->where($selectedCategories[0], 1)->where($selectedCategories[1], 1)->where($selectedCategories[2], 1)->count();

        // Calculate percentages
        $dataPercentages = [];
        foreach ($dataCounts as $key => $count) {
            $dataPercentages[$key] = round(($count / $total_responses) * 100, 1);
        }
        // dd($dataPercentages);
        // Prepare chart data
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
        // dd($chartData);
        // Return the updated chart data as JSON for the AJAX request
        if ($request->ajax()) {
            return response()->json(['chartData' => $chartData]);
        }

        $dataMessage = null;
        return view('net-reach', compact('chartData', 'dataMessage', 'breadcrumb', 'selectedCategories'));
    }
}