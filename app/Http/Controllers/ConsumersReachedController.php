<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConsumersReached;
use Illuminate\Support\Facades\DB;

class ConsumersReachedController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = 'Net % of Consumers Reached';
    
        // Default selection for filters
        $defaultSelection = [
            'ver_tv_senal_nacional' => 1,
        ];
    
        session()->forget('selected_top_row');
        $selectedValues = $request->input('top_row', session('selected_top_row', $defaultSelection));
        session(['selected_top_row' => $selectedValues]);
    
        // Fetch distinct values for quotgene, quotedad, and quosegur
        $quotgeneValues = DB::table('consumers_reacheds')->distinct()->pluck('quotgene');
        $quotedadValues = DB::table('consumers_reacheds')->distinct()->pluck('quotedad');
        $quosegurValues = DB::table('consumers_reacheds')->distinct()->pluck('quosegur');
    
        // Start the query with selected values for media channels
        $query = DB::table('consumers_reacheds')->select(array_keys($selectedValues));
    
        // Aplicar only if they are present in the request
        if ($request->has('quotgene') && $request->input('quotgene') != null) {
            $query->whereIn('quotgene', $request->input('quotgene'));
        }
    
        if ($request->has('quotedad') && $request->input('quotedad') != null) {
            $query->whereIn('quotedad', $request->input('quotedad'));
        }
    
        if ($request->has('quosegur') && $request->input('quosegur') != null) {
            $query->whereIn('quosegur', $request->input('quosegur'));
        }
    
        // Fetch the data after applying the filters
        $dataRows = $query->get();
    
        // Process the data for chart
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
    
        // Calculate percentages for the chart
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
    
        // Return the response for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'commercialQualityData' => $commercialQualityData
            ]);
        }
    
        // If no data, show a message
        $dataMessage = count($dataRows) === 0 ? "No data available to display." : null;
        return view('consumers-reached', compact('breadcrumb', 'dataMessage', 'commercialQualityData', 'selectedValues', 'quotgeneValues', 'quotedadValues', 'quosegurValues'));
    }
}
