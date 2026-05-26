<?php

namespace Src\admin\product\application\useCases;

use Src\admin\product\domain\repositories\ProductRepositoryInterface;

class DeactivateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function execute(int $id): void
    {
        $product = $this->repository->findById($id);

        $product->deactivate();
        $this->repository->save($product);
    }
}
