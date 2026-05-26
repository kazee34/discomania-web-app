<?php

namespace Src\customer\cart\domain\entities;

class CartItem
{
    public function __construct(
        private readonly ?int $id,
        private readonly ?int $cartId,
        private readonly int $productId,
        private readonly int $quantity,
        private readonly float $price,
        private readonly ?string $productArtist = null,
        private readonly ?string $productAlbumTitle = null,
        private readonly ?string $productCoverImageUrl = null,
    ) {}

    public static function create(int $productId, float $price, int $quantity): self
    {
        return new self(
            id: null,
            cartId: null,
            productId: $productId,
            quantity: $quantity,
            price: $price,
        );
    }

    public static function fromPrimitives(
        int $id,
        int $cartId,
        int $productId,
        int $quantity,
        float $price,
        ?string $productArtist = null,
        ?string $productAlbumTitle = null,
        ?string $productCoverImageUrl = null,
    ): self {
        return new self(
            id: $id,
            cartId: $cartId,
            productId: $productId,
            quantity: $quantity,
            price: $price,
            productArtist: $productArtist,
            productAlbumTitle: $productAlbumTitle,
            productCoverImageUrl: $productCoverImageUrl,
        );
    }

    public function withQuantity(int $quantity): self
    {
        return new self(
            $this->id, $this->cartId, $this->productId, $quantity, $this->price,
            $this->productArtist, $this->productAlbumTitle, $this->productCoverImageUrl,
        );
    }

    public function subtotal(): float
    {
        return round($this->price * $this->quantity, 2);
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function cartId(): ?int
    {
        return $this->cartId;
    }

    public function productId(): int
    {
        return $this->productId;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function productArtist(): ?string
    {
        return $this->productArtist;
    }

    public function productAlbumTitle(): ?string
    {
        return $this->productAlbumTitle;
    }

    public function productCoverImageUrl(): ?string
    {
        return $this->productCoverImageUrl;
    }
}
