<?php

namespace App\Http\Controllers;

class VennDiagramController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Net Reach';

        $vennData = [
            [
                'label' => 'Soccer',
                'values' => ['alex', 'casey', 'drew', 'hunter']
            ],
            [
                'label' => 'Tennis',
                'values' => ['casey', 'drew', 'jade']
            ],
            [
                'label' => 'Volleyball',
                'values' => ['drew', 'glen', 'jade']
            ]
        ];

        $chartData = [
            'labels' => ['Soccer', 'Tennis', 'Volleyball', 'Soccer ∩ Tennis', 'Soccer ∩ Volleyball', 'Tennis ∩ Volleyball', 'Soccer ∩ Tennis ∩ Volleyball'],
            'datasets' => [
                [
                    'label' => 'Sports',
                    'data' => [
                        ['sets' => ['Soccer'], 'value' => count($vennData[0]['values'])],
                        ['sets' => ['Tennis'], 'value' => count($vennData[1]['values'])],
                        ['sets' => ['Volleyball'], 'value' => count($vennData[2]['values'])],
                        ['sets' => ['Soccer', 'Tennis'], 'value' => count(array_intersect($vennData[0]['values'], $vennData[1]['values']))],
                        ['sets' => ['Soccer', 'Volleyball'], 'value' => count(array_intersect($vennData[0]['values'], $vennData[2]['values']))],
                        ['sets' => ['Tennis', 'Volleyball'], 'value' => count(array_intersect($vennData[1]['values'], $vennData[2]['values']))],
                        ['sets' => ['Soccer', 'Tennis', 'Volleyball'], 'value' => count(array_intersect($vennData[0]['values'], $vennData[1]['values'], $vennData[2]['values']))],
                    ]
                ]
            ]
        ];


        return view('venn-chart', compact('chartData', 'breadcrumb'));
    }
}
