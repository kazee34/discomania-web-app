<?php

namespace Src\customer\product\application\useCases;

use Src\customer\product\domain\entities\Product;
use Src\customer\product\domain\repositories\ProductRepositoryInterface;

class SearchProductsByCountryUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    /**
     * @return Product[]
     */
    public function execute(string $country): array
    {
        return $this->repository->searchByCountry($country);
    }
}
