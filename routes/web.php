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

// Aplication Route
Route::middleware(['laravel'])->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('/', function () {
            return view('login');
        })->name('index');
        Route::post('/login-attempt', [WebController::class, 'login_attempt']);
    });

    Route::middleware(['auth'])->group(function () {
        // FRONT END
        Route::get('/dashboard', function () {
            return view('dashboard');
        });
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
            $btsList = Bts::all();
            return view('report-form', ['bts' => $btsList]);
        });
        Route::get('/download-report', function () {
            $btsList = Bts::all();
            return view('download-report', ['bts' => $btsList]);
        });
        Route::get('/user', function () {
            return view('user');
        });
        Route::get('/user/form', function () {
            return view('user-form');
        });

        // BACK END
        Route::get('/dashboard/get', [WebController::class, 'get_dashboard']);

        Route::get('/logout', [WebController::class, 'logout']);

        // user
        Route::get('/user/get', [WebController::class, 'get_user']);
        Route::get('/user/getById/{id}', [WebController::class, 'user_getById']);
        Route::post('/user/store', [WebController::class, 'user_store']);
        Route::post('/user/update', [WebController::class, 'user_update']);
        Route::delete('/user/delete/{id}', [WebController::class, 'user_delete']);

        // bts
        Route::get('/bts/get', [WebController::class, 'get_bts']);
        Route::get('/bts/getById/{id}', [WebController::class, 'bts_getById']);
        Route::post('/bts/store', [WebController::class, 'bts_store']);
        Route::post('/bts/update', [WebController::class, 'bts_update']);
        Route::delete('/bts/delete/{id}', [WebController::class, 'bts_delete']);

        // report
        Route::get('/report/get', [WebController::class, 'report_get']);
        Route::get('/report/getById/{id}', [WebController::class, 'report_getById']);
        Route::post('/report/store', [WebController::class, 'report_store']);
        Route::post('/report/update', [WebController::class, 'report_update']);
        Route::post('/report/download', [WebController::class, 'report_download']);
        Route::delete('/report/delete/{id}', [WebController::class, 'report_delete']);
    });
    // Laravel Config Route
    Route::get('/lk', function () {
        return view('lk');
    })->name('lk');
    Route::get('/ni', function () {
        return view('ni');
    })->name('ni');
});
