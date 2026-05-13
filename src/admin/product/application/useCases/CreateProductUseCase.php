<?php

namespace Src\admin\product\application\useCases;

use Src\admin\product\application\dto\CreateProductResult;
use Src\admin\product\domain\entities\Product;
use Src\admin\product\domain\repositories\ProductRepositoryInterface;
use Src\admin\product\application\dto\ProductRequest;

class CreateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function execute(ProductRequest $request): CreateProductResult
    {
        $product = Product::create(
            artist: $request->artist,
            albumTitle: $request->albumTitle,
            price: $request->price,
            stock: $request->stock,
            slug: $request->slug,
            genre: $request->genre,
            releaseYear: $request->releaseYear,
            country: $request->country,
            label: $request->label,
            description: $request->description,
            coverImageUrl: $request->coverImageUrl
        );

        $this->repository->save($product);

        return CreateProductResult::fromProduct($product);
    }
}