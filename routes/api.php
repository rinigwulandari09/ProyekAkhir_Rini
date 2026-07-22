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
use App\Http\Controllers\Api\KunjunganLapanganController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PetaniController;
use App\Http\Controllers\Api\AuditInternalController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/petani', [PetaniController::class, 'getAll']); // Taruh di atas rute {petani_id} agar tidak bentrok
Route::get('/petani/{petani_id}', [AuthController::class, 'getPetani']);

// users
Route::get('/users/admins', [UserController::class, 'getAdmins']);

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

// jenis  kegiatan
// Route untuk menampilkan semua jenis kegiatan
Route::get('/jenis-kegiatan', [JenisKegiatanController::class, 'index']);
// Route untuk menampilkan detail satu jenis kegiatan berdasarkan id
Route::get('/jenis-kegiatan/{id}', [JenisKegiatanController::class, 'show']);

// kegiatan 
// Route untuk menampilkan semua kegiatan dan menyimpan kegiatan baru
Route::get('/kegiatan', [KegiatanController::class, 'index']);
Route::post('/kegiatan', [KegiatanController::class, 'store']);
// Route untuk menampilkan detail satu kegiatan berdasarkan id
Route::get('/kegiatan/{id}', [KegiatanController::class, 'show']);

Route::get('/kegiatan', [KegiatanController::class, 'riwayat']);
Route::get('/kegiatan/{id}', [KegiatanController::class, 'detail']);


// lahan
Route::get('/lahan', [LahanController::class, 'index']);
Route::get('/lahan/petani/{petaniId}', [LahanController::class, 'getByPetani']);
Route::post('/lahan', [LahanController::class, 'store']);

// pengingat (send to petani without storing)
Route::post('/pengingat', [PengingatController::class, 'send']);

// kunjungan lapangan
Route::post('/kunjungan-lapangan', [KunjunganLapanganController::class, 'store']);

// audit internal
Route::post('/audit-internal', [AuditInternalController::class, 'store']);
Route::get('/audit-internal/petani/{petani_id}', [AuditInternalController::class, 'getNotifications']);
Route::put('/notifications/read', [AuditInternalController::class, 'markAsRead']);