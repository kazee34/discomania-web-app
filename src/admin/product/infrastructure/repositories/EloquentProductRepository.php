<?php

namespace Src\admin\product\infrastructure\repositories;

use App\Models\ProductModel;
use Src\admin\product\domain\entities\Product;
use Src\admin\product\domain\repositories\ProductRepositoryInterface;
use Src\shared\domain\exceptions\ProductNotFoundException;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function save(Product $product): void
    {
        if ($product->id() === null) {
            ProductModel::create([
                'artist'          => $product->artist(),
                'album_title'     => $product->albumTitle(),
                'slug'            => $product->slug(),
                'genre'           => $product->genre(),
                'release_year'    => $product->releaseYear(),
                'country'         => $product->country(),
                'label'           => $product->label(),
                'price'           => $product->price()->value(),
                'stock_quantity'  => $product->stock()->value(),
                'description'     => $product->description(),
                'cover_image_url' => $product->coverImageUrl(),
                'is_active'       => $product->isActive(),
            ]);
        } else {
            ProductModel::whereKey((int) $product->id())->update([
                'artist'          => $product->artist(),
                'album_title'     => $product->albumTitle(),
                'slug'            => $product->slug(),
                'genre'           => $product->genre(),
                'release_year'    => $product->releaseYear(),
                'country'         => $product->country(),
                'label'           => $product->label(),
                'price'           => $product->price()->value(),
                'stock_quantity'  => $product->stock()->value(),
                'description'     => $product->description(),
                'cover_image_url' => $product->coverImageUrl(),
                'is_active'       => $product->isActive(),
            ]);
        }
    }

    public function findById(int $id): Product
    {
        $model = ProductModel::find($id);

        if (!$model) {
            throw new ProductNotFoundException();
        }

        return self::toProduct($model);
    }

    public function findBySlug(string $slug): Product
    {
        $model = ProductModel::where('slug', $slug)->first();

        if (!$model) {
            throw new ProductNotFoundException();
        }

        return self::toProduct($model);
    }

    public function search(string $query): array
    {
        return ProductModel::query()
            ->where('artist', 'like', "%{$query}%")
            ->orWhere('album_title', 'like', "%{$query}%")
            ->orWhere('genre', 'like', "%{$query}%")
            ->get()
            ->map(fn ($model) => self::toProduct($model))
            ->toArray();
    }

    public function searchAll(): array
    {
        return ProductModel::all()
            ->map(fn ($model) => self::toProduct($model))
            ->toArray();
    }

    public function delete(int $id): void
    {
        ProductModel::destroy($id);
    }

    private static function toProduct(ProductModel $model): Product
    {
        return Product::fromPrimitives(
            id: $model->id,
            artist: $model->artist,
            albumTitle: $model->album_title,
            price: (float) $model->price,
            stock: $model->stock_quantity,
            slug: $model->slug,
            genre: $model->genre,
            releaseYear: $model->release_year,
            country: $model->country,
            label: $model->label,
            description: $model->description,
            coverImageUrl: $model->cover_image_url,
            isActive: $model->is_active,
        );
    }
}