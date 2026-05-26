<?php

namespace Src\admin\product\domain\events;

class ProductCreatedEvent
{
    public function __construct(
        public readonly string $artist,
        public readonly string $albumTitle,
        public readonly float $price,
        public readonly int $stock,
        public readonly ?string $genre,
        public readonly ?int $releaseYear,
        public readonly ?string $country
    ) {}

}
