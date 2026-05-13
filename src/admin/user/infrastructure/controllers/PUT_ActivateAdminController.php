<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\admin\user\application\useCases\ActivateAdminUseCase;
use Src\admin\user\domain\exceptions\AdminNotFoundException;
use Src\admin\user\domain\exceptions\CannotDeleteSuperAdminException;

final class PUT_ActivateAdminController extends Controller
{
    public function __construct(
        private ActivateAdminUseCase $activateAdminUseCase
    ) {}

    public function update(int $id): JsonResponse
    {
        try {
            $this->activateAdminUseCase->execute($id);

            return response()->json([
                'status' => true,
                'data' => $id,
                'message' => 'Admin activated successfully',
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
                'error' => 'Failed to activate admin: '.$e->getMessage(),
            ], 500);
        }
    }
}
