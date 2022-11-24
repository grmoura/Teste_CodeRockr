<?php

use App\Http\Controllers\CriarInvestimentoController;
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



Route::post('criarInvestimento', [CriarInvestimentoController::class, 'criarInvestimento'])->name('criarInvestimento');
Route::get('criarInvestimento/code', [CriarInvestimentoController::class, 'code'])->name('code');


Route::post('criarInvestimento', [CriarInvestimentoController::class, 'criarInvestimento'])->name('criarInvestimento');
