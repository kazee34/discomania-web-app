<?php

namespace Src\admin\product\application\dto;

class ProductRequest
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly ?string $artist = null,
        public readonly ?string $albumTitle = null,
        public readonly ?float $price = null,
        public readonly ?int $stock = null,
        public readonly ?string $slug = null,
        public readonly ?string $genre = null,
        public readonly ?int $releaseYear = null,
        public readonly ?string $country = null,
        public readonly ?string $label = null,
        public readonly ?string $description = null,
        public readonly ?string $coverImageUrl = null,
        public readonly ?bool $isActive = null
    ) {}
}