<?php

use Illuminate\Support\Facades\Route;
use Src\customer\cart\infrastructure\controllers\DELETE_ClearCartController;
use Src\customer\cart\infrastructure\controllers\DELETE_RemoveItemFromCartController;
use Src\customer\cart\infrastructure\controllers\GET_FindCartController;
use Src\customer\cart\infrastructure\controllers\POST_AddItemToCartController;
use Src\customer\cart\infrastructure\controllers\POST_CreateCartController;
use Src\customer\cart\infrastructure\controllers\PUT_UpdateCartItemQuantityController;

/**
 * ===========================================
 * Cart
 * Recurso raíz: /cart
 * ===========================================
 */
Route::prefix('cart')->name('cart.')->group(function () {
    // POST   /api/v1/customer/cart              → Crea un carrito.
    Route::post('/', [POST_CreateCartController::class, 'store'])->name('store');
    // GET    /api/v1/customer/cart/{token}       → Obtiene un carrito por token.
    Route::get('/{token}', [GET_FindCartController::class, 'show'])->name('show');
    // DELETE /api/v1/customer/cart/{token}       → Vacía el carrito.
    Route::delete('/{token}', [DELETE_ClearCartController::class, 'destroy'])->name('clear');

    // Items del carrito
    Route::prefix('/{token}/items')->name('items.')->group(function () {
        // POST   /api/v1/customer/cart/{token}/items              → Añade un producto al carrito.
        Route::post('/', [POST_AddItemToCartController::class, 'store'])->name('store');
        // PUT    /api/v1/customer/cart/{token}/items/{item}       → Actualiza la cantidad de un item.
        Route::put('/{item}', [PUT_UpdateCartItemQuantityController::class, 'update'])->name('update');
        // DELETE /api/v1/customer/cart/{token}/items/{item}       → Elimina un item del carrito.
        Route::delete('/{item}', [DELETE_RemoveItemFromCartController::class, 'destroy'])->name('destroy');
    });
});
