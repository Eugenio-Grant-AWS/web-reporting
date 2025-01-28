<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class ScatterPlotController extends Controller
{
    public function index()
    {

        // $breadcrumb = 'Reach x Attention Plot';

        $query = DB::table('reach_attention_plots')
            ->select('media_type AS MediaType', 'reach_percentage AS Reach', 'attention_percentage AS Attention')
            ->get();

        $chart = Chartjs::build()
            ->name('scatterPlotTest')
            ->type('scatter') // Change to scatter plot type
            ->size(['width' => 400, 'height' => 200])
            ->datasets([
                [
                    'label' => 'My Scatter Dataset 1',
                    // Scatter data points, each point has an x and y value
                    'backgroundColor' => 'rgba(255, 99, 132, 0.6)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                    'data' => [
                        ['x' => 1, 'y' => 65],
                        ['x' => 2, 'y' => 59],
                        ['x' => 3, 'y' => 80],
                        ['x' => 4, 'y' => 81],
                        ['x' => 5, 'y' => 56],
                    ],
                ],
                [
                    'label' => 'My Scatter Dataset 2',
                    // Another scatter data with different x, y pairs
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                    'data' => [
                        ['x' => 1, 'y' => 50],
                        ['x' => 2, 'y' => 70],
                        ['x' => 3, 'y' => 40],
                        ['x' => 4, 'y' => 60],
                        ['x' => 5, 'y' => 80],
                    ],
                ]
            ])
            ->options([
                'scales' => [
                    'x' => [
                        'beginAtZero' => true,
                        'type' => 'linear',
                        'position' => 'bottom',
                    ],
                    'y' => [
                        'beginAtZero' => true,
                    ]
                ],
            ]);

        return view('scatter-plot', compact('chart', 'breadcrumb'));
    }
}