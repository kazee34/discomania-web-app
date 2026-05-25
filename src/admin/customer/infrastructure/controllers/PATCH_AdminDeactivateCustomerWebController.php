<?php

namespace Src\admin\customer\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Src\admin\customer\application\useCases\AdminDeactivateCustomerUseCase;

final class PATCH_AdminDeactivateCustomerWebController extends Controller
{
    public function __construct(
        private AdminDeactivateCustomerUseCase $useCase,
    ) {}

    public function deactivate(Request $request, int $id): RedirectResponse
    {
        $activate = (bool) $request->input('activate', false);

        $this->useCase->execute($id, $activate);

        return back()->with('success', $activate
            ? 'Cliente activado correctamente.'
            : 'Cliente desactivado correctamente.'
        );
    }
}
