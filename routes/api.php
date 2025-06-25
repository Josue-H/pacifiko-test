<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\CompraController;

Route::post('/productos', [ProductoController::class, 'store']);
Route::get('/productos', [ProductoController::class, 'index']);
Route::get('/productos/{id}', [ProductoController::class, 'show']);

Route::post('/compras', [CompraController::class, 'store']);
Route::get('/compras', [CompraController::class, 'index']);
