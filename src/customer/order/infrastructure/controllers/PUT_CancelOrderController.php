<?php

namespace Src\customer\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\customer\order\application\useCases\CancelOrderUseCase;
use Src\customer\order\domain\exceptions\OrderNotFoundException;

final class PUT_CancelOrderController extends Controller
{
    public function __construct(
        private CancelOrderUseCase $cancelOrderUseCase,
    ) {}

    public function update(string $orderNumber): JsonResponse
    {
        try {
            $result = $this->cancelOrderUseCase->execute($orderNumber);

            return response()->json([
                'success' => true,
                'data'    => $result,
            ]);
        } catch (OrderNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 404);
        } catch (\DomainException $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'Internal Server Error',
            ], 500);
        }
    }
}