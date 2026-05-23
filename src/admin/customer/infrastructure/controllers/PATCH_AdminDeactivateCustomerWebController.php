<?php

namespace Src\admin\customer\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Src\admin\customer\application\useCases\AdminDeactivateCustomerUseCase;

final class PATCH_AdminDeactivateCustomerWebController extends Controller
{
    public function __construct(
        private AdminDeactivateCustomerUseCase $useCase,
    ) {}

    public function deactivate(int $id): RedirectResponse
    {
        $this->useCase->execute($id);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Cliente desactivado correctamente.');
    }
}
