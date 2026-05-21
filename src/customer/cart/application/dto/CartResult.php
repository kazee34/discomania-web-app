<?php

namespace Src\customer\cart\application\dto;

use Src\customer\cart\domain\entities\Cart;

class CartResult
{
    public function __construct(
        public readonly int $id,
        public readonly ?int $customerId,
        public readonly ?string $sessionId,
        public readonly string $cartToken,
        public readonly ?string $expiresAt,
        public readonly array $items,
        public readonly float $totalAmount,
    ) {}

    public static function fromCart(Cart $cart): self
    {
        return new self(
            id: $cart->id(),
            customerId: $cart->customerId(),
            sessionId: $cart->sessionId(),
            cartToken: $cart->cartToken(),
            expiresAt: $cart->expiresAt()?->format(\DateTimeInterface::ATOM),
            items: array_map(
                fn ($item) => CartItemResult::fromCartItem($item),
                $cart->items()
            ),
            totalAmount: $cart->totalAmount(),
        );
    }
}