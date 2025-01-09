<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class Reach_X_AttentionPlotController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Reach x Attention Plot';

        // Scatter plot data, with each data point having x and y values
        $commercialQualityData = [
            [
                'label' => 'My Scatter Dataset 1',
                'backgroundColor' => '#f44336',
                'borderColor' => '#f44336',
                'borderWidth' => 8,
                'data' => [
                    ['x' => 0.3, 'y' => 10],
                    ['x' => 1.3, 'y' => 20],
                    ['x' => 2, 'y' => 30],
                    ['x' => 2.4, 'y' => 51],
                    ['x' => 5, 'y' => 56],
                ],
            ],
            [
                'label' => 'My Scatter Dataset 2',
                'backgroundColor' => '#f44336',
                'borderColor' => '#f44336',
                'borderWidth' => 10,
                'data' => [
                    ['x' => 0.5, 'y' => 15],
                    ['x' => 1.5, 'y' => 25],
                    ['x' => 2.3, 'y' => 40],
                    ['x' => 3, 'y' => 50],
                    ['x' => 4, 'y' => 60],
                ],
            ]
        ];

        // Check if there is no data
        $dataMessage = empty($commercialQualityData[0]['data']) && empty($commercialQualityData[1]['data'])
            ? "No data available to display."
            : null;

        // Initialize chart if data exists
        $chart = null;

        if (!$dataMessage) {

            $chart = $this->buildChart($commercialQualityData);
        }

        // Return the view with data, chart, breadcrumb, and dataMessage
        return view('reach-x-attention-plot', compact('chart', 'breadcrumb', 'dataMessage'));
    }


    /**
     * Build the chart using the provided data.
     */
    private function buildChart($commercialQualityData)
    {
        return   $chart = Chartjs::build()
            ->name('scatterPlotTest')
            ->type('scatter')
            ->size(['width' => 400, 'height' => 200])
            ->datasets($commercialQualityData)
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
    }
}