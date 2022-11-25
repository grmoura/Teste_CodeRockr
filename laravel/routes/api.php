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
Route::get('visualizarInvestimento', [InvestimentoController::class, 'visualizarInvestimento'])->name('visualizarInvestimento');

Route::get('code/{id}', [InvestimentoController::class, 'code'])->name('code');

