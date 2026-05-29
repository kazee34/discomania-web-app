<?php

namespace Src\customer\product\infrastructure\repositories;

use App\Models\ProductModel;
use Src\customer\product\domain\entities\Product;
use Src\customer\product\domain\repositories\ProductRepositoryInterface;
use Src\shared\domain\exceptions\ProductNotFoundException;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function findById(int $id): Product
    {
        $model = ProductModel::find($id);

        if (! $model) {
            throw new ProductNotFoundException;
        }

        return self::toProduct($model);
    }

    public function searchAll(): array
    {
        return ProductModel::where('is_active', true)
            ->get()
            ->map(fn ($model) => self::toProduct($model))
            ->toArray();
    }

    public function findBySlug(string $slug): Product
    {
        $model = ProductModel::where('slug', $slug)->first();

        if (! $model) {
            throw new ProductNotFoundException;
        }

        return self::toProduct($model);
    }

    public function searchByName(string $albumTitle): array
    {
        return ProductModel::where('album_title', 'like', "%{$albumTitle}%")
            ->get()
            ->map(fn ($model) => self::toProduct($model))
            ->toArray();
    }

    public function searchByGenre(string $genre): array
    {
        return ProductModel::where('genre', 'like', "%{$genre}%")
            ->get()
            ->map(fn ($model) => self::toProduct($model))
            ->toArray();
    }

    public function searchByArtist(string $artist): array
    {
        return ProductModel::where('artist', 'like', "%{$artist}%")
            ->get()
            ->map(fn ($model) => self::toProduct($model))
            ->toArray();
    }

    public function searchByCountry(string $country): array
    {
        return ProductModel::where('country', $country)
            ->get()
            ->map(fn ($model) => self::toProduct($model))
            ->toArray();
    }

    public function searchByReleaseYear(int $releaseYear): array
    {
        return ProductModel::where('release_year', $releaseYear)
            ->get()
            ->map(fn ($model) => self::toProduct($model))
            ->toArray();
    }

    public function searchByPriceRange(float $minPrice, float $maxPrice): array
    {
        return ProductModel::whereBetween('price', [$minPrice, $maxPrice])
            ->get()
            ->map(fn ($model) => self::toProduct($model))
            ->toArray();
    }

    public function searchBetweenYears(int $startYear, int $endYear): array
    {
        return ProductModel::whereBetween('release_year', [$startYear, $endYear])
            ->get()
            ->map(fn ($model) => self::toProduct($model))
            ->toArray();
    }

    public function decrementStock(int $productId, int $quantity): void
    {
        ProductModel::where('id', $productId)
            ->where('stock_quantity', '>=', $quantity)
            ->decrement('stock_quantity', $quantity);
    }

    public function incrementStock(int $productId, int $quantity): void
    {
        ProductModel::where('id', $productId)
            ->increment('stock_quantity', $quantity);
    }

    private static function toProduct(ProductModel $model): Product
    {
        return new Product(
            id: $model->id,
            slug: $model->slug,
            artist: $model->artist,
            albumTitle: $model->album_title,
            price: $model->price,
            genre: $model->genre,
            country: $model->country,
            releaseYear: $model->release_year,
            coverImageUrl: $model->cover_image_url,
            description: $model->description,
            label: $model->label,
            stockQuantity: $model->stock_quantity ?? 0,
        );
    }
}
