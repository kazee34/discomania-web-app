<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Src\customer\order\infrastructure\controllers\DELETE_CancelOrderWebController;
use Src\customer\order\infrastructure\controllers\GET_ProfileOrderDetailController;
use Src\customer\order\infrastructure\controllers\GET_ProfileOrdersController;
use Src\customer\order\infrastructure\controllers\POST_CheckoutController;
use Src\customer\product\infrastructure\controllers\GET_ShopIndexController;
use Src\customer\product\infrastructure\controllers\GET_ShopProductController;
use Src\admin\product\infrastructure\controllers\DELETE_AdminProductWebController;
use Src\admin\product\infrastructure\controllers\GET_AdminProductEditWebController;
use Src\admin\product\infrastructure\controllers\GET_AdminProductsWebController;
use Src\admin\product\infrastructure\controllers\PATCH_AdminProductStatusWebController;
use Src\admin\product\infrastructure\controllers\POST_AdminProductWebController;
use Src\admin\product\infrastructure\controllers\PUT_AdminProductWebController;
use Src\customer\user\infrastructure\controllers\GET_ProfileController;
use Src\customer\user\infrastructure\controllers\PUT_UpdateProfileController;


Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::get('/shop', [GET_ShopIndexController::class, 'index'])->name('shop.index');
Route::get('/shop/{slug}', [GET_ShopProductController::class, 'show'])->name('shop.product');
Route::inertia('/cart', 'cart/Index')->name('cart.index');

Route::middleware(['auth'])->group(function () {
    Route::post('/checkout', [POST_CheckoutController::class, 'store'])->name('checkout');

    Route::get('/profile', [GET_ProfileController::class, 'show'])->name('customer.profile');
    Route::put('/profile', [PUT_UpdateProfileController::class, 'update'])->name('customer.profile.update');
    Route::get('/profile/orders', [GET_ProfileOrdersController::class, 'index'])->name('customer.orders');
    Route::get('/profile/orders/{orderNumber}', [GET_ProfileOrderDetailController::class, 'show'])->name('customer.orders.detail');
    Route::delete('/profile/orders/{orderNumber}', [DELETE_CancelOrderWebController::class, 'destroy'])->name('customer.orders.cancel');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/products',              [GET_AdminProductsWebController::class,    'index'])->name('products.index');
    Route::get('/products/create',       [GET_AdminProductsWebController::class,    'create'])->name('products.create');
    Route::post('/products',             [POST_AdminProductWebController::class,    'store'])->name('products.store');
    Route::get('/products/{id}/edit',    [GET_AdminProductEditWebController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}',         [PUT_AdminProductWebController::class,     'update'])->name('products.update');
    Route::delete('/products/{id}',      [DELETE_AdminProductWebController::class,  'destroy'])->name('products.destroy');
    Route::patch('/products/{id}/status',[PATCH_AdminProductStatusWebController::class, 'toggle'])->name('products.status');
});

require __DIR__.'/settings.php';
