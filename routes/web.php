<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetaniController;
use Illuminate\Support\Facades\Route;

// Menampilkan halaman login
Route::get('/', [LoginController::class, 'index'])->name('login');

// Proses Login (Ini yang dipanggil oleh form login.blade.php kamu)
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.process');

// Proses Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// --- SEMUA ROUTE DI BAWAH INI HARUS LOGIN ---
Route::middleware(['auth'])->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('super_admin.dashboard');

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard'); 
    })->name('admin.dashboard');

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


    // --- DATA PETANI ---
    Route::get('/data-petani', [PetaniController::class, 'index'])->name('petani.index');

    Route::get('/data-petani/{id}', function ($id) {
        return view('super_admin.petani.show', ['id' => $id]);
    })->name('petani.show');


    // --- DATA LAHAN ---
    Route::get('/data-lahan', function () {
        return view('super_admin.lahan.index');
    })->name('lahan.index');

    Route::get('/data-lahan/{id}', function ($id) {
        return view('super_admin.lahan.show', ['id' => $id]);
    })->name('lahan.show');


    // --- KEUANGAN ---
    Route::get('/data-keuangan', function () {
        return view('super_admin.keuangan.index');
    })->name('keuangan.index');

    Route::get('/data-keuangan/{id}', function ($id) {
        return view('super_admin.keuangan.show', ['id' => $id]);
    })->name('keuangan.show');


    // --- LAIN-LAIN ---
    Route::get('/pengingat/tambah', function () {
        return view('super_admin.pengingat.create');
    })->name('pengingat.create');

    Route::get('/notifikasi', function () {
        return view('super_admin.notifikasi.index');
    })->name('notifikasi.index');

});