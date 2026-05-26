<?php

namespace Src\admin\product\application\useCases;

use Illuminate\Support\Str;
use Src\admin\product\application\dto\CreateProductResult;
use Src\admin\product\application\dto\ProductRequest;
use Src\admin\product\domain\entities\Product;
use Src\admin\product\domain\repositories\ProductRepositoryInterface;

class CreateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository,
    ) {}

    public function execute(ProductRequest $request): CreateProductResult
    {
        $slug = $request->slug ?: Str::slug("{$request->artist} {$request->albumTitle}");

        $product = Product::create(
            artist: $request->artist,
            albumTitle: $request->albumTitle,
            price: $request->price,
            stock: $request->stock,
            slug: $slug,
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
