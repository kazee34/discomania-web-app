<?php

namespace Src\admin\product\application\useCases;

use Src\admin\product\application\dto\FindProductResult;
use Src\admin\product\domain\repositories\ProductRepositoryInterface;

class FindProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function execute(int $id): FindProductResult
    {
        $product = $this->repository->findById($id);

        return FindProductResult::fromProduct($product);
    }
}
