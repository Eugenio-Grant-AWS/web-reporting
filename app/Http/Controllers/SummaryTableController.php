<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SummaryTableController extends Controller
{
    public function index()
    {

        $breadcrumb = 'TIP Summary';

        return view('tip-summary', compact('breadcrumb'));
    }
}