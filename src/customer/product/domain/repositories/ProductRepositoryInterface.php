<?php

namespace Src\customer\product\domain\repositories;

use Src\customer\product\domain\entities\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): Product;

    public function searchAll(): array;

    public function findBySlug(string $slug): Product;

    public function searchByName(string $name): array;

    public function searchByGenre(string $genre): array;

    public function searchByArtist(string $artist): array;

    public function searchByCountry(string $country): array;

    public function searchByReleaseYear(int $releaseYear): array;

    public function searchByPriceRange(float $minPrice, float $maxPrice): array;

    public function searchBetweenYears(int $startYear, int $endYear): array;

    public function decrementStock(int $productId, int $quantity): void;
}