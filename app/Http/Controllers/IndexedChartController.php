<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexedChartController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Indexed Review of Stronger Drivers';

        // Example data - replace with your actual data retrieval logic
        $dates = [
            '2024-01-01T00:00:00.000Z',
            '2024-01-02T00:00:00.000Z',
            '2024-01-03T00:00:00.000Z',
            '2024-01-05T00:00:00.000Z',
            '2024-01-07T00:00:00.000Z',
            '2024-01-09T00:00:00.000Z',
        ];

        $prices = [
            5000000,
            5400000,
            5200000,
            5700000,
            5400000,
            5600000,
        ];

        $data = array_map(function ($date, $price) {
            return [strtotime($date) * 1000, $price];
        }, $dates, $prices);

        return view('index-chart', compact('breadcrumb', 'data'));
    }
}
