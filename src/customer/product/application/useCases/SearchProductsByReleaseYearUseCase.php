<?php

namespace Src\customer\product\application\useCases;

use Src\customer\product\domain\entities\Product;
use Src\customer\product\domain\repositories\ProductRepositoryInterface;

class SearchProductsByReleaseYearUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    /**
     * @param int $releaseYear
     * @return Product[]
     */
    public function execute(int $releaseYear): array
    {
        return $this->repository->searchByReleaseYear($releaseYear);
    }
}