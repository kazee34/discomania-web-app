<?php

namespace Src\admin\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Src\admin\product\application\useCases\SearchAllProductsUseCase;

final class GET_AdminProductsWebController extends Controller
{
    public function __construct(
        private SearchAllProductsUseCase $searchAllProductsUseCase,
    ) {}

    public function index(): Response
    {
        $products = $this->searchAllProductsUseCase->execute();

        return Inertia::render('admin/products/Index', [
            'products' => array_map(fn ($p) => [
                'id'           => $p->id,
                'artist'       => $p->artist,
                'albumTitle'   => $p->albumTitle,
                'price'        => $p->price,
                'stock'        => $p->stock,
                'slug'         => $p->slug,
                'genre'        => $p->genre,
                'coverImageUrl'=> $p->coverImageUrl,
                'isActive'     => $p->isActive,
            ], $products),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/products/Form');
    }
}