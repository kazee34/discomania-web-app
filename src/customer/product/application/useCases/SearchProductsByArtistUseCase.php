<?php

namespace Src\customer\product\application\useCases;

use Src\customer\product\domain\repositories\ProductRepositoryInterface;
use Src\customer\product\domain\entities\Product;

class SearchProductsByArtistUseCase
{
    public function __construct(
        private ProductRepositoryInterface $repository
    ) {}

    /**
     * @param string $artist
     * @return Product[]
     */
    public function execute(string $artist): array
    {
        return $this->repository->searchByArtist($artist);
    }
}