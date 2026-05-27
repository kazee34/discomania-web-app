<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Src\admin\user\application\useCases\ActivateAdminUseCase;
use Src\admin\user\application\useCases\DeactivateAdminUseCase;

final class PATCH_AdminToggleAdminWebController extends Controller
{
    public function __construct(
        private ActivateAdminUseCase $activateUseCase,
        private DeactivateAdminUseCase $deactivateUseCase,
    ) {}

    public function toggle(int $id, Request $request): RedirectResponse
    {
        $activate = $request->boolean('activate');

        try {
            $activate
                ? $this->activateUseCase->execute($id)
                : $this->deactivateUseCase->execute($id);
        } catch (\DomainException $e) {
            return redirect()->route('admin.admins.index')
                ->with('error', $e->getMessage());
        }

        $msg = $activate ? 'Administrador activado.' : 'Administrador desactivado.';

        return redirect()->route('admin.admins.index')
            ->with('success', $msg);
    }
}
