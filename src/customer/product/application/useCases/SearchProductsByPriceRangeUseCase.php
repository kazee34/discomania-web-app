<?php

namespace Src\customer\product\application\useCases;

use Src\customer\product\domain\entities\Product;
use Src\customer\product\domain\repositories\ProductRepositoryInterface;

class SearchProductsByPriceRangeUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    /**
     * @param float $minPrice
     * @param float $maxPrice
     * @return Product[]
     */
    public function execute(float $minPrice, float $maxPrice): array
    {
        return $this->repository->searchByPriceRange($minPrice, $maxPrice);
    }
}