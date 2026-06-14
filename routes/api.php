<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DesaController;
use App\Http\Controllers\Api\ProduksiController;
use App\Http\Controllers\Api\BiayaOperasionalController;

Route::post('/register', [AuthController::class, 'register']);
Route::get('/desa', [DesaController::class, 'index']);
//produksi
Route::get('/produksi', [ProduksiController::class, 'index']);
Route::post('/produksi', [ProduksiController::class, 'store']);
Route::get('/produksi/{id}', [ProduksiController::class, 'show']);
//biaya operasional
Route::get('/biaya-operasional', [BiayaOperasionalController::class, 'index']);
Route::post('/biaya-operasional', [BiayaOperasionalController::class, 'store']);
Route::get('/biaya-operasional/{id}', [BiayaOperasionalController::class, 'show']);