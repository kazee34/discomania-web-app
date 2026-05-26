<?php

namespace Src\customer\product\application\useCases;

use Src\customer\product\domain\entities\Product;
use Src\customer\product\domain\repositories\ProductRepositoryInterface;

class SearchProductsBetweenYearsUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    /**
     * @return Product[]
     */
    public function execute(int $startYear, int $endYear): array
    {
        return $this->repository->searchBetweenYears($startYear, $endYear);
    }
}
