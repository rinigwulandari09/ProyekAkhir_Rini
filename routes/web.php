<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetaniController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\AuditInternalController;
use App\Http\Controllers\HargaTbsController;
use App\Http\Controllers\ProduksiController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::get('/login', [LoginController::class, 'index'])
    ->name('login');

Route::post('/login', [LoginController::class,'authenticate'])
    ->name('login.process');

Route::post('/logout', [LoginController::class,'logout'])
    ->name('logout');

// Tambahkan kedua route ini
Route::get('/auth/google', [LoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

// SEMUA ROUTE DI BAWAH INI HARUS LOGIN
Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // untuk update status petani di dashboard super admin
    Route::put('/dashboard/petani/{id}/status', [DashboardController::class, 'updateStatus'])->name('petani.updateStatus');

    
    //MANAJEMEN USER (CRUD)
    // Menampilkan Tabel User
    Route::get('/data-user', [UserController::class, 'index'])->name('user.index');
    
    // Form Tambah User
    Route::get('/data-user/create', [UserController::class, 'create'])->name('user.create');
    
    // Proses Simpan User Baru
    Route::post('/data-user', [UserController::class, 'store'])->name('user.store');
    
    // Form Edit User
    Route::get('/data-user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    
    // Proses Update User (Gunakan PUT)
    Route::put('/data-user/{id}', [UserController::class, 'update'])->name('user.update');
    
    // Proses Hapus User (Gunakan DELETE)
    Route::delete('/data-user/{id}', [UserController::class, 'destroy'])->name('user.destroy');


    // --- KELOLA DATA PETANI (Dipakai Bersama oleh Admin & Super Admin) ---
    // Menampilkan halaman daftar petani (Otomatis mendeteksi layout di Controller)
    Route::get('/petani', [PetaniController::class, 'index'])->name('petani.index');

    // Form Tambah Petani
    Route::get('/petani/create', [PetaniController::class, 'create'])
        ->name('petani.create');

    // Proses Simpan Petani Baru
    Route::post('/petani', [PetaniController::class, 'store'])
        ->name('petani.store');

    // Menampilkan halaman form edit petani
    Route::get('/petani/{id}/edit', [PetaniController::class, 'edit'])->name('petani.edit');

    // Proses memperbarui status/data petani
    Route::post('/petani/{id}/update-status', [PetaniController::class, 'updateStatus'])->name('petani.updateStatus');

    // Proses menghapus data petani
    Route::delete('/petani/{id}', [PetaniController::class, 'destroy'])->name('petani.destroy');

    
    //DATA LAHAN
    Route::get('/lahan', [LahanController::class, 'index'])->name('lahan.index');
    Route::get('/lahan/create', [LahanController::class, 'create'])->name('lahan.create');
    Route::post('/lahan', [LahanController::class, 'store'])->name('lahan.store');
    Route::get('/lahan/{id}', [LahanController::class, 'show'])->name('lahan.show');
    Route::get('/lahan/{id}/edit', [LahanController::class, 'edit'])->name('lahan.edit');
    Route::put('/lahan/{id}', [LahanController::class, 'update'])->name('lahan.update');
    Route::delete('/lahan/{id}', [LahanController::class, 'destroy'])->name('lahan.destroy');
    Route::post('/lahan/import-geojson', [App\Http\Controllers\LahanController::class, 'importGeoJson'])->name('lahan.import_geojson');

    //DATA PRODUKSI (RSPO)
    Route::get('/produksi', [ProduksiController::class, 'index'])->name('produksi.index');
    Route::get('/produksi/export', [ProduksiController::class, 'export'])->name('produksi.export');

    //KEUANGAN
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::get('/data-keuangan/{id}', [KeuanganController::class, 'show'])->name('keuangan.show');
    
    // Edit Keuangan (Produksi & Biaya Operasional)
    Route::get('/produksi/{id}/edit', [KeuanganController::class, 'editProduksi'])->name('produksi.edit');
    Route::put('/produksi/{id}', [KeuanganController::class, 'updateProduksi'])->name('produksi.update');
    Route::get('/biaya-operasional/{id}/edit', [KeuanganController::class, 'editBiayaOperasional'])->name('biaya_operasional.edit');
    Route::put('/biaya-operasional/{id}', [KeuanganController::class, 'updateBiayaOperasional'])->name('biaya_operasional.update');

    //HARGA TBS
    Route::get('/harga-tbs', [HargaTbsController::class, 'index'])->name('harga_tbs.index');
    Route::post('/harga-tbs', [HargaTbsController::class, 'store'])->name('harga_tbs.store');

    //AUDIT INTERNAL
    Route::get('/audit', [AuditInternalController::class, 'index'])->name('audit.index');
    Route::delete('/audit/kunjungan/{id}', [AuditInternalController::class, 'destroyKunjungan'])->name('audit.kunjungan.destroy');
    Route::put('/audit/kunjungan/{id}/status', [AuditInternalController::class, 'updateStatusKunjungan'])->name('audit.kunjungan.updateStatus');
    Route::delete('/audit/internal/{id}', [AuditInternalController::class, 'destroyInternal'])->name('audit.internal.destroy');
    Route::put('/audit/internal/{id}/status', [AuditInternalController::class, 'updateStatus'])->name('audit.internal.updateStatus');

    //PENGINGAT
    Route::get('/pengingat/tambah', [App\Http\Controllers\PengingatController::class, 'create'])->name('pengingat.create');
    Route::post('/pengingat/send', [App\Http\Controllers\PengingatController::class, 'send'])->name('pengingat.send');

    // lahan
    Route::post('/lahan/preview-import', [LahanController::class, 'previewImport'])
    ->name('lahan.preview_import');

    Route::post('/lahan/process-import', [LahanController::class, 'processImport'])
        ->name('lahan.process_import');

    //Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/popup', [NotifikasiController::class, 'getPopup']);
    Route::get('/notifikasi/count', [NotifikasiController::class, 'count']);
    Route::post('/notifikasi/mark-all', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.markAllAsRead');
    Route::post('/notifikasi/read/{id}', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.markAsRead');
    Route::post('/tugas/{id}/selesai', [DashboardController::class, 'completeTask'])->name('tugas.complete');
});
