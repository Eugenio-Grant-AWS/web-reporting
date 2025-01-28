<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AttentiveExposureController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Media Exposure';

        $mediaData = DB::table('attentive_exposures')->select('MediaType', 'attentive_exposure')->get();

        $groupedData = $mediaData->groupBy('MediaType');
        $averages = $groupedData->map(function ($group) {
            $total = $group->sum('attentive_exposure');
            $count = $group->count();
            return $count > 0 ? round(($total / $count) * 100, 2) : 0;
        });
        // Prepare data for the chart
        $chartData = [
            'categories' => $averages->keys()->toArray(), // Media types
            'percentages' => $averages->values()->toArray(), // Average exposure percentages
        ];

        $dataMessage = empty($chartData['categories']) ? "No data available to display." : null;

        // Pass data to the view
        return view('attentive-exposure', compact('breadcrumb', 'chartData', 'dataMessage'));
    }
}