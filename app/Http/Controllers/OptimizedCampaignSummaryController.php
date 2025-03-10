<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OptimizedCampaignSummaryController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Optimized Campaign Summary';


        $commercialQualityData = [
            // ['id' => 1, 'name' => 'Quality A', 'value' => 'High'],
            // ['id' => 2, 'name' => 'Quality B', 'value' => 'Medium'],
            // ['id' => 3, 'name' => 'Quality C', 'value' => 'Low'],
        ];

        $showNoDataMessage = empty($commercialQualityData)
            ? "Commercial quality variable does not exist."
            : "";

        $mindMapData1 = [
            "meta" => [
                "name" => "flowchart",
                "author" => "user",
                "version" => "1.0"
            ],
            "format" => "node_tree",
            "data" => [
                "id" => "root",
                "topic" => "Arc...",
                "children" => [
                    ["id" => "parallel", "topic" => "Parallel stages handling data..."],
                    ["id" => "segmented", "topic" => "Segmented flow of work"],
                    ["id" => "efficiency", "topic" => "Efficiency in parallel processing"]
                ]
            ]
        ];

        $mindMapData2 = [
            "meta" => [
                "name" => "architecture",
                "author" => "user",
                "version" => "1.0"
            ],
            "format" => "node_tree",
            "data" => [
                "id" => "root_2",
                "topic" => "Arc...",
                "children" => [
                    [
                        "id" => "layers",
                        "topic" => "Illustrate a simple example of how system components relate in the Pipeline Architecture",
                        "direction" => "left"
                    ],

                ]
            ]
        ];


        return view('optimized-campaign-summary', compact('breadcrumb', 'mindMapData1', 'mindMapData2', 'showNoDataMessage'));
    }
}