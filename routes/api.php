<?php

use App\Http\Controllers\Api\ProdukController;
use App\Http\Controllers\Api\TransaksiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('products', ProdukController::class);

Route::get('transactions', [TransaksiController::class, 'index']);