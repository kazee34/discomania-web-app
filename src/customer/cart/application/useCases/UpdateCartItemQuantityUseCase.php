<?php

namespace Src\customer\cart\application\useCases;

use Src\customer\cart\application\dto\CartResult;
use Src\customer\cart\domain\repositories\CartRepositoryInterface;

class UpdateCartItemQuantityUseCase
{
    public function __construct(
        private CartRepositoryInterface $repository,
    ) {}

    public function execute(string $cartToken, int $cartItemId, int $quantity): CartResult
    {
        $cart = $this->repository->findByToken($cartToken);

        $cart->updateItemQuantity($cartItemId, $quantity);

        $this->repository->updateItemQuantity($cartItemId, $quantity);

        return CartResult::fromCart($this->repository->findByToken($cartToken));
    }
}
