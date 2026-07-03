<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DesaController;
use App\Http\Controllers\Api\ProduksiController;
use App\Http\Controllers\Api\BiayaOperasionalController;
use App\Http\Controllers\Api\JenisKegiatanController;
use App\Http\Controllers\Api\LahanController;
use App\Http\Controllers\Api\KegiatanController;
use App\Http\Controllers\Api\RiwayatKeuanganController;
use App\Http\Controllers\Api\PengingatController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/petani/{petani_id}', [AuthController::class, 'getPetani']);

Route::get('/desa', [DesaController::class, 'index']);

// produksi
Route::get('/produksi', [ProduksiController::class, 'index']);
Route::get('/produksi/{id}', [ProduksiController::class, 'show']);
Route::post('/produksi', [ProduksiController::class, 'store']);

// biaya operasional
Route::get('/biaya-operasional', [BiayaOperasionalController::class, 'index']);
Route::get('/biaya-operasional/{id}', [BiayaOperasionalController::class, 'show']);
Route::post('/biaya-operasional', [BiayaOperasionalController::class, 'store']);

// riwayat keauangan
Route::get('/riwayat-keuangan', [RiwayatKeuanganController::class, 'index']);
Route::get('/riwayat-keuangan/detail', [RiwayatKeuanganController::class, 'detail']);

// jenis kegiatan
Route::get('/jenis-kegiatan', [JenisKegiatanController::class, 'index']);

// kegiatan 
Route::post('/kegiatan', [KegiatanController::class, 'store']);

// lahan
Route::get('/lahan', [LahanController::class, 'index']);
Route::get('/lahan/petani/{petaniId}', [LahanController::class, 'getByPetani']);
Route::post('/lahan', [LahanController::class, 'store']);

// pengingat (send to petani without storing)
Route::post('/pengingat', [PengingatController::class, 'send']);