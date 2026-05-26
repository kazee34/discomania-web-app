<?php

namespace Src\customer\cart\application\useCases;

use Src\customer\cart\application\dto\CartResult;
use Src\customer\cart\domain\entities\Cart;
use Src\customer\cart\domain\repositories\CartRepositoryInterface;

class CreateCartUseCase
{
    public function __construct(
        private CartRepositoryInterface $repository,
    ) {}

    public function execute(
        ?int $customerId,
        ?string $sessionId,
        ?\DateTimeImmutable $expiresAt = null,
    ): CartResult {
        $cart = Cart::create($customerId, $sessionId, $expiresAt);

        $this->repository->save($cart);

        return CartResult::fromCart($this->repository->findByToken($cart->cartToken()));
    }
}
