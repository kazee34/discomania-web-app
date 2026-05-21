<?php

namespace Src\customer\cart\application\useCases;

use Src\customer\cart\application\dto\CartResult;
use Src\customer\cart\domain\entities\CartItem;
use Src\customer\cart\domain\repositories\CartRepositoryInterface;

class AddItemToCartUseCase
{
    public function __construct(
        private CartRepositoryInterface $repository,
    ) {}

    public function execute(string $cartToken, int $productId, float $price, int $quantity): CartResult
    {
        $cart = $this->repository->findByToken($cartToken);

        $existingItem = $cart->findItemByProductId($productId);

        if ($existingItem !== null) {
            $this->repository->updateItemQuantity(
                $existingItem->id(),
                $existingItem->quantity() + $quantity
            );
        } else {
            $this->repository->addItem(
                $cart->id(),
                CartItem::create($productId, $price, $quantity)
            );
        }

        return CartResult::fromCart($this->repository->findByToken($cartToken));
    }
}