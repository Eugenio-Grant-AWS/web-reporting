<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttentiveExposureController extends Controller
{
    public function index(Request $request)
    {
        $breadcrumb = 'Media Exposure';

        // Define the mapping of old MediaType labels to new ones
        $mediaTypeMapping = [
            "Ver TV nacional" => "TV nacional",
            "Ver TV por cable" => "TV por cable",
            "Ver TV por internet" => "TV por internet",
            "O?r radio" => "Radio",
            "O?r radio online" => "Radio online",
            "Leer revista impresa" => "Revista impresa",
            "Leer revista digital" => "Revista digital",
            "Leer peri?dico impreso" => "Periódico impreso",
            "Leer peri?dico digital" => "Periódico digital",
            "Peri?dico por email" => "Periódico por email",
            "Ver vallas publicitarias" => "Vallas publicitarias",
            "Visitar centros comerciales" => "Centros comerciales",
            "Usar metrobús" => "Metrobús",
            "Ir al cine" => "Cine",
            "Abrir emails comerciales" => "Emails comerciales",
            "Buscar aseguradoras en Google" => "Buscar aseguradoras",
            "Usar Facebook" => "Facebook",
            "Usar X (Twitter)" => "Twitter",
            "Usar Instagram" => "Instagram",
            "Usar YouTube" => "YouTube",
            "Usar LinkedIn" => "LinkedIn",
            "Usar WhatsApp" => "WhatsApp",
            "Escuchar Spotify" => "Spotify",
            "Ver Netflix" => "Netflix",
            "Usar listas de correo" => "Listas de correo",
            "Jugar en el celular" => "Jugar en el celular",
            "Usar WeTransfer" => "WeTransfer",
            "Usar Uber" => "Uber",
            "Usar PedidosYa" => "PedidosYa",
            "Usar Meet" => "Meet",
            "Usar Zoom" => "Zoom",
            "Usar Airbnb" => "Airbnb",
            "Usar TikTok" => "TikTok",
            "Usar Encuentra24" => "Encuentra24",
        ];

        /*
         * STEP 1. Prepare the query for filtering.
         * We use a query builder (without ->get()) so that all filters are applied at the DB level.
         */
        $query = DB::table('attentive_exposures');

        // Aplicar based on the request parameters
        if ($request->has('uniqueRespoSer') && !empty($request->uniqueRespoSer)) {
            $query->whereIn('RespoSer', $request->uniqueRespoSer);
        }
        if ($request->has('uniqueGender') && !empty($request->uniqueGender)) {
            $query->whereIn('QuotGene', $request->uniqueGender);
        }
        if ($request->has('uniqueAge') && !empty($request->uniqueAge)) {
            $query->whereIn('QuotEdad', $request->uniqueAge);
        }
        if ($request->has('uniqueQuoSegur') && !empty($request->uniqueQuoSegur)) {
            $query->whereIn('QuoSegur', $request->uniqueQuoSegur);
        }
        if ($request->has('uniqueMediaType') && !empty($request->uniqueMediaType)) {
            // Trim the incoming MediaType filter values
            $filterValues = array_map('trim', $request->uniqueMediaType);
            $placeholders = implode(',', array_fill(0, count($filterValues), '?'));
            // Remove carriage returns and newlines from the DB value before comparing.
            $query->whereRaw(
                "REPLACE(REPLACE(MediaType, '\r', ''), '\n', '') IN ($placeholders)",
                $filterValues
            );
        }
        if ($request->has('uniqueValue') && !empty($request->uniqueValue)) {
            $query->whereIn('Value', $request->uniqueValue);
        }
        if ($request->has('uniqueReach') && !empty($request->uniqueReach)) {
            $query->whereIn('Reach', $request->uniqueReach);
        }
        if ($request->has('uniqueattentive_exposure') && !empty($request->uniqueattentive_exposure)) {
            $query->whereIn('attentive_exposure', $request->uniqueattentive_exposure);
        }

        // Retrieve the filtered data
        $mediaData = $query->get();

        /*
         * STEP 2. Retrieve unique filter options.
         * (These are obtained from the full table so that all options appear.)
         */
        $fullData = DB::table('attentive_exposures')->get();
        $uniqueRespoSer = $fullData->pluck('RespoSer')->unique();
        $uniqueGender = $fullData->pluck('QuotGene')->unique();
        $uniqueAge = $fullData->pluck('QuotEdad')->unique();
        $uniqueQuoSegur = $fullData->pluck('QuoSegur')->unique();
        $uniqueMediaType = $fullData->pluck('MediaType')->unique();
        $uniqueValue = $fullData->pluck('Value')->unique();
        $uniqueReach = $fullData->pluck('Reach')->unique();
        $uniqueattentive_exposure = $fullData->pluck('attentive_exposure')->unique();

        /*
         * STEP 3. Map MediaType values using the mapping array and ensure proper UTF-8 encoding.
         * (Here we transform the collection.)
         */
        $mediaData->transform(function ($item) use ($mediaTypeMapping) {
            if (isset($mediaTypeMapping[$item->MediaType])) {
                $item->MediaType = $mediaTypeMapping[$item->MediaType];
            }
            return $item;
        });

        /*
         * STEP 4. Group the data by MediaType and calculate the average attentive exposure for each group.
         */
        $groupedData = $mediaData->groupBy('MediaType');
        $averages = $groupedData->map(function ($group) {
            $total = $group->sum('attentive_exposure');
            $count = $group->count();
            return $count > 0 ? round(($total / $count) * 100, 2) : 0;
        });
        $sortedAverages = $averages->sortDesc();

        // Prepare data for the chart
        $chartData = [
            'categories' => $sortedAverages->keys()->toArray(), // Media types
            'percentages' => $sortedAverages->values()->toArray(), // Average exposure percentages
        ];

        // Check if data is available
        $dataMessage = empty($chartData['categories']) ? "No data available to display." : null;

        // If AJAX, return JSON; otherwise, return the view.
        if ($request->ajax()) {
            return response()->json(['chartData' => $chartData, 'dataMessage' => $dataMessage]);
        }
        return view('attentive-exposure', compact(
            'breadcrumb',
            'chartData',
            'dataMessage',
            'uniqueRespoSer',
            'uniqueGender',
            'uniqueAge',
            'uniqueQuoSegur',
            'uniqueMediaType',
            'uniqueValue',
            'uniqueReach',
            'uniqueattentive_exposure'
        ));
    }
}