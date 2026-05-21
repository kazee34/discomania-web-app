<?php

namespace Src\customer\cart\domain\repositories;

use Src\customer\cart\domain\entities\Cart;
use Src\customer\cart\domain\entities\CartItem;

interface CartRepositoryInterface
{
    public function save(Cart $cart): int;

    public function findById(int $id): Cart;

    public function findByToken(string $token): Cart;

    public function findByCustomerId(int $customerId): ?Cart;

    public function findBySessionId(string $sessionId): ?Cart;

    public function addItem(int $cartId, CartItem $item): CartItem;

    public function updateItemQuantity(int $cartItemId, int $quantity): void;

    public function removeItem(int $cartItemId): void;

    public function clearItems(int $cartId): void;
}