<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class CampaignSummaryChartController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Advertising Attention by Touchpoint';

        $chart = Chartjs::build()
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['January', 'February', 'March', 'April', 'May'])
            ->datasets([
                [
                    'label' => 'My Bar Dataset',
                    // Bar chart data
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)'
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    'borderWidth' => 1,
                    'data' => [65, 59, 80, 81, 56],
                ],
                [
                    'label' => 'My Line Dataset',
                    // Line chart data
                    'type' => 'line',
                    'backgroundColor' => 'rgba(0, 123, 255, 0.3)',
                    'borderColor' => 'rgba(0, 123, 255, 1)',
                    'borderWidth' => 2,
                    'fill' => false,
                    'data' => [80, 70, 80, 89, 60],
                ]
            ])
            ->options([
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                    ]
                ],
            ]);

        return view('attentive-exposure', compact('chart', 'breadcrumb'));
    }
}