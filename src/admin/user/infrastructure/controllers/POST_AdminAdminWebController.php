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
        ], [
            'name.required'     => 'El nombre es obligatorio.',
            'name.max'          => 'El nombre no puede superar los 255 caracteres.',
            'email.required'    => 'El correo electrónico es obligatorio.',
            'email.email'       => 'El formato del correo electrónico no es válido.',
            'email.unique'      => 'Este correo electrónico ya está en uso.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'=> 'La confirmación de contraseña no coincide.',
            'role.required'     => 'El rol es obligatorio.',
            'role.in'           => 'El rol seleccionado no es válido.',
        ]);

        $this->useCase->execute(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'],
        );

        return redirect()->route('admin.admins.index')
            ->with('success', 'Administrador creado correctamente.');
    }
}
