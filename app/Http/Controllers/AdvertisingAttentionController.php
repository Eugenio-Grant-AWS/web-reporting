<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class AdvertisingAttentionController extends Controller
{

    public function index(Request $request)
    {
        $breadcrumb = 'Advertising Attention by Touchpoint';
        $query = DB::table('advertising_attentions');


        if ($request->has('uniqueRespoSer') && !empty($request->uniqueRespoSer)) {
            $query = $query->whereIn('RespoSer', $request->uniqueRespoSer);
        }
        if ($request->has('uniqueGender') && !empty($request->uniqueGender)) {
            $query = $query->whereIn('QuotGene', $request->uniqueGender);
        }
        if ($request->has('uniqueAge') && !empty($request->uniqueAge)) {
            $query = $query->whereIn('QuotEdad', $request->uniqueAge);
        }
        if ($request->has('uniqueValue') && !empty($request->uniqueValue)) {
            $query = $query->whereIn('Value', $request->uniqueValue);
        }
        if ($request->has('uniqueQuoSegur') && !empty($request->uniqueQuoSegur)) {
            $query = $query->whereIn('QuoSegur', $request->uniqueQuoSegur);
        }
        if ($request->has('uniqueMediaType') && !empty($request->uniqueMediaType)) {
            $query = $query->whereIn('MediaType', $request->uniqueMediaType);
        }

        $mediaTypes = $query->get();

      
        $uniqueRespoSer = $mediaTypes->pluck('RespoSer')->unique();
        $uniqueGender = $mediaTypes->pluck('QuotGene')->unique();
        $uniqueAge = $mediaTypes->pluck('QuotEdad')->unique();
        $uniqueValue = $mediaTypes->pluck('Value')->unique();
        $uniqueQuoSegur = $mediaTypes->pluck('QuoSegur')->unique();
        $uniqueMediaType = $mediaTypes->pluck('MediaType')->unique();

        $mediaTypeMapping = [
            "Ver TV por cable" => "Ver TV por cable",
            "O?r radio" => "Radio",
            "O?r radio online" => "Radio online",
            "Leer revista impresa" => "Revista impresa",
            "Leer revista digital" => "Revista digital",
            "Leer peri?dico impreso" => "Periódico impreso",
            "Leer peri?dico digital" => "Periódico digital",
            "Peri?dico por email" => "Periódico email",
            "Ver vallas publicitarias" => "Vallas publicitarias",
            "Visitar centros comerciales" => "Visitar centros comerciales",
            "Ir al cine" => "Cine",
            "Abrir emails comerciales" => "Emails comerciales",
            "Usar X (Twitter)" => "X (Twitter)",
            "Usar Instagram" => "Instagram",
            "Usar YouTube" => "YouTube",
            "Usar LinkedIn" => "LinkedIn",
            "Usar WhatsApp" => "WhatsApp",
            "Escuchar Spotify" => "Spotify",
            "Jugar en el celular" => "Jugar en el celular",
            "Usar WeTransfer" => "WeTransfer",
            "Usar PedidosYa" => "PedidosYa",
            "Usar Meet" => "Meet",
            "Usar Airbnb" => "Airbnb",
            "Usar Encuentra24" => "Encuentra24",
            "Ver TV nacional" => "TV nacional",
            "Ver TV por internet" => "TV por internet",
            "Usar metrob?s" => "Usar metrobús",
            "Buscar aseguradoras en Google" => "Buscar aseguradoras en Google",
            "Usar Facebook" => "Facebook",
            "Usar listas de correo" => "Usar listas de correo",
            "Usar Uber" => "Uber",
            "Usar TikTok" => "TikTok",
            "Usar Zoom" => "Zoom",
            "Ver Netflix" => "Netflix",
        ];

        $mediaTypes = $mediaTypes->map(function ($item) use ($mediaTypeMapping) {
            $item->MediaType = preg_replace('/^\d+\.\s/', '', $item->MediaType);
            if (isset($mediaTypeMapping[$item->MediaType])) {
                $item->MediaType = $mediaTypeMapping[$item->MediaType];
            }
            return $item;
        });

        $grouped = $mediaTypes->groupBy(['MediaType', 'Value']);
        $categories = $mediaTypes->pluck('MediaType')->unique()->toArray();
        $values = $mediaTypes->pluck('Value')
            ->unique()
            ->sort()
            ->values()
            ->all();

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

        // Sort the categories and series data
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

        // Prepare chart data to pass to the view
        $chartData = [
            'categories' => $sortedCategories,
            'series' => $sortedSeries,
        ];
        if ($request->ajax()) {
            return response()->json(['chartData' => $chartData]);
        }
        // If no data is found, show a message
        $dataMessage = empty($chartData['series']) ? "No data available to display." : null;
        return view('advertising-attention', compact(
            'breadcrumb',
            'chartData',
            'dataMessage',
            'uniqueRespoSer',
            'uniqueGender',
            'uniqueAge',
            'uniqueQuoSegur',
            'uniqueMediaType',
            'uniqueValue'
        ));
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
    
}