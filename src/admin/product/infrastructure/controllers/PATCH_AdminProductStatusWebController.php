<?php

namespace Src\admin\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Src\admin\product\application\useCases\ActivateProductUseCase;
use Src\admin\product\application\useCases\DeactivateProductUseCase;

final class PATCH_AdminProductStatusWebController extends Controller
{
    public function __construct(
        private ActivateProductUseCase $activateProductUseCase,
        private DeactivateProductUseCase $deactivateProductUseCase,
    ) {}

    public function toggle(Request $request, int $id): RedirectResponse
    {
        $activate = $request->boolean('activate');

        if ($activate) {
            $this->activateProductUseCase->execute($id);
        } else {
            $this->deactivateProductUseCase->execute($id);
        }

        return back()->with('success', $activate ? 'Producto activado.' : 'Producto desactivado.');
    }
}