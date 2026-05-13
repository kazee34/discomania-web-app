<?php

namespace Src\admin\product\application\useCases;

use Src\admin\product\domain\exceptions\ProductNotFoundException;
use Src\admin\product\domain\repositories\ProductRepositoryInterface;

class DeleteProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function execute(int $id): void
    {
        $product = $this->repository->findById($id);

        if (!$product) {
            throw new ProductNotFoundException($id);
        }

        $product->delete();
        $this->repository->delete($id);

        // Dispatch events
    }
}