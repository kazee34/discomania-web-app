<?php

namespace Src\customer\cart\application\useCases;

use Src\customer\cart\application\dto\CartResult;
use Src\customer\cart\domain\repositories\CartRepositoryInterface;

class ClearCartUseCase
{
    public function __construct(
        private CartRepositoryInterface $repository,
    ) {}

    public function execute(string $cartToken): CartResult
    {
        $cart = $this->repository->findByToken($cartToken);

        $this->repository->clearItems($cart->id());

        return CartResult::fromCart($this->repository->findByToken($cartToken));
    }
}
