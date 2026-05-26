<?php

namespace Src\customer\product\application\useCases;

use Src\customer\product\domain\entities\Product;
use Src\customer\product\domain\repositories\ProductRepositoryInterface;

class SearchProductsByNameUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    /**
     * @return Product[]
     */
    public function execute(string $name): array
    {
        return $this->repository->searchByName($name);
    }
}
