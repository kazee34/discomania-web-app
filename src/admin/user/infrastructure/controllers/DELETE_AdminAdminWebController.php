<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Src\admin\user\application\useCases\DeleteAdminUseCase;
use Src\admin\user\domain\repositories\AdminRepositoryInterface;

final class DELETE_AdminAdminWebController extends Controller
{
    public function __construct(
        private DeleteAdminUseCase $useCase,
        private AdminRepositoryInterface $adminRepository,
    ) {}

    public function destroy(int $id, Request $request): RedirectResponse
    {
        $currentAdmin = $this->adminRepository->findByUserId($request->user()->id);

        try {
            $this->useCase->execute($id, $currentAdmin?->id());
        } catch (\DomainException $e) {
            return redirect()->route('admin.admins.index')
                ->with('error', $e->getMessage());
        }

        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrador eliminado correctamente.');
    }
}
