<?php

namespace Src\customer\cart\application\useCases;

use Src\customer\cart\application\dto\CartResult;
use Src\customer\cart\domain\repositories\CartRepositoryInterface;

class RemoveItemFromCartUseCase
{
    public function __construct(
        private CartRepositoryInterface $repository,
    ) {}

    public function execute(string $cartToken, int $cartItemId): CartResult
    {
        $cart = $this->repository->findByToken($cartToken);

        $cart->removeItem($cartItemId);

        $this->repository->removeItem($cartItemId);

        return CartResult::fromCart($this->repository->findByToken($cartToken));
    }
}
