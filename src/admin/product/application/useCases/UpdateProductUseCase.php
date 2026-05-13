<?php

namespace Src\admin\product\application\useCases;

use Src\admin\product\application\dto\ProductRequest;
use Src\admin\product\application\dto\UpdateProductResult;
use Src\admin\product\domain\repositories\ProductRepositoryInterface;

class UpdateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function execute(ProductRequest $request): UpdateProductResult
    {
        $product = $this->repository->findById($request->id);

        $product->update(
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
            coverImageUrl: $request->coverImageUrl,
        );

        $this->repository->save($product);

        return UpdateProductResult::fromProduct($product);
    }
}