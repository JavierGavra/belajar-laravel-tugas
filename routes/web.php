<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.session')->group(function () {
    Route::get('/', [HomeController::class, 'index']);

    Route::prefix('produk')->group(function () {
        Route::get('/', [ProdukController::class, 'index']);
        Route::post('/', [ProdukController::class, 'create']);
        Route::post('/edit/{id}', [ProdukController::class, 'edit']);
        Route::get('/delete/{id}', [ProdukController::class, 'delete']);
        Route::get('/download', [ProdukController::class, 'download']);
    });

    Route::prefix('keranjang')->group(function () {
        Route::get('/', [TransaksiController::class, 'index']);
        Route::post('/', [TransaksiController::class, 'cart_add']);
        Route::post('/edit', [TransaksiController::class, 'cart_edit']);
        Route::get('/delete/{id}', [TransaksiController::class, 'cart_delete']);
        Route::get('/clear', [TransaksiController::class, 'cart_clear']);
    });
});

Route::post('/buy', [TransaksiController::class, 'buy']);
Route::get('/checkout', [TransaksiController::class, 'checkout']);
Route::get('/history', [TransaksiController::class, 'history']);

Route::get('/login', [AuthController::class, 'login']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);
Route::get('ajax/destinations', [TransaksiController::class, 'destinations'])->middleware('auth.session')->name('ajax.destinations');
Route::get('ajax/costs', [TransaksiController::class, 'costs'])->middleware('auth.session')->name('ajax.costs');
