<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OptimizedCampaignSummaryController extends Controller
{
    public function index()
    {
        $breadcrumb = 'Optimized Campaign Summary';
        return view('optimized-campaign-summary', compact('breadcrumb'));
    }
}