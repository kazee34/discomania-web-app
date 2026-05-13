<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\admin\user\application\useCases\FindAdminByIdUseCase;
use Src\admin\user\domain\exceptions\AdminNotFoundException;

final class GET_GetAdminByIdController extends Controller
{
    public function __construct(
        private FindAdminByIdUseCase $findAdminByIdUseCase
    ) {}

    public function show(int $id): JsonResponse
    {
        try {
            $admin = $this->findAdminByIdUseCase->execute($id);

            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $admin->id(),
                    'user_id' => $admin->userId(),
                    'role' => $admin->role()->value,
                    'is_active' => $admin->isActive(),
                    'created_by' => $admin->createdBy(),
                    'created_at' => $admin->createdAt()?->format('Y-m-d H:i:s'),
                ],
                'message' => 'success',
            ]);

        } catch (AdminNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Internal server error',
            ], 500);
        }
    }
}