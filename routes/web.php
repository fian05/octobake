<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('login');
})->name('login');
Route::post('login_proses', [UserController::class, 'login'])->name('login_proses');
Route::middleware('auth')->group(function() {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('logout', [UserController::class, 'logout'])->name('logout');
    Route::get('ubahPassword', function() {return view('ubahPw');})->name('viewUbahPw');
    Route::put('updatePw', [UserController::class, 'updatePw'])->name('karyawan_updatePw');
    Route::put('{id}/reset', [UserController::class, 'resetPw'])->name('karyawan_reset');
    
    Route::get('karyawan', [UserController::class, 'index'])->name('karyawan_view');
    Route::post('karyawan/tambah', [UserController::class, 'store'])->name('karyawan_tambah');
    Route::put('karyawan/ubah', [UserController::class, 'update'])->name('karyawan_ubah');
    Route::delete('karyawan/{id}/hapus', [UserController::class, 'destroy'])->name('karyawan_hapus');

    Route::get('produk', [ProdukController::class, 'index'])->name('produk_view');
    Route::post('produk/tambah', [ProdukController::class, 'store'])->name('produk_tambah');
    Route::put('produk/ubah', [ProdukController::class, 'update'])->name('produk_ubah');
    Route::delete('produk/{id}/hapus', [ProdukController::class, 'destroy'])->name('produk_hapus');

    Route::get('pembayaran', [PembayaranController::class, 'index'])->name('pembayaran_view');
    Route::post('pembayaran/tambah', [PembayaranController::class, 'store'])->name('pembayaran_tambah');
});