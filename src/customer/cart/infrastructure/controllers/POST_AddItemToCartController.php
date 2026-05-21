<?php

namespace Src\customer\cart\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\customer\cart\application\useCases\AddItemToCartUseCase;
use Src\customer\cart\domain\exceptions\CartNotFoundException;
use Src\customer\cart\infrastructure\validators\AddItemToCartRequest;

final class POST_AddItemToCartController extends Controller
{
    public function __construct(
        private AddItemToCartUseCase $addItemToCartUseCase,
    ) {}

    public function store(AddItemToCartRequest $request, string $token): JsonResponse
    {
        try {
            $result = $this->addItemToCartUseCase->execute(
                cartToken: $token,
                productId: (int) $request->input('product_id'),
                price: (float) $request->input('price'),
                quantity: (int) $request->input('quantity'),
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
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 400);
        }
    }
}
