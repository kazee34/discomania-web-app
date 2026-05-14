<?php

namespace Src\customer\product\application\useCases;

use Src\customer\product\domain\repositories\ProductRepositoryInterface;
use Src\customer\product\domain\entities\Product;

class SearchProductsByCountryUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}
        
    /**
     * @param string $country
     * @return Product[]
     */
    public function execute(string $country): array
    {
        return $this->repository->searchByCountry($country);
    }
}