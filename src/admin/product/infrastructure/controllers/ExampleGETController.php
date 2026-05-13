<?php

namespace Src\admin\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Src\admin\product\application\useCases\SearchAllProductsUseCase;
use Src\admin\product\infrastructure\repositories\EloquentProductRepository;
use Illuminate\Http\JsonResponse;

class GET_ProductsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $repository = new EloquentProductRepository();
        $useCase = new SearchAllProductsUseCase($repository);
        $products = $useCase->execute();

        return response()->json($products);
    }
}
