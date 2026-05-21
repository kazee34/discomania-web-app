<?php

namespace Src\customer\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\customer\order\application\useCases\FindOrderUseCase;
use Src\customer\order\domain\exceptions\OrderNotFoundException;

final class GET_FindOrderController extends Controller
{
    public function __construct(
        private FindOrderUseCase $findOrderUseCase,
    ) {}

    public function show(string $orderNumber): JsonResponse
    {
        try {
            $result = $this->findOrderUseCase->execute($orderNumber);

            return response()->json([
                'success' => true,
                'data'    => $result,
            ]);
        } catch (OrderNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Internal Server Error',
            ], 500);
        }
    }
}