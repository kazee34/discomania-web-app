<?php

namespace Src\customer\cart\application\useCases;

use Src\customer\cart\application\dto\CartResult;
use Src\customer\cart\domain\exceptions\CartItemNotFoundException;
use Src\customer\cart\domain\repositories\CartRepositoryInterface;
use Src\customer\product\domain\repositories\ProductRepositoryInterface;

class UpdateCartItemQuantityUseCase
{
    public function __construct(
        private CartRepositoryInterface $repository,
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function execute(string $cartToken, int $cartItemId, int $quantity): CartResult
    {
        $cart = $this->repository->findByToken($cartToken);

        $item = null;
        foreach ($cart->items() as $i) {
            if ($i->id() === $cartItemId) {
                $item = $i;
                break;
            }
        }

        if ($item === null) {
            throw CartItemNotFoundException::byId($cartItemId);
        }

        $product = $this->productRepository->findById($item->productId());

        if ($quantity > $product->stockQuantity()) {
            throw new \DomainException(
                "Stock insuficiente para '{$product->artist()} — {$product->albumTitle()}'. " .
                "Máximo disponible: {$product->stockQuantity()}."
            );
        }

        $cart->updateItemQuantity($cartItemId, $quantity);
        $this->repository->updateItemQuantity($cartItemId, $quantity);

        return CartResult::fromCart($this->repository->findByToken($cartToken));
    }
}
