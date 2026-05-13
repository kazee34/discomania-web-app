<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\admin\user\application\useCases\SearchAllAdminUseCase;

final class GET_GetAllAdminsController extends Controller
{
    public function __construct(
        private SearchAllAdminUseCase $searchAllAdminUseCase
    ) {}

    public function index(): JsonResponse
    {
        try {
            $admins = $this->searchAllAdminUseCase->execute();

            $data = array_map(function ($admin) {
                return [
                    'id' => $admin->id(),
                    'user_id' => $admin->userId(),
                    'role' => $admin->role()->value,
                    'is_active' => $admin->isActive(),
                    'created_by' => $admin->createdBy(),
                ];
            }, $admins);

            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => 'success',
                'count' => count($data),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Internal server error',
            ], 500);
        }
    }
}
