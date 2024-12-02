<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class PieChartController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Net % of Consumers Reached';
        $title = 'Reach Exposure / Probability with Mean';

        $chart = Chartjs::build()
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['January', 'February', 'March', 'April', 'May'])
            ->datasets([
                [
                    'label' => 'My Dataset',
                    'backgroundColor' => ['rgba(75,192,192,0.6)', 'rgba(153,102,255,0.6)', 'rgba(255,159,64,0.6)', 'rgba(54,162,235,0.6)', 'rgba(255,99,132,0.6)'],
                    'hoverBackgroundColor' => ['rgba(75,192,192,1)', 'rgba(153,102,255,1)', 'rgba(255,159,64,1)', 'rgba(54,162,235,1)', 'rgba(255,99,132,1)'],
                    'data' => [65, 59, 80, 81, 56],
                ]
            ])
            ->options([
                'plugins' => [
                    'legend' => [
                        'position' => 'top',
                    ],
                ],
            ]);

        return view('pie-chart', compact('chart', 'title', 'breadcrumb'));
    }
}