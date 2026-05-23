<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use src\admin\user\application\useCases\CreateUserUseCase;

final class POST_AdminAdminWebController extends Controller
{
    public function __construct(
        private CreateUserUseCase $useCase,
    ) {}

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'in:admin,editor'],
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
