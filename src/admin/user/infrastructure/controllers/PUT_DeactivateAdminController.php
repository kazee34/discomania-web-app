<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\admin\user\application\useCases\DeactivateAdminUseCase;
use Src\admin\user\domain\exceptions\AdminNotFoundException;
use Src\admin\user\domain\exceptions\CannotDeleteSuperAdminException;

final class PUT_DeactivateAdminController extends Controller
{
    public function __construct(
        private DeactivateAdminUseCase $deactivateAdminUseCase
    ) {}

    public function update(int $id): JsonResponse
    {
        try {
            $this->deactivateAdminUseCase->execute($id);

            return response()->json([
                'status' => true,
                'data' => $id,
                'message' => 'Admin deactivated successfully',
            ]);
        } catch (AdminNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 404);
        } catch (CannotDeleteSuperAdminException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to deactivate admin: '.$e->getMessage(),
            ], 500);
        }
    }
}
