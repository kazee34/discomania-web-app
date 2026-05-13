<?php

use Illuminate\Support\Facades\Route;
use Src\admin\product\infrastructure\controllers\DELETE_DeleteProductByIdController;
use Src\admin\product\infrastructure\controllers\GET_FindProductByIdController;
use Src\admin\product\infrastructure\controllers\GET_SearchAllProductsController;
use Src\admin\product\infrastructure\controllers\POST_CreateProductController;
use Src\admin\product\infrastructure\controllers\PUT_ActivateProductController;
use Src\admin\product\infrastructure\controllers\PUT_DeactivateProductController;
use Src\admin\product\infrastructure\controllers\PUT_UpdateProductController;

/**
 * ===========================================
 * Products
 * Recurso raíz: /products
 * ===========================================
 */
Route::prefix('products')->name('products.')->group(function () {
    // GET    /api/v1/admin/products       → Lista todos los productos.
    Route::get('/', [GET_SearchAllProductsController::class, 'index'])->name('index');
    // POST   /api/v1/admin/products       → Crea un producto.
    Route::post('/', [POST_CreateProductController::class, 'store'])->name('store');
    // GET    /api/v1/admin/products/{product} → Muestra un producto por su ID.
    Route::get('/{product}', [GET_FindProductByIdController::class, 'show'])->name('show');
    // PUT    /api/v1/admin/products/{product} → Actualiza un producto.
    Route::put('/{product}', [PUT_UpdateProductController::class, 'update'])->name('update');
    // DELETE /api/v1/admin/products/{product} → Elimina un producto.
    Route::delete('/{product}', [DELETE_DeleteProductByIdController::class, 'destroy'])->name('destroy');

    // Acciones de estado
    Route::put('/{product}/activate', [PUT_ActivateProductController::class, 'update'])->name('activate');
    Route::put('/{product}/deactivate', [PUT_DeactivateProductController::class, 'update'])->name('deactivate');
});