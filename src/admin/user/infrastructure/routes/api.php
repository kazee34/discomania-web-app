<?php

use Illuminate\Support\Facades\Route;
use Src\admin\user\infrastructure\controllers\PUT_ActivateAdminController;
use Src\admin\user\infrastructure\controllers\POST_CreateUserController;
use Src\admin\user\infrastructure\controllers\PUT_DeactivateAdminController;
use Src\admin\user\infrastructure\controllers\DELETE_DeleteAdminByIdController;
use Src\admin\user\infrastructure\controllers\GET_GetAllAdminsController;
use Src\admin\user\infrastructure\controllers\GET_GetAdminByEmailController;
use Src\admin\user\infrastructure\controllers\GET_GetAdminByIdController;
use Src\admin\user\infrastructure\controllers\GET_GetUserByIdController;

/**
 * ===========================================
 * Users (Base)
 * Recurso raíz: /users
 * ===========================================
 */
Route::prefix('users')->name('users.')->group(function () {
    // POST   /api/v1/users → Crea un usuario. El evento de dominio creará el administrador automáticamente.
    Route::post('/', [POST_CreateUserController::class, 'store'])->name('store');
    // GET    /api/v1/users/{user} → Muestra un usuario específico.
    Route::get('/{user}', [GET_GetUserByIdController::class, 'show'])->name('show');
});

/**
 * ===========================================
 * Admins
 * Recurso raíz: /admins
 * Acciones de gestión general
 * ===========================================
 */
Route::prefix('admins')->name('admins.')->group(function () {
    // GET    /api/v1/admins → Lista todos los administradores.
    Route::get('/', [GET_GetAllAdminsController::class, 'index'])->name('index');
    // GET    /api/v1/admins/{admin} → Muestra un administrador por su ID.
    Route::get('/{admin}', [GET_GetAdminByIdController::class, 'show'])->name('show');
    // DELETE /api/v1/admins/{admin} → Elimina un administrador.
    Route::delete('/{admin}', [DELETE_DeleteAdminByIdController::class, 'destroy'])->name('destroy');

    // ========== Sub-recursos y Acciones Personalizadas ==========
    // GET    /api/v1/admins/by-email/{email} → Obtiene un administrador por su correo electrónico.
    Route::get('/by-email/{email}', [GET_GetAdminByEmailController::class, 'show'])->name('show');

    // Acciones de estado sobre el recurso "admin".
    Route::put('/{admin}/activate', [PUT_ActivateAdminController::class, 'update'])->name('update');
    Route::put('/{admin}/deactivate', [PUT_DeactivateAdminController::class, 'update'])->name('update');
});
