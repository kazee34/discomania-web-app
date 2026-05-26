<?php

namespace Src\customer\cart\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\customer\cart\application\useCases\UpdateCartItemQuantityUseCase;
use Src\customer\cart\domain\exceptions\CartItemNotFoundException;
use Src\customer\cart\domain\exceptions\CartNotFoundException;
use Src\customer\cart\infrastructure\validators\UpdateCartItemQuantityRequest;

final class PUT_UpdateCartItemQuantityController extends Controller
{
    public function __construct(
        private UpdateCartItemQuantityUseCase $updateCartItemQuantityUseCase,
    ) {}

    public function update(UpdateCartItemQuantityRequest $request, string $token, int $itemId): JsonResponse
    {
        try {
            $result = $this->updateCartItemQuantityUseCase->execute(
                cartToken: $token,
                cartItemId: $itemId,
                quantity: (int) $request->input('quantity'),
            );

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (CartNotFoundException|CartItemNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Internal Server Error',
            ], 500);
        }
    }
}
