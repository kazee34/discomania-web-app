<?php

namespace Src\admin\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\admin\product\application\useCases\SearchAllProductsUseCase;

final class GET_SearchAllProductsController extends Controller
{
    public function __construct(
        private SearchAllProductsUseCase $searchAllProductsUseCase
    ) {}

    public function index(): JsonResponse
    {
        try {
            $results = $this->searchAllProductsUseCase->execute();

            return response()->json([
                'success' => true,
                'data' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Internal Server Error',
            ], 500);
        }
    }
}