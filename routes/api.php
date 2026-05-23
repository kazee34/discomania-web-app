<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::prefix('customer')->group(function () {
        require base_path('src/customer/cart/infrastructure/routes/api.php');
    });
});
