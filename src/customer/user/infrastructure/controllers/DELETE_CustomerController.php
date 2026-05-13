<?php

namespace Src\customer\user\infrastructure\controllers;

use Illuminate\Http\JsonResponse;
use Src\customer\user\application\useCases\DeleteCustomerUseCase;
use Src\customer\user\domain\exceptions\CustomerNotFoundException;
use Src\customer\user\infrastructure\repositories\EloquentCustomerRepository;

class DELETE_CustomerController
{
    public function __invoke(int $id): JsonResponse
    {
        $repository = new EloquentCustomerRepository;
        $useCase = new DeleteCustomerUseCase($repository);

        try {
            $useCase->execute($id);

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully',
            ]);
        } catch (CustomerNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }
}
