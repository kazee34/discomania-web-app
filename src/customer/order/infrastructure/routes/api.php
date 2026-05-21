<?php

use Illuminate\Support\Facades\Route;
use Src\customer\order\infrastructure\controllers\GET_FindOrderController;
use Src\customer\order\infrastructure\controllers\GET_GetCustomerOrdersController;
use Src\customer\order\infrastructure\controllers\POST_CreateOrderController;
use Src\customer\order\infrastructure\controllers\PUT_CancelOrderController;

/**
 * ===========================================
 * Orders
 * Recurso raíz: /orders
 * ===========================================
 */
Route::prefix('orders')->name('orders.')->group(function () {
    // POST /api/v1/customer/orders                         → Crea una orden a partir del carrito.
    Route::post('/', [POST_CreateOrderController::class, 'store'])->name('store');
    // GET  /api/v1/customer/orders/customer/{customerId}   → Lista las órdenes de un cliente.
    Route::get('/customer/{customerId}', [GET_GetCustomerOrdersController::class, 'index'])->name('index');
    // GET  /api/v1/customer/orders/{orderNumber}           → Obtiene una orden por su número.
    Route::get('/{orderNumber}', [GET_FindOrderController::class, 'show'])->name('show');
    // PUT  /api/v1/customer/orders/{orderNumber}/cancel    → Cancela una orden pendiente.
    Route::put('/{orderNumber}/cancel', [PUT_CancelOrderController::class, 'update'])->name('cancel');
});