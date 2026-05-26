<?php

namespace Src\customer\cart\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\customer\cart\application\useCases\RemoveItemFromCartUseCase;
use Src\customer\cart\domain\exceptions\CartItemNotFoundException;
use Src\customer\cart\domain\exceptions\CartNotFoundException;

final class DELETE_RemoveItemFromCartController extends Controller
{
    public function __construct(
        private RemoveItemFromCartUseCase $removeItemFromCartUseCase,
    ) {}

    public function destroy(string $token, int $itemId): JsonResponse
    {
        try {
            $result = $this->removeItemFromCartUseCase->execute($token, $itemId);

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
