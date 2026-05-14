<?php

use Illuminate\Support\Facades\Route;
use Src\customer\user\infrastructure\controllers\DELETE_CustomerController;
use Src\customer\user\infrastructure\controllers\GET_CustomerByIdController;
use Src\customer\user\infrastructure\controllers\PATCH_DeactivateCustomerController;
use Src\customer\user\infrastructure\controllers\POST_CreateCustomerController;

Route::post('/', POST_CreateCustomerController::class);

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/{id}', GET_CustomerByIdController::class);
    Route::patch('/{id}/deactivate', PATCH_DeactivateCustomerController::class);
    Route::delete('/{id}', DELETE_CustomerController::class);
});
