<?php

namespace Src\admin\product\domain\entities;

use Src\admin\product\domain\valueObjects\ProductPrice;
use Src\admin\product\domain\valueObjects\ProductStock;
use Src\admin\product\domain\events\ProductCreatedEvent;
use Src\admin\product\domain\events\ProductUpdatedEvent;
use Src\admin\product\domain\events\ProductDeletedEvent;

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
        return $this->id?->value();
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

    public function slug(): string
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
        ?string $slug = null,
        ?string $genre = null,
        ?int $releaseYear = null,
        ?string $country = null,
        ?string $label = null,
        ?string $description = null,
        ?string $coverImageUrl = null
    ): Product {
        return new Product(
            id: $id,
            artist: $artist,
            albumTitle: $albumTitle,
            slug: $slug ?? \Illuminate\Support\Str::slug($artist . ' ' . $albumTitle),
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
    }

    public static function create(
        string $artist,
        string $albumTitle,
        float $price,
        int $stock,
        ?string $slug = null,
        ?string $genre = null,
        ?int $releaseYear = null,
        ?string $country = null,
        ?string $label = null,
        ?string $description = null,
        ?string $coverImageUrl = null
    ): self {
        $product = new Product(
            id: null,
            artist: $artist,
            albumTitle: $albumTitle,
            slug: $slug ?? \Illuminate\Support\Str::slug($artist . ' ' . $albumTitle),
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

    public function updatePrice(float $newPrice): void
    {
        $this->price = new ProductPrice($newPrice);
        $this->recordEvent(new ProductUpdatedEvent($this->id(), ['price' => $newPrice]));
    }

    public function updateStock(int $newStock): void
    {
        $this->recordEvent(new ProductUpdatedEvent($this->id(), ['stock' => $newStock]));
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
