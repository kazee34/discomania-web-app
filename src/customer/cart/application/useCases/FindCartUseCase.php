<?php

namespace Src\customer\cart\application\useCases;

use Src\customer\cart\application\dto\CartResult;
use Src\customer\cart\domain\repositories\CartRepositoryInterface;

class FindCartUseCase
{
    public function __construct(
        private CartRepositoryInterface $repository,
    ) {}

    public function execute(string $cartToken): CartResult
    {
        $cart = $this->repository->findByToken($cartToken);

        return CartResult::fromCart($cart);
    }
}
