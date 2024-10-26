<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\WisataKulinerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Lupa Password
Route::get('/lupapassword', [ForgotPasswordController::class, 'index'])->name('lupaPassword');
Route::post('/sendforgotpasswordemail', [ForgotPasswordController::class, 'sendForgotPasswordEmail'])->name('sendForgotPasswordEmail');

// Berhubungan dengan email
Route::get('/verification/{id}', [EmailController::class, 'verification'])->name('verification');
Route::get('/forgotpassword/{id}', [EmailController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/activation', [EmailController::class, 'activation'])->name('activation');
Route::post('/newpassword', [EmailController::class, 'newPassword'])->name('newPassword');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Wisata Kuliner
Route::get('/wisatakuliner', [WisataKulinerController::class, 'index'])->name('wisataKuliner');
Route::post('/wisatakuliner/store', [WisataKulinerController::class, 'store'])->name('wisataKulinerStore');
Route::get('/wisatakuliner/fetchall', [WisataKulinerController::class, 'fetchAll'])->name('wisataKulinerFetchAll');
Route::delete('/wisatakuliner/delete', [WisataKulinerController::class, 'delete'])->name('wisataKulinerDelete');
Route::get('/wisatakuliner/edit', [WisataKulinerController::class, 'edit'])->name('wisataKulinerEdit');
Route::post('/wisatakuliner/update', [WisataKulinerController::class, 'update'])->name('wisataKulinerUpdate');

// Kategori
Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori');
Route::post('/kategori/store', [KategoriController::class, 'store'])->name('kategoriStore');
Route::get('/kategori/fetchall', [KategoriController::class, 'fetchAll'])->name('kategoriFetchAll');
Route::delete('/kategori/delete', [KategoriController::class, 'delete'])->name('kategoriDelete');
Route::get('/kategori/edit', [KategoriController::class, 'edit'])->name('kategoriEdit');
Route::post('/kategori/update', [KategoriController::class, 'update'])->name('kategoriUpdate');

// Menu
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::post('/menu/store', [MenuController::class, 'store'])->name('menuStore');
Route::get('/menu/fetchall', [MenuController::class, 'fetchAll'])->name('menuFetchAll');
Route::delete('/menu/delete', [MenuController::class, 'delete'])->name('menuDelete');
Route::get('/menu/edit', [MenuController::class, 'edit'])->name('menuEdit');
Route::post('/menu/update', [MenuController::class, 'update'])->name('menuUpdate');

// Transaksi
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi');
Route::post('/transaksi/store', [TransaksiController::class, 'store'])->name('transaksiStore');
Route::get('/transaksi/fetchall', [TransaksiController::class, 'fetchAll'])->name('transaksiFetchAll');
Route::delete('/transaksi/delete', [TransaksiController::class, 'delete'])->name('transaksiDelete');
Route::get('/transaksi/edit', [TransaksiController::class, 'edit'])->name('transaksiEdit');
Route::post('/transaksi/update', [TransaksiController::class, 'update'])->name('transaksiUpdate');
Route::get('/transaksi/fetchmenus', [TransaksiController::class, 'fetchMenus'])->name('transaksiFetchMenus');

// Pengguna
Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna');
// Route::post('/pengguna/store', [PenggunaController::class, 'store'])->name('penggunaStore');
Route::get('/pengguna/fetchall', [PenggunaController::class, 'fetchAll'])->name('penggunaFetchAll');
Route::delete('/pengguna/delete', [PenggunaController::class, 'delete'])->name('penggunaDelete');
Route::get('/pengguna/edit', [PenggunaController::class, 'edit'])->name('penggunaEdit');
Route::post('/pengguna/update', [PenggunaController::class, 'update'])->name('penggunaUpdate');
