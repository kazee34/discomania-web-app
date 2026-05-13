<?php

namespace Src\admin\product\application\useCases;

use Src\admin\product\application\dto\ProductRequest;
use Src\admin\product\application\dto\UpdateProductResult;
use Src\admin\product\domain\repositories\ProductRepositoryInterface;

class UpdateProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    public function execute(ProductRequest $request): ?UpdateProductResult
    {
        $product = $this->repository->findById($request->id);

        $this->repository->save($product);

        return UpdateProductResult::fromProduct($product);
    }
}