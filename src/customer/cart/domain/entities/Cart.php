<?php

namespace Src\customer\cart\domain\entities;

use Ramsey\Uuid\Uuid;
use Src\customer\cart\domain\events\CartClearedEvent;
use Src\customer\cart\domain\events\CartCreatedEvent;
use Src\customer\cart\domain\events\CartItemAddedEvent;
use Src\customer\cart\domain\events\CartItemQuantityUpdatedEvent;
use Src\customer\cart\domain\events\CartItemRemovedEvent;
use Src\customer\cart\domain\exceptions\CartItemNotFoundException;

class Cart
{
    private array $domainEvents = [];
    private array $items;

    public function __construct(
        private readonly ?int $id,
        private readonly ?int $customerId,
        private readonly ?string $sessionId,
        private readonly string $cartToken,
        private readonly ?\DateTimeImmutable $expiresAt,
        array $items = []
    ) {
        $this->items = $items;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function customerId(): ?int
    {
        return $this->customerId;
    }

    public function sessionId(): ?string
    {
        return $this->sessionId;
    }

    public function cartToken(): string
    {
        return $this->cartToken;
    }

    public function expiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }
    
    public function items(): array
    {
        return $this->items;
    }

    public static function create(
        ?int $customerId,
        ?string $sessionId,
        ?\DateTimeImmutable $expiresAt = null,
    ): self {
        $cart = new self(
            id: null,
            customerId: $customerId,
            sessionId: $sessionId,
            cartToken: self::generateToken(),
            expiresAt: $expiresAt,
            items: []
        );

        $cart->recordEvent(new CartCreatedEvent($customerId, $sessionId));

        return $cart;
    }

    public static function fromPrimitives(
        int $id,
        ?int $customerId,
        ?string $sessionId,
        string $cartToken,
        ?\DateTimeImmutable $expiresAt,
        array $items
    ): self {
        return new self(
            id: $id,
            customerId: $customerId,
            sessionId: $sessionId,
            cartToken: $cartToken,
            expiresAt: $expiresAt,
            items: $items,
        );
    }

    public function addItem(int $productId, float $price, int $quantity): void
    {
        foreach ($this->items as $key => $item) {
            if ($item->productId() === $productId) {
                $newQuantity = $item->quantity() + $quantity;
                $this->items[$key] = $item->withQuantity($newQuantity);
                $this->recordEvent(new CartItemQuantityUpdatedEvent($this->id, $productId, $newQuantity));
                return;
            }
        }

        $this->items[] = CartItem::create($productId, $price, $quantity);
        $this->recordEvent(new CartItemAddedEvent($this->id, $productId, $price, $quantity));
    }

    public function removeItem(int $cartItemId): void
    {
        foreach ($this->items as $key => $item) {
            if ($item->id() === $cartItemId) {
                unset($this->items[$key]);
                $this->items = array_values($this->items);
                $this->recordEvent(new CartItemRemovedEvent($this->id, $cartItemId));
                return;
            }
        }

        throw CartItemNotFoundException::byId($cartItemId);
    }

    public function updateItemQuantity(int $cartItemId, int $quantity): void
    {
        foreach ($this->items as $key => $item) {
            if ($item->id() === $cartItemId) {
                $this->items[$key] = $item->withQuantity($quantity);
                $this->recordEvent(new CartItemQuantityUpdatedEvent($this->id, $cartItemId, $quantity));
                return;
            }
        }

        throw CartItemNotFoundException::byId($cartItemId);
    }

    public function clear(): void
    {
        $this->items = [];
        $this->recordEvent(new CartClearedEvent($this->id));
    }

    public function findItemByProductId(int $productId): ?CartItem
    {
        foreach ($this->items as $item) {
            if ($item->productId() === $productId) {
                return $item;
            }
        }
        return null;
    }

    public function isExpired(): bool
    {
        return $this->expiresAt !== null && $this->expiresAt < new \DateTimeImmutable;
    }

    public function totalAmount(): float
    {
        return round(array_sum(array_map(fn(CartItem $i) => $i->subtotal(), $this->items)), 2);
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

    private static function generateToken(): string
    {
        return Uuid::uuid4()->toString();
    }
}
