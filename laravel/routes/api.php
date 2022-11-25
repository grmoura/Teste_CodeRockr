<?php

use App\Http\Controllers\InvestimentoController;
use Illuminate\Support\Facades\Route;

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



Route::post('criarInvestimento', [InvestimentoController::class, 'criarInvestimento'])->name('criarInvestimento');
Route::post('visualizarInvestimento', [InvestimentoController::class, 'visualizarInvestimento'])->name('visualizarInvestimento');
Route::post('retirarInvestimento', [InvestimentoController::class, 'retirarInvestimento'])->name('retirarInvestimento');
Route::post('listarInvestimento', [InvestimentoController::class, 'listarInvestimento'])->name('listarInvestimento');

Route::get('code/{id}', [InvestimentoController::class, 'code'])->name('code');

