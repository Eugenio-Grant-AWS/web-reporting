<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;



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

        // Fetch data from the attentive_exposures table
        $mediaData = DB::table('attentive_exposures')->get();

               // Get unique categories for filters
            $uniqueRespoSer = $mediaData->pluck('RespoSer')->unique();
            $uniqueGender = $mediaData->pluck('QuotGene')->unique();
            $uniqueAge = $mediaData->pluck('QuotEdad')->unique();
            $uniqueQuoSegur = $mediaData->pluck('QuoSegur')->unique();
            $uniqueMediaType = $mediaData->pluck('MediaType')->unique();
            $uniqueValue = $mediaData->pluck('Value')->unique();
            $uniqueReach = $mediaData->pluck('Reach')->unique();
            $uniqueattentive_exposure = $mediaData->pluck('attentive_exposure')->unique();

            // Apply filters based on selected options from the request
            if ($request->has('uniqueRespoSer') && !empty($request->uniqueRespoSer)) {
                $mediaData = $mediaData->whereIn('RespoSer', $request->uniqueRespoSer);
            }
            if ($request->has('uniqueGender') && !empty($request->uniqueGender)) {
                $mediaData = $mediaData->whereIn('QuotGene', $request->uniqueGender);
            }
            if ($request->has('uniqueAge') && !empty($request->uniqueAge)) {
                $mediaData = $mediaData->whereIn('QuotEdad', $request->uniqueAge);
            }
            if ($request->has('uniqueQuoSegur') && !empty($request->uniqueQuoSegur)) {
                $mediaData = $mediaData->whereIn('QuoSegur', $request->uniqueQuoSegur);
            }
            if ($request->has('uniquMediaType') && !empty($request->uniqueMediaType)) {
                $mediaData = $mediaData->whereIn('MediaType', $request->uniqueMediaType);
            }
            if ($request->has('uniqueValue') && !empty($request->uniqueValue)) {
                $mediaTypes = $mediaData->whereIn('Value', $request->uniqueValue);
            }
            if ($request->has('uniqueReach') && !empty($request->uniqueReach)) {
                $mediaData = $mediaData->whereIn('Reach', $request->uniqueReach);
            }
            if ($request->has('uniqueattentive_exposure') && !empty($request->uniqueattentive_exposure)) {
                $mediaData = $mediaData->whereIn('attentive_exposure', $request->uniqueattentive_exposure);
            }
        // Ensure proper UTF-8 encoding for special characters
        $mediaData->transform(function ($item) use ($mediaTypeMapping) {
            // Map MediaType to the new label if it exists in the mapping
            if (isset($mediaTypeMapping[$item->MediaType])) {
                $item->MediaType = $mediaTypeMapping[$item->MediaType];
            }
            // Ensure correct UTF-8 encoding
            // $item->MediaType = mb_convert_encoding($item->MediaType, 'UTF-8', 'auto');
            return $item;
        });

        // Group the data by MediaType
        $groupedData = $mediaData->groupBy('MediaType');
        // dd($groupedData);
        // Calculate the average exposure for each group
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
        if ($request->ajax()) {
            return response()->json(['chartData' => $chartData, 'dataMessage' => $dataMessage]);
        }
        return view('attentive-exposure', compact('breadcrumb', 'chartData', 'dataMessage' , 'uniqueRespoSer', 'uniqueGender', 'uniqueAge', 'uniqueQuoSegur', 'uniqueMediaType', 'uniqueValue', 'uniqueReach','uniqueattentive_exposure'));
    }
}