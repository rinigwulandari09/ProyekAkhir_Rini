<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetaniController;
use App\Http\Controllers\LahanController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\NotifikasiController;

Route::get('/', [LoginController::class,'index'])
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

    // untuk update status petani di dashboard
    Route::put('/dashboard/petani/{id}/status', [DashboardController::class, 'updateStatus'])->name('petani.updateStatus');

    // dashboard admin
    
    // --- MANAJEMEN USER (CRUD) ---
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
    Route::get('/petani', [PetaniController::class, 'index'])
        ->name('petani.index');

    // Menampilkan halaman form edit petani
    Route::get('/petani/{id}/edit', [PetaniController::class, 'edit'])
        ->name('petani.edit');

    // Proses memperbarui status/data petani
    Route::post('/petani/{id}/update-status', [PetaniController::class, 'updateStatus'])
        ->name('petani.updateStatus');

    // Proses menghapus data petani (Sekarang bisa dieksekusi oleh Admin & Super Admin)
    Route::delete('/petani/{id}', [PetaniController::class, 'destroy'])
        ->name('petani.destroy');

    
    // --- DATA LAHAN ---
    // Route::get('/data-lahan', [LahanController::class, 'index'])->name('lahan.index');

    // Route::get('/data-lahan/{id}', [LahanController::class, 'show'])->name('lahan.show');


    // --- DATA LAHAN ---
    // 1. Halaman Utama Daftar Lahan (Bisa diakses Admin & Super Admin)
    Route::get('/lahan', [LahanController::class, 'index'])->name('lahan.index');

    // 2. Halaman Form Tambah Lahan (Khusus Admin - Membuka view/admin/lahan/tambah.blade.php)
    Route::get('/lahan/create', [LahanController::class, 'create'])->name('lahan.create');

    // 3. Eksekusi Simpan Data Lahan Baru ke Database (Dipanggil saat submit form)
    Route::post('/lahan', [LahanController::class, 'store'])->name('lahan.store');

    // 4. Halaman Detail Lahan / Lihat Peta
    Route::get('/lahan/{id}', [LahanController::class, 'show'])->name('lahan.show');

    // 5. Halaman Edit Lahan
    Route::get('/lahan/{id}/edit', [LahanController::class, 'edit'])->name('lahan.edit');

    // 6. Eksekusi Update Data Lahan
    Route::put('/lahan/{id}', [LahanController::class, 'update'])->name('lahan.update');

    // 7. Eksekusi Hapus Data Lahan
    Route::delete('/lahan/{id}', [LahanController::class, 'destroy'])->name('lahan.destroy');


    Route::post('/lahan/import-geojson', [App\Http\Controllers\LahanController::class, 'importGeoJson'])->name('lahan.import_geojson');

    // --- KEUANGAN ---
    Route::get('/keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::get('/data-keuangan/{id}', [KeuanganController::class, 'show'])->name('keuangan.show');


    // --- LAIN-LAIN ---
    Route::get('/pengingat/tambah', [App\Http\Controllers\PengingatController::class, 'create'])->name('pengingat.create');
    Route::post('/pengingat/send', [App\Http\Controllers\PengingatController::class, 'send'])->name('pengingat.send');

    //Lahan Admin
    // Route::get('/admin/lahan', [LahanController::class, 'index'])->name('admin.lahan.index');
    // Route::get('/admin/lahan/create', [LahanController::class, 'create'])->name('admin.lahan.create');
    // Route::post('/admin/lahan/store', [LahanController::class, 'store'])->name('admin.lahan.store');

    // lahan
    Route::post('/lahan/preview-import', [LahanController::class, 'previewImport'])
    ->name('lahan.preview_import');

    Route::post('/lahan/process-import', [LahanController::class, 'processImport'])
        ->name('lahan.process_import');


    // notifikasi
    // Notifikasi Routes
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/popup', [NotifikasiController::class, 'getPopup']);
    Route::get('/notifikasi/count', [NotifikasiController::class, 'count']);

    // URL disesuaikan dengan fetch JavaScript kemarin
    // Pastikan url-nya '/notifikasi/mark-all' sesuai yang diminta JavaScript-mu
    Route::post('/notifikasi/mark-all', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.markAllAsRead');

    // Pastikan juga url read per item sudah terpasang seperti ini
    Route::post('/notifikasi/read/{id}', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.markAsRead');
    });
