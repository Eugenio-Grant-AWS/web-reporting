<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConsumersReached;
use Illuminate\Support\Facades\DB;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class ConsumersReachedController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = 'Net % of Consumers Reached';

        $defaultSelection = [
            'ver_tv_senal_nacional' => 1,


        ];
        session()->forget('selected_top_row');
        $selectedValues = $request->input('top_row', session('selected_top_row', $defaultSelection));

        session(['selected_top_row' => $selectedValues]);

        $dataRows = DB::table('consumers_reacheds')->select(array_keys($selectedValues))->get();

        $results = [];
        foreach ($dataRows as $index => $row) {
            $rowArray = (array) $row;

            $sumProduct = 0;
            foreach ($selectedValues as $column => $topValue) {
                $sumProduct += $topValue * ($rowArray[$column] ?? 0);
            }

            $rowResult = $sumProduct > 0 ? 1 : 0;

            $results[] = [
                'row_id' => $index + 1,
                'reach' => $rowResult,
                'sum_product' => $sumProduct,
            ];
        }

        $totalCount = count($results);
        $countZero = count(array_filter($results, fn($item) => $item['reach'] == 0));
        $countOne = count(array_filter($results, fn($item) => $item['reach'] == 1));
        $zeroPercentage = $totalCount ? ($countZero / $totalCount) * 100 : 0;
        $onePercentage = $totalCount ? ($countOne / $totalCount) * 100 : 0;

        $commercialQualityData = [
            'labels' => ['Reached', 'Not Reached'],
            'datasets' => [
                'Default' => [
                    'data' => [$onePercentage, $zeroPercentage]
                ]
            ]
        ];

        if ($request->ajax()) {
            return response()->json([
                'commercialQualityData' => $commercialQualityData
            ]);
        }

        $dataMessage = count($dataRows) === 0 ? "No data available to display." : null;
        return view('consumers-reached', compact('breadcrumb', 'dataMessage', 'commercialQualityData', 'selectedValues'));
    }
}
