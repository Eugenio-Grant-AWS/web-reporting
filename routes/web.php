<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HeatMapController;
use App\Http\Controllers\BarChartController;
use App\Http\Controllers\PieChartController;
use App\Http\Controllers\LineChartController;
use App\Http\Controllers\ScatterPlotController;
use App\Http\Controllers\VennDiagramController;
use App\Http\Controllers\IndexedChartController;
use App\Http\Controllers\SummaryChartController;
use App\Http\Controllers\SummaryTableController;
use App\Http\Controllers\TouchpointInfluenceController;
use App\Http\Controllers\CampaignSummaryChartController;
use App\Http\Controllers\OptimizedCampaignSummaryController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/bar-chart', [BarChartController::class, 'index'])->name('bar-chart');
    Route::get('/pie-chart', [PieChartController::class, 'index'])->name('pie-chart');
    Route::get('/line-chart', [LineChartController::class, 'index'])->name('line-chart');
    Route::get('/venn-diagram', [VennDiagramController::class, 'index'])->name('venn-diagram');
    Route::get('/scatter-plot', [ScatterPlotController::class, 'index'])->name('scatter-plot');
    Route::get('/heat-map', [HeatMapController::class, 'index'])->name('heat-map');
    Route::get('/touchpoint-influence', [TouchpointInfluenceController::class, 'index'])->name('touchpoint-influence');
    Route::get('/indexed-chart', [IndexedChartController::class, 'index'])->name('indexed-chart');
    Route::get('/tip-summary', [SummaryTableController::class, 'index'])->name('tip-summary');
    Route::get('/summary-chart', [SummaryChartController::class, 'index'])->name('summary-chart');
    Route::get('/optimized-campaign-summary', [OptimizedCampaignSummaryController::class, 'index'])->name('optimized-campaign-summary');
    Route::get('/attentive-exposure', [CampaignSummaryChartController::class, 'index'])->name('attentive-exposure');

    // Route::view('/touchpoint-influence', 'touchpoint-influence')->name('touchpoint-influence');
    // Route::view('/indexed-review', 'indexed-review')->name('indexed-review');
    // Route::view('/tip-summary', 'tip-summary')->name('tip-summary');
    // Route::view('/attentive-exposure', 'attentive-exposure')->name('attentive-exposure');
    // Route::view('/optimized-campaign-summary', 'optimized-campaign-summary')->name('optimized-campaign-summary');
});


require __DIR__ . '/auth.php';