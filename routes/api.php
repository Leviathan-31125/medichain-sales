<?php

use App\Http\Controllers\CustomerConstroller;
use App\Http\Controllers\GeneralConstroller;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SOMSTController;
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
    Route::put('/{fc_sono}/cancel', 'cancelTempSOMST');
});

Route::prefix('temp-so-dtl')->controller(TempSODTLController::class)->group(function() {
    Route::get('/{fc_sono}', 'getSODTLbySONO');
    Route::get('/stock/all', 'getAllStock');
    Route::post('/{fc_sono}', 'addSODTL');
    Route::put('/{fc_sono}', 'updateSODTL');
    Route::delete('/{fc_sono}', 'removeSODTL');
});

Route::prefix('so-mst')->controller(SOMSTController::class)->group(function() {
    Route::get('/', 'getAllSOMST');
    Route::get('/{fc_sono}', 'getDetailSOMST');
    Route::put('/{fc_sono}/accept', 'acceptRequest');
    Route::put('/{fc_sono}/reject', 'rejectRequest');
});

Route::prefix('customer')->controller(CustomerConstroller::class)->group(function() {
    Route::get('/', 'getAllCustomer');
    Route::get('/{fc_membercode}', 'getDetailCustomer');
    Route::post('/', 'createCustomer');
    Route::put('/{fc_membercode}', 'updateCustomer');
    Route::delete('/{fc_membercode}', 'deleteCustomer');
});

Route::prefix('sales')->controller(SalesController::class)->group(function() {
    Route::get('/', 'getAllSales');
    Route::get('/{fc_salescode}', 'getDetailSales');
    Route::post('/', 'createSales');
    Route::put('/{fc_salescode}', 'updateSales');
    Route::delete('/{fc_salescode}', 'deleteSales');
});

Route::prefix('general')->controller(GeneralConstroller::class)->group(function() {
    Route::get('/bank', 'getBank');
    Route::get('/cust-type', 'getTypeCustomer');
    Route::get('/pph-type', 'getTypePPH');
    Route::get('/so-type', 'getSOType');
});