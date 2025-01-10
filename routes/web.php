<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NetReachController;
use App\Http\Controllers\SummaryChartController;
use App\Http\Controllers\SummaryTableController;
use App\Http\Controllers\IndexedReviewController;
use App\Http\Controllers\ReachExposureController;
use App\Http\Controllers\ConsumersReachedController;
use App\Http\Controllers\AttentiveExposureController;
use App\Http\Controllers\TouchpointInfluenceController;
use App\Http\Controllers\AdvertisingAttentionController;
use App\Http\Controllers\UnduplicatedNetReachController;
use App\Http\Controllers\Reach_X_AttentionPlotController;
use App\Http\Controllers\OptimizedCampaignSummaryController;


if (Auth::check()) {
    Route::get('/', function () {
        return redirect()->route('reach-exposure-probability-with-mean');
    });
} else {
    Route::get('/', function () {
        return redirect()->route('login');
    });
}


// Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');


Route::middleware(['auth', 'verified', 'track.previous.url'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/testconsumer', [TestController::class, 'index'])->name('testconsumer.index');

    Route::get('/reach-exposure-probability-with-mean', [ReachExposureController::class, 'index'])->name('reach-exposure-probability-with-mean');
    Route::post('/media-consumption/import', [ReachExposureController::class, 'import'])->name('media-consumption.import');


    Route::get('/net-percentage-of-consumers-reached', [ConsumersReachedController::class, 'index'])->name('net-percentage-of-consumers-reached');
    Route::get('/unduplicated-net-reach', [UnduplicatedNetReachController::class, 'index'])->name('unduplicated-net-reach');
    Route::get('/net-reach', [NetReachController::class, 'index'])->name('net-reach');
    Route::get('/reach-attention-plot', [Reach_X_AttentionPlotController::class, 'index'])->name('reach-attention-plot');
    Route::get('/attentive-exposure', [AttentiveExposureController::class, 'index'])->name('attentive-exposure');
    Route::get('/touchpoint-influence', [TouchpointInfluenceController::class, 'index'])->name('touchpoint-influence');
    Route::get('/indexed-review-of-stronger-drivers', [IndexedReviewController::class, 'index'])->name('indexed-review-of-stronger-drivers');
    Route::get('/tip-summary', [SummaryTableController::class, 'index'])->name('tip-summary');
    Route::get('/tip-summary-creative-quality', [SummaryChartController::class, 'index'])->name('tip-summary-creative-quality');
    Route::get('/optimized-campaign-summary', [OptimizedCampaignSummaryController::class, 'index'])->name('optimized-campaign-summary');
    Route::get('/advertising-attention-by-touchpoint', [AdvertisingAttentionController::class, 'index'])->name('advertising-attention-by-touchpoint');
});


require __DIR__ . '/auth.php';