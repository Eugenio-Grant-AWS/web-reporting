<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class Reach_X_AttentionPlotController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Reach x Attention Plot';

        $mediaData = DB::table('reach_attention_plots')
            ->select(
                'MediaType',
                DB::raw('ROUND(100.0 * COUNT(CASE WHEN Reach = "Reached" THEN 1 END) / COUNT(*), 2) as reachPercentage'),
                DB::raw('ROUND(100.0 * COUNT(CASE WHEN Attention = "Yes" THEN 1 END) / COUNT(*), 2) as attentionPercentage')
            )
            ->groupBy('MediaType')
            ->get();

        // Convert data to an array formatted for the chart
        $chartData = $mediaData->map(function ($data) {
            return [
                'x' => (float) $data->reachPercentage,  // X-axis (Reach)
                'y' => (float) $data->attentionPercentage,  // Y-axis (Attention)
                'label' => $data->MediaType, // Label for annotation
            ];
        });
        // dd($chartData);
        // Check if data is available
        $dataMessage = empty($chartData) ? "No data available to display." : null;

        return view('reach-x-attention-plot', compact('chartData', 'breadcrumb', 'dataMessage'));
    }
}