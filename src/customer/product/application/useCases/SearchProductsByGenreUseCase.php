<?php

namespace Src\customer\product\application\useCases;

use Src\customer\product\domain\entities\Product;
use Src\customer\product\domain\repositories\ProductRepositoryInterface;

class SearchProductsByGenreUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    /**
     * @param string $genre
     * @return Product[]
     */
    public function execute(string $genre): array
    {
        return $this->repository->searchByGenre($genre);
    }

}