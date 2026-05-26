<?php

namespace Src\admin\product\application\useCases;

use Src\admin\product\domain\repositories\ProductRepositoryInterface;

class ActivateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function execute(int $id): void
    {
        $product = $this->repository->findById($id);

        $product->activate();
        $this->repository->save($product);
    }
}
