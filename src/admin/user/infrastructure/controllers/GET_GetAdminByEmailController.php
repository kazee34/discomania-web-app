<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\admin\user\application\useCases\FindAdminByEmailUseCase;
use Src\admin\user\domain\exceptions\AdminNotFoundException;

final class GET_GetAdminByEmailController extends Controller
{
    public function __construct(
        private FindAdminByEmailUseCase $findAdminByEmailUseCase
    ) {}

    public function show(string $email): JsonResponse
    {
        try {
            $admin = $this->findAdminByEmailUseCase->execute($email);

            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $admin->id(),
                    'user_id' => $admin->userId(),
                    'role' => $admin->role()->value,
                    'is_active' => $admin->isActive(),
                ],
                'message' => 'success',
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => false,
                'error' => 'Invalid ID',
            ], 400);
        } catch (AdminNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Internal Server Error',
            ], 500);
        }
    }
}