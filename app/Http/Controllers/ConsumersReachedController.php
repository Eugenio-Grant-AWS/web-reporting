<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class ConsumersReachedController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Net % of Consumers Reached';

        $commercialQualityData = [
            'labels' => ['Figma', 'Sketch', 'Photoshop', 'Illustrator', 'AfterEffect', 'InDesign', 'Maya'],
            'datasets' => [
                [
                    'data' => [88, 74, 60, 43, 28, 13, 17],
                ]
            ]

        ];

        $dataMessage = empty($commercialQualityData['datasets']) || empty($commercialQualityData['labels'])
            ? "No data available to display."
            : null;

        return view('consumers-reached', compact('commercialQualityData', 'breadcrumb', 'dataMessage'));
    }

    /**
     * Build the chart using the provided data.
     */
    // private function buildChart($commercialQualityData)
    // {
    //     return $chart = Chartjs::build()
    //         ->name('pieChartTest')
    //         ->type('pie')
    //         ->size(['width' => 400, 'height' => 200])
    //         ->labels($commercialQualityData['labels'])
    //         ->datasets($commercialQualityData['datasets'])
    //         ->options([
    //             'plugins' => [
    //                 'legend' => [
    //                     'position' => 'right',
    //                 ],
    //                 'tooltip' => [
    //                     'enabled' => true,
    //                 ],
    //                 'datalabels' => [
    //                     'display' => true,
    //                     //'backgroundColor' => '#fff',
    //                     //'borderRadius' => 4,

    //                 ],
    //             ],
    //         ]);
    // }
}