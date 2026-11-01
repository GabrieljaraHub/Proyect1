<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockMovementController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Autenticación)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

/*
|--------------------------------------------------------------------------
| Rutas Protegidas (Requieren Sesión Activa)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Redirección inicial al Dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Panel de Control Principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Cierre de Sesión Seguro
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Módulos CRUD de Inventario (excluyendo 'show')
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('suppliers', SupplierController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);

    // Movimientos de Stock (Kardex y Trazabilidad)
    Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
    Route::post('/stock-movements', [StockMovementController::class, 'store'])->name('stock-movements.store');
});
