<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    
    Route::prefix('admin')->group(function () {
        require base_path('src/admin/user/infrastructure/routes/api.php');
    });

    Route::prefix('customer')->group(function () {
        require base_path('src/customer/user/infrastructure/routes/api.php');
    });
});
