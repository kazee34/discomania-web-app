<?php

namespace Src\customer\product\domain\entities;

class Product
{
    public function __construct(
        private readonly ?int $id,
        private string $slug,
        private string $artist,
        private string $albumTitle,
        private float $price,
        private string $genre,
        private string $country,
        private int $releaseYear,
        private ?string $coverImageUrl,
        private ?string $description = null,
        private ?string $label = null,
        private int $stockQuantity = 0,
    ) {}

    public function id(): ?int
    {
        return $this->id;
    }

    public function slug(): string
    {
        return $this->slug;
    }

    public function artist(): string
    {
        return $this->artist;
    }

    public function albumTitle(): string
    {
        return $this->albumTitle;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function genre(): string
    {
        return $this->genre;
    }

    public function releaseYear(): int
    {
        return $this->releaseYear;
    }

    public function country(): string
    {
        return $this->country;
    }

    public function coverImageUrl(): ?string
    {
        return $this->coverImageUrl;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function label(): ?string
    {
        return $this->label;
    }

    public function stockQuantity(): int
    {
        return $this->stockQuantity;
    }

    public static function fromPrimitives(
        int $id,
        string $slug,
        string $artist,
        string $albumTitle,
        float $price,
        string $genre,
        int $releaseYear,
        string $country,
        ?string $coverImageUrl
    ): self {
        return new self(
            id: $id,
            slug: $slug,
            artist: $artist,
            albumTitle: $albumTitle,
            price: $price,
            genre: $genre,
            releaseYear: $releaseYear,
            country: $country,
            coverImageUrl: $coverImageUrl,
        );
    }
}
