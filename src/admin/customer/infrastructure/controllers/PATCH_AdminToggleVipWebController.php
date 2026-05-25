<?php

namespace Src\admin\customer\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Src\admin\customer\application\useCases\AdminToggleVipUseCase;

final class PATCH_AdminToggleVipWebController extends Controller
{
    public function __construct(
        private AdminToggleVipUseCase $useCase,
    ) {}

    public function toggle(Request $request, int $id): RedirectResponse
    {
        $isVip = (bool) $request->input('is_vip', false);

        $this->useCase->execute($id, $isVip);

        return back()->with('success', $isVip
            ? 'Cliente marcado como VIP.'
            : 'Estado VIP retirado al cliente.'
        );
    }
}
