<?php

namespace Src\customer\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Src\customer\product\domain\repositories\ProductRepositoryInterface;
use Src\shared\domain\exceptions\ProductNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class GET_ShopProductController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function show(string $slug): Response
    {
        try {
            $p = $this->productRepository->findBySlug($slug);
        } catch (ProductNotFoundException) {
            throw new NotFoundHttpException;
        }

        return Inertia::render('shop/Show', [
            'product' => [
                'id' => $p->id(),
                'slug' => $p->slug(),
                'artist' => $p->artist(),
                'albumTitle' => $p->albumTitle(),
                'price' => $p->price(),
                'genre' => $p->genre(),
                'country' => $p->country(),
                'releaseYear' => $p->releaseYear(),
                'coverImageUrl' => $p->coverImageUrl(),
                'description' => $p->description(),
                'label' => $p->label(),
                'stockQuantity' => $p->stockQuantity(),
            ],
        ]);
    }
}
