<?php

namespace Src\admin\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\admin\product\application\useCases\ActivateProductUseCase;
use Src\admin\product\domain\exceptions\ProductNotFoundException;

final class PUT_ActivateProductController extends Controller
{
    public function __construct(
        private ActivateProductUseCase $activateProductUseCase
    ) {}

    public function update(int $id): JsonResponse
    {
        try {
            $this->activateProductUseCase->execute($id);

            return response()->json([
                'success' => true,
                'data' => $id,
                'message' => 'Product activated successfully',
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