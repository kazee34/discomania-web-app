<?php

namespace Src\admin\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\admin\product\application\useCases\FindProductUseCase;
use Src\shared\domain\exceptions\ProductNotFoundException;

final class GET_FindProductByIdController extends Controller
{
    public function __construct(
        private FindProductUseCase $findProductUseCase
    ) {}

    public function show(int $id): JsonResponse
    {
        try {
            $result = $this->findProductUseCase->execute($id);

            return response()->json([
                'success' => true,
                'data' => $result,
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