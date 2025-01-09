<?php

namespace App\Http\Controllers;

class AttentiveExposureController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Attentive Exposure';

        // Heatmap data in the required format for ApexCharts
        $data = [
            [
                'name' => 'Series 1',
                'data' => [30, 40, 35, 50, 49, 60, 70, 91]
            ],
            [
                'name' => 'Series 2',
                'data' => [20, 30, 45, 60, 45, 50, 60, 70]
            ],
            [
                'name' => 'Series 3',
                'data' => [40, 30, 50, 30, 42, 60, 80, 90]
            ],
            [
                'name' => 'Series 4',
                'data' => [30, 40, 35, 50, 49, 60, 70, 91]
            ],
            [
                'name' => 'Series 5',
                'data' => [20, 30, 45, 60, 45, 50, 60, 70]
            ],
            [
                'name' => 'Series 6',
                'data' => [40, 30, 50, 30, 42, 60, 80, 90]
            ]
        ];

        // Categories corresponding to the X-axis labels (time intervals)
        $categories = ['10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '01:00', '01:30'];

        $dataMessage = empty($data) ? "No data available to display." : null;

        return view('attentive-exposure', compact('breadcrumb', 'data', 'categories', 'dataMessage'));
    }
}