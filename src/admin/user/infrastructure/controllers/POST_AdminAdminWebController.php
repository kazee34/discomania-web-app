<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use App\Rules\NoSpecialCharacters;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Src\admin\user\application\useCases\CreateUserUseCase;
use Src\admin\user\application\useCases\FindAdminByUserIdUseCase;

final class POST_AdminAdminWebController extends Controller
{
    public function __construct(
        private FindAdminByUserIdUseCase $findAdminByUserId,
        private CreateUserUseCase $useCase,
    ) {}

    public function store(Request $request): RedirectResponse
    {
        $admin = $this->findAdminByUserId->execute($request->user()->id);
        abort_unless(in_array($admin?->role()->value(), ['super_admin', 'admin']), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', new NoSpecialCharacters],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:admin,editor'],
        ]);

        $this->useCase->execute(
            name: $data['name'],
            email: $data['email'],
            password: bcrypt($data['password']),
            role: $data['role'],
        );

        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrador creado correctamente.');
    }
}
