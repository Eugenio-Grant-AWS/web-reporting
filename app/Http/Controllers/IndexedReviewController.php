<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexedReviewController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Indexed Review of Stronger Drivers';

        // Example data - replace with your actual data retrieval logic
        $tools = [
            'Figma',
            'Sketch',
            'XD',
            'Photoshop',
            'Illustrator',
            'AfterEffect',
        ];

        $prices = [
            150,
            130,
            95,
            130,
            140,
            100,
        ];

        // Prepare data for the chart (no need to use strtotime)
        $commercialQualityData = [];
        foreach ($tools as $index => $tool) {
            $commercialQualityData[] = [$tool, $prices[$index]];
        }

        // Check if the data is empty and set a message accordingly
        $dataMessage = empty($commercialQualityData) ? "No data available to display." : null;

        return view('index-chart', compact('breadcrumb', 'commercialQualityData', 'dataMessage'));
    }
}