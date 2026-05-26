<?php

namespace Src\admin\product\application\useCases;

use Src\admin\product\application\dto\FindProductResult;
use Src\admin\product\domain\repositories\ProductRepositoryInterface;

class SearchAllProductsUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    /*
    * @return FindProductResult[]
    */
    public function execute(): array
    {
        $products = $this->repository->searchAll();

        return array_map(
            fn ($product) => FindProductResult::fromProduct($product),
            $products
        );
    }
}
