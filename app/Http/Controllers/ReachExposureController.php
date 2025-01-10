<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessImportJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Facades\Process;

class ReachExposureController extends Controller
{

    public function index()
    {
        $breadcrumb = 'Reach Exposure - Probability with mean';

        $mediaTypes = DB::table('reach_exposures')
            ->get()
            ->map(function ($item) {
                $item->MediaType = preg_replace('/^\d+\.\s/', '', $item->MediaType);
                return $item;
            });



        $grouped = $mediaTypes->groupBy(['MediaType', 'Adjusted_value']);

        $categories = $mediaTypes->pluck('MediaType')->unique()->toArray();

        $values = $mediaTypes->pluck('Adjusted_value')
            ->unique()
            ->filter(fn($value) => $value !== "0")
            ->sort()
            ->values()
            ->all();

        $values = !empty($values) ? array_merge($values, ["0"]) : [];

        $series = [];

        $colors = $this->generateColors(count($values));

        foreach ($values as $index => $value) {
            $data = collect($categories)->map(function ($category) use ($grouped, $value) {
                $totalForCategory = $grouped->get($category, collect())->flatten(1)->count();
                $count = $grouped->get($category, collect())->get($value, collect())->count();
                return $totalForCategory > 0 ? round(($count / $totalForCategory) * 100, 1) . '%' : '0%';
            })->toArray();

            $series[] = [
                'name' => $value,
                'data' => $data,
                'color' => $colors[$index],
            ];
        }
        if (!empty($series[0]['data'])) {
            $dataToSort = array_map(fn($value) => strpos($value, '%') !== false ? floatval(str_replace('%', '', $value)) : 0, $series[0]['data']);
            $pairs = array_map(null, $categories, $dataToSort, array_keys($categories));

            usort($pairs, fn($a, $b) => $b[1] <=> $a[1]);

            $sortedCategories = array_column($pairs, 0);

            $sortedIndices = array_column($pairs, 2);

            $sortedSeries = array_map(function ($serie) use ($sortedIndices) {

                $serie['data'] = array_map(fn($index) => $serie['data'][$index], $sortedIndices);
                return $serie;
            }, $series);
        } else {
            $sortedCategories = $categories;
            $sortedSeries = $series;
        }


        $chartData = [
            'categories' => $sortedCategories,
            'series' => $sortedSeries,
        ];
        // dd($chartData);


        $dataMessage = empty($chartData['series']) ? "No data available to display." : null;

        return view('reach-exposure', compact('breadcrumb', 'chartData', 'dataMessage'));
    }

    /**
     * Generate an array of unique colors.
     *
     * @param int $count Number of colors to generate.
     * @return array
     */
    private function generateColors(int $count): array
    {
        $colors = [
            '#4e81bd',
            '#bf504d',
            '#2c4d75',
            '#8064a2',
            '#1b6ae7',
            '#f79645',
            '#FFFFFF',

        ];

        // Generate additional colors if needed
        if ($count > 1) {
            for ($i = 0; $i < $count - 1; $i++) {  // Don't generate the last color here
                $hue = (($i + 3) * (360 / ($count))) % 360;
                $colors[] = "hsl($hue, 70%, 50%)";
            }
        }
        return $colors;
    }




    public function import(Request $request)
    {

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);


        $file = $request->file('csv_file');


        if (!$file || !$file->isValid()) {
            Log::error('File upload failed or file is invalid.');
            return redirect()->back()->with('error', 'File upload failed.');
        }


        $path = $file->store('temp', 'public');


        $absolutePath = storage_path('app/public/' . $path);


        $absolutePath = realpath($absolutePath);

        Log::info('File uploaded successfully:', ['file' => $absolutePath]);

        // Dispatch job to process the file
        ProcessImportJob::dispatch($absolutePath);

        // Return success message to the user
        return redirect()->back()->with('success', 'File uploaded successfully.');
    }
}