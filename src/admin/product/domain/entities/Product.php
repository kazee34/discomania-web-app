<?php

namespace Src\admin\product\domain\entities;

use Src\admin\product\domain\events\ProductCreatedEvent;
use Src\admin\product\domain\events\ProductDeletedEvent;
use Src\admin\product\domain\events\ProductUpdatedEvent;
use Src\admin\product\domain\valueObjects\ProductPrice;
use Src\admin\product\domain\valueObjects\ProductStock;

class Product
{
    private array $domainEvents = [];

    public function __construct(
        private readonly ?int $id,
        private string $artist,
        private string $albumTitle,
        private ?string $slug,
        private ?string $genre,
        private ?int $releaseYear,
        private ?string $country,
        private ?string $label,
        private ProductPrice $price,
        private ProductStock $stock,
        private ?string $description,
        private ?string $coverImageUrl,
        private bool $isActive
    ) {}

    public function id(): ?int
    {
        return $this->id;
    }

    public function price(): ProductPrice
    {
        return $this->price;
    }

    public function stock(): ProductStock
    {
        return $this->stock;
    }

    public function artist(): string
    {
        return $this->artist;
    }

    public function albumTitle(): string
    {
        return $this->albumTitle;
    }

    public function slug(): ?string
    {
        return $this->slug;
    }

    public function genre(): ?string
    {
        return $this->genre;
    }

    public function releaseYear(): ?int
    {
        return $this->releaseYear;
    }

    public function country(): ?string
    {
        return $this->country;
    }

    public function label(): ?string
    {
        return $this->label;
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function coverImageUrl(): ?string
    {
        return $this->coverImageUrl;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public static function fromPrimitives(
        int $id,
        string $artist,
        string $albumTitle,
        float $price,
        int $stock,
        ?string $slug,
        ?string $genre,
        ?int $releaseYear,
        ?string $country,
        ?string $label,
        ?string $description,
        ?string $coverImageUrl,
        bool $isActive = true,
    ): self {
        return new self(
            id: $id,
            artist: $artist,
            albumTitle: $albumTitle,
            slug: $slug,
            genre: $genre,
            releaseYear: $releaseYear,
            country: $country,
            label: $label,
            price: new ProductPrice($price),
            stock: new ProductStock($stock),
            description: $description,
            coverImageUrl: $coverImageUrl,
            isActive: $isActive,
        );
    }

    public static function create(
        string $artist,
        string $albumTitle,
        float $price,
        int $stock,
        ?string $slug,
        string $genre,
        int $releaseYear,
        string $country,
        string $label,
        string $description,
        string $coverImageUrl,
    ): self {
        $product = new self(
            id: null,
            artist: $artist,
            albumTitle: $albumTitle,
            slug: $slug,
            genre: $genre,
            releaseYear: $releaseYear,
            country: $country,
            label: $label,
            price: new ProductPrice($price),
            stock: new ProductStock($stock),
            description: $description,
            coverImageUrl: $coverImageUrl,
            isActive: true
        );

        $product->recordEvent(
            new ProductCreatedEvent(
                $product->artist(),
                $product->albumTitle(),
                $product->price()->value(),
                $product->stock()->value(),
                $product->genre(),
                $product->releaseYear(),
                $product->country()
            )
        );

        return $product;
    }

    public function update(
        ?string $artist = null,
        ?string $albumTitle = null,
        ?float $price = null,
        ?int $stock = null,
        ?string $slug = null,
        ?string $genre = null,
        ?int $releaseYear = null,
        ?string $country = null,
        ?string $label = null,
        ?string $description = null,
        ?string $coverImageUrl = null,
    ): void {
        $changed = [];

        if ($artist !== null) {
            $this->artist = $artist;
            $changed['artist'] = $artist;
        }
        if ($albumTitle !== null) {
            $this->albumTitle = $albumTitle;
            $changed['album_title'] = $albumTitle;
        }
        if ($price !== null) {
            $this->price = new ProductPrice($price);
            $changed['price'] = $price;
        }
        if ($stock !== null) {
            $this->stock = new ProductStock($stock);
            $changed['stock_quantity'] = $stock;
        }
        if ($slug !== null) {
            $this->slug = $slug;
            $changed['slug'] = $slug;
        }
        if ($genre !== null) {
            $this->genre = $genre;
            $changed['genre'] = $genre;
        }
        if ($releaseYear !== null) {
            $this->releaseYear = $releaseYear;
            $changed['release_year'] = $releaseYear;
        }
        if ($country !== null) {
            $this->country = $country;
            $changed['country'] = $country;
        }
        if ($label !== null) {
            $this->label = $label;
            $changed['label'] = $label;
        }
        if ($description !== null) {
            $this->description = $description;
            $changed['description'] = $description;
        }
        if ($coverImageUrl !== null) {
            $this->coverImageUrl = $coverImageUrl;
            $changed['cover_image_url'] = $coverImageUrl;
        }

        if (! empty($changed)) {
            $this->recordEvent(new ProductUpdatedEvent($this->id(), $changed));
        }
    }

    public function activate(): void
    {
        $this->isActive = true;
        $this->recordEvent(new ProductUpdatedEvent($this->id(), ['is_active' => true]));
    }

    public function deactivate(): void
    {
        $this->isActive = false;
        $this->recordEvent(new ProductUpdatedEvent($this->id(), ['is_active' => false]));
    }

    public function delete(): void
    {
        $this->recordEvent(new ProductDeletedEvent($this->id()));
    }

    public function recordEvent(object $event): void
    {
        $this->domainEvents[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }
}
