<?php

use App\Http\Controllers\TempSODTLController;
use App\Http\Controllers\TempSOMSTController;
use App\Models\TempDOMST;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\TextUI\XmlConfiguration\Group;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('temp-so-mst')->controller(TempSOMSTController::class)->group(function () {
    Route::get('/', 'getgetAllTempSOMSTAll');
    Route::get('/{fc_sono}', 'detailTempSOMST');
    Route::post('/', 'createTempSOMST');
    Route::put('/{fc_sono}', 'setDetailInfoTempSOMST');
    Route::put('/{fc_sono}/submit', 'submitTempSOMST');
});

Route::prefix('temp-so-dtl')->controller(TempSODTLController::class)->group(function() {
    Route::get('/{fc_sono}', 'getSODTLbySONO');
    Route::post('/{fc_sono}', 'addSODTL');
    Route::put('/{fc_sono}', 'updateSODTL');
    Route::delete('/{fc_sono}', 'removeSODTL');
});