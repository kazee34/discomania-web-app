<?php

namespace Src\customer\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Src\customer\product\domain\entities\Product;
use Src\customer\product\domain\repositories\ProductRepositoryInterface;

final class GET_ShopIndexController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function index(): Response
    {
        $products = $this->productRepository->searchAll();

        return Inertia::render('shop/Index', [
            'products' => array_map(fn (Product $p) => [
                'id'            => $p->id(),
                'slug'          => $p->slug(),
                'artist'        => $p->artist(),
                'albumTitle'    => $p->albumTitle(),
                'price'         => $p->price(),
                'genre'         => $p->genre(),
                'country'       => $p->country(),
                'releaseYear'   => $p->releaseYear(),
                'coverImageUrl' => $p->coverImageUrl(),
                'stockQuantity' => $p->stockQuantity(),
            ], $products),
        ]);
    }
}
