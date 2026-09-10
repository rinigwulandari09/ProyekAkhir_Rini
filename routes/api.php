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
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\HargaTbsController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/lupa-pin', [AuthController::class, 'lupaPin']);
Route::get('/petani', [PetaniController::class, 'getAll']); // Taruh di atas rute {petani_id} agar tidak bentrok
Route::get('/petani/{petani_id}', [AuthController::class, 'getPetani']);
Route::post('/petani/update/{petani_id}', [PetaniController::class, 'update']);
Route::post('/petani/ubah-pin/{petani_id}', [PetaniController::class, 'ubahPin']);

// harga tbs (Public endpoint for mobile apps)
Route::get('/harga-tbs/latest', [HargaTbsController::class, 'getLatestHargaApi']);

// users
Route::get('/users/admins', [UserController::class, 'getAdmins']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users/update/{id}', [UserController::class, 'update']);

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
Route::get('/jenis-kegiatan', [JenisKegiatanController::class, 'index']);
Route::get('/jenis-kegiatan/{id}', [JenisKegiatanController::class, 'show']);

// kegiatan 
Route::get('/kegiatan', [KegiatanController::class, 'index']);
Route::post('/kegiatan', [KegiatanController::class, 'store']);
Route::get('/kegiatan/{id}', [KegiatanController::class, 'show']);

Route::get('/kegiatan', [KegiatanController::class, 'riwayat']);
Route::get('/kegiatan/{id}', [KegiatanController::class, 'detail']);

// lahan
Route::get('/lahan', [LahanController::class, 'index']);
Route::get('/lahan/petani/{petaniId}', [LahanController::class, 'getByPetani']);
Route::post('/lahan', [LahanController::class, 'store']);
Route::post('/lahan/update/{lahan_id}', [LahanController::class, 'update']);

// pengingat (send to petani without storing)
Route::post('/pengingat', [PengingatController::class, 'send']);

// kunjungan lapangan
Route::post('/kunjungan-lapangan', [KunjunganLapanganController::class, 'store']);

// audit internal
Route::post('/audit-internal', [AuditInternalController::class, 'store']);
Route::get('/audit-internal/all', [AuditInternalController::class, 'getAllByDesa']);
Route::get('/audit-internal/petani/{petani_id}', [AuditInternalController::class, 'getNotifications']);
Route::put('/notifications/read', [AuditInternalController::class, 'markAsRead']);

// dashboard
Route::get('/dashboard', [DashboardApiController::class, 'index']);
Route::get('/dashboard/petani/{petani_id}', [DashboardApiController::class, 'petaniSummary']);

// tugas & FCM push notification API
use App\Http\Controllers\Api\TugasApiController;
Route::get('/tugas', [TugasApiController::class, 'index']);
Route::post('/tugas/{id}/complete', [TugasApiController::class, 'complete']);
Route::post('/user/fcm-token', [TugasApiController::class, 'updateFcmToken']);