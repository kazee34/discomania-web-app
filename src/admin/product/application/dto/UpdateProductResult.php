<?php

namespace Src\admin\product\application\dto;

use Src\admin\product\domain\entities\Product;

class UpdateProductResult
{
    public function __construct(
        public readonly int $id,
        public readonly string $artist,
        public readonly string $albumTitle,
        public readonly float $price,
        public readonly int $stock,
        public readonly string $slug,
        public readonly ?string $genre,
        public readonly ?int $releaseYear,
        public readonly ?string $country,
        public readonly ?string $label,
        public readonly ?string $description,
        public readonly ?string $coverImageUrl,
        public readonly bool $isActive
    ) {}

    public static function fromProduct(Product $product): self
    {
        return new self(
            id: $product->id(),
            artist: $product->artist(),
            albumTitle: $product->albumTitle(),
            price: $product->price()->value(),
            stock: $product->stock()->value(),
            slug: $product->slug(),
            genre: $product->genre(),
            releaseYear: $product->releaseYear(),
            country: $product->country(),
            label: $product->label(),
            description: $product->description(),
            coverImageUrl: $product->coverImageUrl(), 
            isActive: $product->isActive()
        );
    }
}