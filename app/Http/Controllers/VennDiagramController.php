<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

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

        return view('venn-chart', compact('vennData', 'breadcrumb'));
    }
}