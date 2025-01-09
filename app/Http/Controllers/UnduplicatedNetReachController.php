<?php

namespace App\Http\Controllers;

use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class UnduplicatedNetReachController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Unduplicated Net Reach';

        $commercialQualityData = [
            'labels' => [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ],
            'datasets' => [
                [
                    "label" => 2021,
                    'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                    'borderColor' => "rgba(38, 185, 154, 0.7)",
                    "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                    "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                    "pointHoverBackgroundColor" => "#fff",
                    "pointHoverBorderColor" => "rgba(220,220,220,1)",
                    "data" => [29.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                    "fill" => false,
                ],
                [
                    "label" => 2021,
                    'backgroundColor' => "#ff928a",
                    'borderColor' => "#ff928a",
                    "pointBorderColor" => "#ff928a",
                    "pointBackgroundColor" => "#ffff",
                    "pointHoverBackgroundColor" => "#fff",
                    "pointHoverBorderColor" => "rgba(220,220,220,1)",
                    "data" => [12, 33, 44, 44, 55, 23, 40],
                    "fill" => false,
                ]
            ]

        ];

        $dataMessage = empty($commercialQualityData['datasets']) || empty($commercialQualityData['labels'])
            ? "No data available to display."
            : null;


        $chart = null;
        if (!$dataMessage) {
            $chart = $this->buildChart($commercialQualityData);
        }


        return view('unduplicated-net-reach', compact('chart', 'breadcrumb', 'dataMessage'));
    }


    /**
     * Build the chart using the provided data.
     */
    private function buildChart($commercialQualityData)
    {
        return   $chart = Chartjs::build()
            ->name('lineChartTest')
            ->type('line')
            ->size(['width' => 400, 'height' => 200])
            ->labels($commercialQualityData['labels'])
            ->datasets($commercialQualityData['datasets'])->options([
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                    ]
                ],
                'plugins' => [
                    'legend' => [
                        'position' => 'bottom',
                    ],
                ],
            ]);
    }
}