<?php

namespace Src\admin\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\admin\product\application\useCases\DeleteProductUseCase;
use Src\shared\domain\exceptions\ProductNotFoundException;

final class DELETE_DeleteProductByIdController extends Controller
{
    public function __construct(
        private DeleteProductUseCase $deleteProductUseCase
    ) {}

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->deleteProductUseCase->execute($id);

            return response()->json([
                'success' => true,
                'data' => $id,
                'message' => 'Product deleted successfully',
            ]);
        } catch (ProductNotFoundException $e) {
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