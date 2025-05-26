<?php

use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [ProductoController::class, 'home'])->name('home');



Route::post('pedidos', [PedidoController::class, 'store'])->name('pedidos.store');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', [PedidoController::class, 'dashboard'])->name('dashboard');

    Route::resource('productos', ProductoController::class)->except('show');

    Route::get('pedidos', [PedidoController::class, 'reporteCantidades'])->name('pedidos.reporte');
    //Route::get('pedidos/reportes', [PedidoController::class, 'reporteCantidades'])->name('pedidos.reporte');

});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
