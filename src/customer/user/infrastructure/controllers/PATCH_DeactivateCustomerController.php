<?php

namespace Src\customer\user\infrastructure\controllers;

use Illuminate\Http\JsonResponse;
use Src\customer\user\application\useCases\DeactivateCustomerUseCase;
use Src\customer\user\infrastructure\repositories\EloquentCustomerRepository;

class PATCH_DeactivateCustomerController
{
    public function __invoke(int $id): JsonResponse
    {
        $repository = new EloquentCustomerRepository;
        $useCase = new DeactivateCustomerUseCase($repository);

        try {
            $useCase->execute($id);

            return response()->json([
                'success' => true,
                'message' => 'Customer deactivated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }
}
