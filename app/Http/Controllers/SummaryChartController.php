<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SummaryChartController extends Controller
{
    public function index()
    {

        $breadcrumb = 'TIP Summary x Creative Quality';

        return view('summary-chart', compact('breadcrumb'));
    }
}