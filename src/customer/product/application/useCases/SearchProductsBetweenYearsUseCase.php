<?php

namespace Src\customer\product\application\useCases;

use Src\customer\product\domain\repositories\ProductRepositoryInterface;
use Src\customer\product\domain\entities\Product;

class SearchProductsBetweenYearsUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    /**
     * @param int $startYear
     * @param int $endYear
     * @return Product[]
     */
    public function execute(int $startYear, int $endYear): array
    {
        return $this->repository->searchBetweenYears($startYear, $endYear);
    }
}