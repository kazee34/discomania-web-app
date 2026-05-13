<?php

namespace Src\admin\product\domain\repositories;

use Src\admin\product\domain\entities\Product;

interface ProductRepositoryInterface
{
    public function save(Product $product): void;

    public function findById(int $id): ?Product;
    
    public function searchAll(): array;
    
    public function delete(int $id): void;
    
    public function findBySlug(string $slug): ?Product;
    
    public function search(string $query): array;
}