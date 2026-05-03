<?php

use Illuminate\Support\Facades\Route;

// Opsi 1: Supaya pas buka 127.0.0.1:8000 LANGSUNG muncul login
Route::get('/', function () {
    return view('auth.login');
})->name('login');


//dashboard super admin
Route::get('/dashboard', function () {
    return view('super_admin.dashboard');
});


//data user
Route::get('/data-user', function () {
    return view('super_admin.user.index');
})->name('user.index');


//tambah user
Route::get('/data-user/create', function () {
    return view('super_admin.user.create');
})->name('user.create');

//edit user
Route::get('/data-user/{id}/edit', function ($id) {
    return view('super_admin.user.edit', ['id' => $id]);
})->name('user.edit');

//data petani
Route::get('/data-petani', function () {
    return view('super_admin.petani.index');
})->name('petani.index');

//detail petani
Route::get('/data-petani/{id}', function ($id) {
    return view('super_admin.petani.show', ['id' => $id]);
})->name('petani.show');


//data lahan
Route::get('/data-lahan', function () {
    return view('super_admin.lahan.index');
})->name('lahan.index');

//detail lahan
Route::get('/data-lahan/{id}', function ($id) {
    return view('super_admin.lahan.show', ['id' => $id]);
})->name('lahan.show');


//keuangan
Route::get('/data-keuangan', function () {
    return view('super_admin.keuangan.index');
})->name('keuangan.index');

//detail keuangan
Route::get('/data-keuangan/{id}', function ($id) {
    return view('super_admin.keuangan.show', ['id' => $id]);
})->name('keuangan.show');

//pengingat
Route::get('/pengingat/tambah', function () {
    return view('super_admin.pengingat.create');
})->name('pengingat.create');

//notifikasi
Route::get('/notifikasi', function () {
    return view('super_admin.notifikasi.index');
})->name('notifikasi.index');