<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class TouchpointInfluenceController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Touchpoint Influence';

        $commercialQualityData = $this->getCommercialQualityData();

        $dataMessage = empty($commercialQualityData['datasets']) || empty($commercialQualityData['labels'])
            ? "No data available to display !"
            : null;

        $chart = null;

        if (!$dataMessage) {
            $chart = $this->buildChart($commercialQualityData);
        }
        return view('touchpoint-influence', compact('chart', 'breadcrumb', 'dataMessage'));
    }


    /**
     * Prepare the commercial quality data for chart rendering.
     */
    private function getCommercialQualityData()
    {
        return [
            'labels' => ['Figma', 'Sketch', 'XD', 'Photoshop', 'Illustrator', 'AfterEffect', 'InDesign', 'Maya', 'Premiere', 'Final Cut', 'Figma', 'Sketch'],
            'datasets' => [
                [
                    'label' => '2021',
                    'type' => 'line',
                    'backgroundColor' => '#ffffff',
                    'borderColor' => 'rgba(0, 123, 255, 0.3)',
                    'borderWidth' => 2,
                    'fill' => false,
                    'data' => [80, 60, 78, 75, 77, 84, 98, 77, 78, 79, 92, 62],
                    'yAxisID' => 'y',
                ],
                [
                    'label' => '2021 ',
                    'backgroundColor' => '#7ac0f8',
                    'borderColor' => 'rgba(33, 150, 243, 1)',
                    'borderWidth' => 1,
                    'data' => [70, 18, 35, 20, 40, 15, 58, 18, 58, 56, 92, 41],
                    'yAxisID' => 'y2',
                ]
            ]
        ];
    }



    /**
     * Build the chart using the provided data.
     */
    private function buildChart($commercialQualityData)
    {
        return Chartjs::build()
            ->name('reachExposureChart')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels($commercialQualityData['labels'])
            ->datasets($commercialQualityData['datasets'])
            ->options([
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                    ],
                ],
                'y2' => [
                    'position' => 'right',
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => new \Illuminate\Support\HtmlString('function(value) { return value; }'),  // Optional: Format tick labels
                    ],
                ],
                'plugins' => [
                    'legend' => [
                        'position' => 'bottom',
                    ],
                ],
                'layout' => [
                    'padding' => 10,
                ],
            ]);
    }
}