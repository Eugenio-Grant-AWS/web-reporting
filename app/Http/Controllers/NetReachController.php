<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class NetReachController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Net Reach';

        // Define Venn Diagram Colors
        $vennColors = [
            'SOLO Ver TV nacional' => '#ffdcae',
            'SOLO Ver TV por cable' => '#a5d7a7',
            'SOLO Ver TV por internet' => '#cba3ff',
            'Ver TV nacional + Ver TV por Cable' => '#8ba9a7',
            'Ver TV nacional + Ver TV por internet' => '#a5c67f',
            'SOLO Ver TV por cable + Ver TV por internet' => '#e5aeae',
            'All Three' => '#bfccaf'
        ];

        // Get total respondents to calculate percentages
        $total_responses = DB::table('consumers_reacheds')->count();
        $total_responses = max($total_responses, 1); // Prevent division by zero

        // Fetch category counts dynamically
        $dataCounts = [
            'SOLO Ver TV nacional' => DB::table('consumers_reacheds')
                ->where('ver_tv_senal_nacional', 1)
                ->where('ver_tv_cable', 0)
                ->where('ver_tv_internet', 0)
                ->count(),

            'SOLO Ver TV por cable' => DB::table('consumers_reacheds')
                ->where('ver_tv_senal_nacional', 0)
                ->where('ver_tv_cable', 1)
                ->where('ver_tv_internet', 0)
                ->count(),

            'SOLO Ver TV por internet' => DB::table('consumers_reacheds')
                ->where('ver_tv_senal_nacional', 0)
                ->where('ver_tv_cable', 0)
                ->where('ver_tv_internet', 1)
                ->count(),

            'Ver TV nacional + Ver TV por Cable' => DB::table('consumers_reacheds')
                ->where('ver_tv_senal_nacional', 1)
                ->where('ver_tv_cable', 1)
                ->where('ver_tv_internet', 0)
                ->count(),

            'Ver TV nacional + Ver TV por internet' => DB::table('consumers_reacheds')
                ->where('ver_tv_senal_nacional', 1)
                ->where('ver_tv_cable', 0)
                ->where('ver_tv_internet', 1)
                ->count(),

            'SOLO Ver TV por cable + Ver TV por internet' => DB::table('consumers_reacheds')
                ->where('ver_tv_senal_nacional', 0)
                ->where('ver_tv_cable', 1)
                ->where('ver_tv_internet', 1)
                ->count(),

            'All Three' => DB::table('consumers_reacheds')
                ->where('ver_tv_senal_nacional', 1)
                ->where('ver_tv_cable', 1)
                ->where('ver_tv_internet', 1)
                ->count(),
        ];

        // Calculate percentages
        $dataPercentages = [];
        foreach ($dataCounts as $key => $count) {
            $dataPercentages[$key] = round(($count / $total_responses) * 100, 2);
        }

        // Convert data to match the required structure with percentages included
        $chartData = [
            'labels' => array_keys($dataCounts),
            'datasets' => [
                [
                    'label' => 'Net Reach',
                    'data' => array_map(function ($key) use ($dataCounts, $dataPercentages) {
                        return [
                            'sets' => explode(' + ', $key),
                            'value' => $dataCounts[$key],
                            'percentage' => $dataPercentages[$key] . '%'
                        ];
                    }, array_keys($dataCounts)),
                    'backgroundColor' => array_values($vennColors)
                ]
            ]
        ];
        // Check if data is available
        $dataMessage = ($total_responses === 0) ? "No data available to display." : null;

        // Pass the data to the view
        return view('net-reach', compact('chartData', 'breadcrumb', 'dataMessage'));
    }
}