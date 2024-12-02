<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexedChartController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Indexed Review of Stronger Drivers';

        return view('index-chart', compact('breadcrumb'));
    }
}