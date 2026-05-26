<?php

namespace Src\customer\cart\application\useCases;

use Src\customer\cart\application\dto\CartResult;
use Src\customer\cart\domain\entities\CartItem;
use Src\customer\cart\domain\repositories\CartRepositoryInterface;
use Src\customer\product\domain\repositories\ProductRepositoryInterface;

class AddItemToCartUseCase
{
    public function __construct(
        private CartRepositoryInterface $repository,
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(string $cartToken, int $productId, float $price, int $quantity): CartResult
    {
        $product = $this->productRepository->findById($productId);

        $cart = $this->repository->findByToken($cartToken);

        $existingItem = $cart->findItemByProductId($productId);
        $currentQty = $existingItem?->quantity() ?? 0;
        $newTotal = $currentQty + $quantity;

        if ($newTotal > $product->stockQuantity()) {
            throw new \DomainException(
                "No hay suficiente stock para '{$product->artist()} — {$product->albumTitle()}'. ".
                "Stock disponible: {$product->stockQuantity()}, en tu carrito: {$currentQty}."
            );
        }

        if ($existingItem !== null) {
            $this->repository->updateItemQuantity($existingItem->id(), $newTotal);
        } else {
            $this->repository->addItem(
                $cart->id(),
                CartItem::create($productId, $price, $quantity)
            );
        }

        return CartResult::fromCart($this->repository->findByToken($cartToken));
    }
}
