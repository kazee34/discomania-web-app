<?php

namespace Src\customer\cart\application\dto;

use Src\customer\cart\domain\entities\CartItem;

class CartItemResult
{
    public function __construct(
        public readonly int $id,
        public readonly int $productId,
        public readonly int $quantity,
        public readonly float $price,
        public readonly float $subtotal,
        public readonly ?string $productArtist,
        public readonly ?string $productAlbumTitle,
        public readonly ?string $productCoverImageUrl,
    ) {}

    public static function fromCartItem(CartItem $item): self
    {
        return new self(
            id: $item->id(),
            productId: $item->productId(),
            quantity: $item->quantity(),
            price: $item->price(),
            subtotal: $item->subtotal(),
            productArtist: $item->productArtist(),
            productAlbumTitle: $item->productAlbumTitle(),
            productCoverImageUrl: $item->productCoverImageUrl(),
        );
    }
}
