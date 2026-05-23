<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminModel;
use Inertia\Inertia;
use Inertia\Response;

final class GET_AdminAdminsWebController extends Controller
{
    public function index(): Response
    {
        $admins = AdminModel::with('user')->get()->map(fn ($a) => [
            'id'        => $a->id,
            'userId'    => $a->user_id,
            'name'      => $a->user?->name,
            'email'     => $a->user?->email,
            'role'      => $a->role,
            'isActive'  => $a->is_active,
            'createdAt' => $a->created_at?->format('Y-m-d'),
        ])->all();

        return Inertia::render('admin/admins/Index', [
            'admins' => $admins,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/admins/Create');
    }
}
