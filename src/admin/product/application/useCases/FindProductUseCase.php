<?php

namespace Src\admin\product\application\useCases;

use Src\admin\product\application\dto\FindProductResult;
use Src\admin\product\domain\repositories\ProductRepositoryInterface;
use Src\admin\product\domain\exceptions\ProductNotFoundException;

class FindProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function execute(int $id): ?FindProductResult
    {
        $product = $this->repository->findById($id);

        if (!$product) {
            throw new ProductNotFoundException($id);
        }

        return FindProductResult::fromProduct($product);
    }
}