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

    public function index(Request $request)
    {
        $breadcrumb = 'Reach Exposure - Probability with mean';

        // Define the mapping of old to new values
        $mediaTypeMapping = [
            "Ver la televisión de señal nacional" => "TV nacional",
            "Ver la televisión por cable" => "TV cable",
            "Ver la televisión por internet" => "TV por internet",
            "Escuchar la radio" => "Radio",
            "Escuchar la radio por internet" => "Radio online",
            "Leer una revista impresa" => "Revista impresa",
            "Leer una revista digital" => "Revista digital",
            "Leer un periódico impreso" => "Periódico impreso",
            "Leer un periódico digital" => "Periódico digital",
            "Leer un Periódico por correo electrónico" => "Periódico email",
            "Pasar cerca de vallas publicitarias gigantes, fijas o digitales en carreteras/autopistas" => "Vallas publicitarias",
            "Pasar por dentro de los centros comerciales" => "Visitar centros comerciales",
            "Transitar por ruta de metrobuses" => "Usar metrobús",
            "Ver una película en el cine" => "Cine",
            "Abrir correos electrónicos que vienen de algunas compañías" => "Emails comerciales",
            "Entrar a los sitios web/páginas web de LAS ASEGURADORAS (hacer search desde Google)" => "Buscar aseguradoras en Google",
            "Entrar a Facebook" => "Facebook",
            "Entrar a Twitter" => "X (Twitter)",
            "Entrar a Whatsapp" => "WhatsApp",
            "Entrar a Youtube" => "YouTube",
            "Entrar a LinkedIn" => "LinkedIn",
            "Escuchar Spotify" => "Spotify",
            "24.Ver Netflix" => "Netflix",
            "Utilizar Mailing list" => "Usar listas de correo",
            "Videojuegos (celular)" => "Jugar en el celular",
            "Utilizar We transfer" => "WeTransfer",
            "Utilizar Waze" => "Waze",
            "Utilizar Uber" => "Uber",
            "Utilizar Pedidos Ya" => "PedidosYa",
            "Utilizar Meet" => "Meet",
            "Utilizar Zoom" => "Zoom",
            "Utilizar Airbnb" => "Airbnb",
            "Entrar a Google" => "Google",
            "Entrar Encuentra 24" => "Encuentra24",
            "Entrar a Instagram" => "Instagram"
        ];

        $defaultSelection = []; // Make sure this contains a valid selection of column names

        session()->forget('selected_top_row');

        // Handle filter logic: Get selected media types
        $selectedValues = $request->input('top_row', session('selected_top_row', []));
        session(['selected_top_row' => $selectedValues]);

        // Prepare the selected keys for the query, to ensure filtering based on selected media channels
        $filteredKeys = array_keys($selectedValues);

        // If no selection is made, fall back to selecting all columns
        $columnsToSelect = empty($filteredKeys) ? ['*'] : $filteredKeys;

        $dataRows = DB::table('reach_exposures')
            ->select($columnsToSelect) // Select only relevant columns
            ->when(count($filteredKeys), function ($query) use ($filteredKeys) {
                return $query->whereIn('MediaType', $filteredKeys);
            })
            ->get();


        // Fetch the data and apply the mapping to the MediaType
        $mediaTypes = $dataRows->map(function ($item) use ($mediaTypeMapping) {
            // Strip any numbering
            $item->MediaType = preg_replace('/^\d+\.\s/', '', $item->MediaType);

            // Replace with the new value if it exists in the mapping
            if (isset($mediaTypeMapping[$item->MediaType])) {
                $item->MediaType = $mediaTypeMapping[$item->MediaType];
            }

            return $item;
        });

        // Group by MediaType and Adjusted_value
        $grouped = $mediaTypes->groupBy(['MediaType', 'Adjusted_value']);

        // Extract unique categories
        $categories = $mediaTypes->pluck('MediaType')->unique()->toArray();

        // Extract unique Adjusted_value values
        $values = $mediaTypes->pluck('Adjusted_value')
            ->unique()
            ->filter(fn($value) => $value !== "0")
            ->sort()
            ->values()
            ->all();

        // Ensure 0 is included in the list of values
        $values = !empty($values) ? array_merge($values, ["0"]) : [];

        // Prepare the series data for the chart
        $series = [];
        $colors = $this->generateColors(count($values));

        foreach ($values as $index => $value) {
            $data = collect($categories)->map(function ($category) use ($grouped, $value) {
                // Get total count for the category
                $totalForCategory = $grouped->get($category, collect())->flatten(1)->count();
                // Get the count for the category and value
                $count = $grouped->get($category, collect())->get($value, collect())->count();
                // Calculate the percentage
                return $totalForCategory > 0 ? round(($count / $totalForCategory) * 100, 1) . '%' : '0%';
            })->toArray();

            // Add to series array
            $series[] = [
                'name' => $value,
                'data' => $data,
                'color' => $colors[$index],
            ];
        }

        // Sort the categories based on the first data value in each series
        if (!empty($series[0]['data'])) {
            // Convert percentage strings to float for sorting
            $dataToSort = array_map(fn($value) => strpos($value, '%') !== false ? floatval(str_replace('%', '', $value)) : 0, $series[0]['data']);
            $pairs = array_map(null, $categories, $dataToSort, array_keys($categories));

            // Sort by the percentage values
            usort($pairs, fn($a, $b) => $b[1] <=> $a[1]);

            // Extract sorted categories and their indices
            $sortedCategories = array_column($pairs, 0);
            $sortedIndices = array_column($pairs, 2);

            // Sort series based on the sorted indices
            $sortedSeries = array_map(function ($serie) use ($sortedIndices) {
                $serie['data'] = array_map(fn($index) => $serie['data'][$index], $sortedIndices);
                return $serie;
            }, $series);
        } else {
            // If no data, just use the original categories and series
            $sortedCategories = $categories;
            $sortedSeries = $series;
        }

        // Prepare the chart data
        $chartData = [
            'categories' => $sortedCategories,
            'series' => $sortedSeries,
        ];

        if ($request->ajax()) {
            return response()->json([
                'chartData' => $chartData
            ]);
        }

        // If there is no data, set the message
        $dataMessage = empty($chartData['series']) ? "No data available to display." : null;

        // Pass data to the view
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