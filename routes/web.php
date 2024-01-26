<?php

use App\Models\Bts;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return view('login');
    })->name('index');
    Route::post('/login-attempt', [WebController::class, 'login_attempt']);
});

Route::middleware(['auth'])->group(function () {
    // FRONT END
    Route::get('/bts', function () {
        return view('bts');
    });
    Route::get('/bts/form', function () {
        return view('bts-form');
    });
    Route::get('/report', function () {
        return view('report');
    });
    Route::get('/report/form', function () {
        $btsList = Bts::all(); // Ambil semua data BTS dari model
        return view('report-form', ['bts' => $btsList]); // Kirim data BTS ke tampilan 'report'
    });

    // BACK END
    Route::get('/dashboard/get', [WebController::class, 'get_dashboard']);

    Route::get('/logout', [WebController::class, 'logout']);

    Route::get('/bts/get', [WebController::class, 'get_bts']);
    Route::get('/bts/getById/{id}', [WebController::class, 'bts_getById']);
    Route::post('/bts/store', [WebController::class, 'bts_store']);
    Route::post('/bts/update', [WebController::class, 'bts_update']);
    Route::delete('/bts/delete/{id}', [WebController::class, 'bts_delete']);

    Route::get('/report/get', [WebController::class, 'report_get']);
    Route::post('/report/store', [WebController::class, 'report_store']);
    Route::delete('/report/delete/{id}', [WebController::class, 'report_delete']);
});
