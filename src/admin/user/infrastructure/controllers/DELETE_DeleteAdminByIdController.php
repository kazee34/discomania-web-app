<?php

namespace Src\admin\user\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\admin\user\application\useCases\DeleteAdminUseCase;
use Src\admin\user\domain\exceptions\AdminNotFoundException;
use Src\admin\user\domain\exceptions\CannotDeleteSelfException;
use Src\admin\user\domain\exceptions\CannotDeleteSuperAdminException;

final class DELETE_DeleteAdminByIdController extends Controller
{
    public function __construct(
        private DeleteAdminUseCase $deleteAdminUsecase
    ) {}

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->deleteAdminUsecase->execute($id);

            return response()->json([
                'status' => true,
                'data' => $id,
                'message' => 'success',
            ]);
        } catch (AdminNotFoundException $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 404);
        } catch (CannotDeleteSelfException|CannotDeleteSuperAdminException $e) {
            return response()->json([
                'status' => false,
                'error' => $e->getMessage(),
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'error' => 'Internal Server Error',
            ], 500);
        }
    }
}
