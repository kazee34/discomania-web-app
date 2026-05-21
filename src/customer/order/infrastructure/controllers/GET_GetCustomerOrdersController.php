<?php

namespace Src\customer\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\customer\order\application\useCases\GetCustomerOrdersUseCase;

final class GET_GetCustomerOrdersController extends Controller
{
    public function __construct(
        private GetCustomerOrdersUseCase $getCustomerOrdersUseCase,
    ) {}

    public function index(int $customerId): JsonResponse
    {
        try {
            $results = $this->getCustomerOrdersUseCase->execute($customerId);

            return response()->json([
                'success' => true,
                'data'    => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Internal Server Error',
            ], 500);
        }
    }
}