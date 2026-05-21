<?php

namespace Src\customer\order\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\customer\cart\domain\exceptions\CartNotFoundException;
use Src\customer\order\application\useCases\CreateOrderFromCartUseCase;
use Src\customer\order\infrastructure\validators\CreateOrderRequest;

final class POST_CreateOrderController extends Controller
{
    public function __construct(
        private CreateOrderFromCartUseCase $createOrderFromCartUseCase,
    ) {}

    public function store(CreateOrderRequest $request): JsonResponse
    {
        try {
            $result = $this->createOrderFromCartUseCase->execute(
                cartToken: $request->input('cart_token'),
                customerId: (int) $request->input('customer_id'),
                customerNotes: $request->input('customer_notes'),
            );

            return response()->json([
                'success' => true,
                'data'    => $result,
            ], 201);
        } catch (CartNotFoundException $e) {
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