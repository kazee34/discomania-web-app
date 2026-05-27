<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Src\admin\user\application\useCases\CreateUserUseCase;

final class POST_AdminAdminWebController extends Controller
{
    public function __construct(
        private CreateUserUseCase $useCase,
    ) {}

    public function store(Request $request): RedirectResponse
    {
        $role = AdminModel::where('user_id', $request->user()->id)->value('role');
        abort_unless(in_array($role, ['super_admin', 'admin']), 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
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
