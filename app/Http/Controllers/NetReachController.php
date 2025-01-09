<?php

namespace App\Http\Controllers;

class NetReachController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Net Reach';

        $vennColors = [
            'A' => '#ffdcae',  // A background color
            'B' => '#a5d7a7',   // B background color
            'C' => '#cba3ff',  // C background color
        ];

        $commercialQualityData = [
            [
                'label' => 'B',
                'values' => ['alex', 'casey', 'drew', 'hunter']
            ],
            [
                'label' => 'A',
                'values' => ['casey', 'drew', 'jade']
            ],
            [
                'label' => 'C',
                'values' => ['drew', 'glen', 'jade']
            ]
        ];


        // The check ensures that we only access the data if it's available
        $dataMessage = null;

        if (
            empty($commercialQualityData) ||
            !isset($commercialQualityData[0]['values']) ||
            !isset($commercialQualityData[1]['values']) ||
            !isset($commercialQualityData[2]['values'])
        ) {
            $dataMessage = "No data available to display.";  // Error message if data is missing
        }

        // Always initialize chartData
        $chartData = null;

        if (!$dataMessage) {
            // Only proceed if there is valid data
            $chartData = [
                'labels' => ['B', 'C', 'A', 'B ∩ C', 'B ∩ A', 'C ∩ A', 'B ∩ C ∩ A'],
                'datasets' => [
                    [
                        'label' => 'Net Reach',
                        'data' => [
                            // **SAFE ACCESS TO DATA WITH isset()**
                            ['sets' => ['B'], 'value' => count($commercialQualityData[0]['values'])],
                            ['sets' => ['C'], 'value' => count($commercialQualityData[1]['values'])],
                            ['sets' => ['A'], 'value' => count($commercialQualityData[2]['values'])],
                            ['sets' => ['B', 'C'], 'value' => count(array_intersect($commercialQualityData[0]['values'], $commercialQualityData[1]['values']))],
                            ['sets' => ['B', 'A'], 'value' => count(array_intersect($commercialQualityData[1]['values'], $commercialQualityData[0]['values']))],
                            ['sets' => ['C', 'A'], 'value' => count(array_intersect($commercialQualityData[1]['values'], $commercialQualityData[2]['values']))],
                            ['sets' => ['B', 'C', 'A'], 'value' => count(array_intersect($commercialQualityData[0]['values'], $commercialQualityData[1]['values'], $commercialQualityData[2]['values']))],
                        ],

                        'backgroundColor' => [
                            $vennColors['B'],
                            $vennColors['C'],
                            $vennColors['A'],
                            '#8ba9a7',
                            '#a5c67f',
                            '#e5aeae',
                            '#bfccaf',
                        ],
                    ]
                ]
            ];
        }

        // Pass the data to the view
        return view('net-reach', compact('chartData', 'breadcrumb', 'dataMessage'));
    }
}