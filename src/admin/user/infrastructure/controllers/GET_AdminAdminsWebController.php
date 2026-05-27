<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Src\admin\user\application\useCases\FindAdminByUserIdUseCase;
use Src\admin\user\application\useCases\SearchAllAdminUseCase;

final class GET_AdminAdminsWebController extends Controller
{
    public function __construct(
        private SearchAllAdminUseCase $searchAllAdmins,
        private FindAdminByUserIdUseCase $findAdminByUserId,
    ) {}

    public function index(): Response
    {
        $admins = array_map(fn ($admin) => [
            'id' => $admin->id(),
            'userId' => $admin->userId(),
            'name' => $admin->name(),
            'email' => $admin->email(),
            'role' => $admin->role()->value(),
            'isActive' => $admin->isActive(),
            'createdAt' => $admin->createdAt()?->format('Y-m-d'),
        ], $this->searchAllAdmins->execute());

        return Inertia::render('admin/admins/Index', [
            'admins' => $admins,
        ]);
    }

    public function create(Request $request): Response
    {
        $admin = $this->findAdminByUserId->execute($request->user()->id);
        abort_unless(in_array($admin?->role()->value(), ['super_admin', 'admin']), 403);

        return Inertia::render('admin/admins/Create');
    }
}
