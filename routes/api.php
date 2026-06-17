<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DesaController;
use App\Http\Controllers\Api\ProduksiController;
use App\Http\Controllers\Api\BiayaOperasionalController;
use App\Http\Controllers\Api\JenisKegiatanController;
use App\Http\Controllers\Api\LahanController;
use App\Http\Controllers\Api\KegiatanController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/desa', [DesaController::class, 'index']);
//produksi
Route::get('/produksi', [ProduksiController::class, 'index']);
Route::post('/produksi', [ProduksiController::class, 'store']);
Route::get('/produksi/{id}', [ProduksiController::class, 'show']);
//biaya operasional
Route::get('/biaya-operasional', [BiayaOperasionalController::class, 'index']);
Route::post('/biaya-operasional', [BiayaOperasionalController::class, 'store']);
Route::get('/biaya-operasional/{id}', [BiayaOperasionalController::class, 'show']);

# jenis kegiatan
Route::get('/jenis-kegiatan', [JenisKegiatanController::class, 'index']);

# kegiatan 
Route::post('/kegiatan', [KegiatanController::class, 'store']);

# lahan
Route::get('/lahan', [LahanController::class, 'index']);
Route::get('/lahan/petani/{petaniId}', [LahanController::class, 'getByPetani']);
Route::post('/lahan', [LahanController::class, 'store']);