<?php

namespace Src\admin\product\infrastructure\repositories;

use Src\admin\product\domain\entities\Product;
use Src\admin\product\domain\exceptions\ProductNotFoundException;
use Src\admin\product\domain\repositories\ProductRepositoryInterface;
use App\Models\ProductModel;

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
        $model = ProductModel::find('id', $id);

        if (!$model) {
            throw new ProductNotFoundException($id);
        }

        return $this->toProduct($model);
    }

    public function findBySlug(string $slug): Product
    {
        $model = ProductModel::firstWhere('slug', '=', $slug);

        if (!$model) {
            // TODO: Create a specific exception for this case
            throw new \Exception("Product with slug '{$slug}' not found.");
        }

        return $this->toProduct($model);
    }

    public function search(string $query): array
    {
        return ProductModel::query()
            ->where('artist', 'like', "%{$query}%")
            ->orWhere('album_title', 'like', "%{$query}%")
            ->orWhere('genre', 'like', "%{$query}%")
            ->get()
            ->map(fn ($model) => $this->toProduct($model))
            ->toArray();
    }

    public function searchAll(): array
    {
        return ProductModel::all()
            ->map(fn ($model) => $this->toProduct($model))
            ->toArray();
    }

    public function delete(int $id): void
    {
        ProductModel::destroy($id);
    }

    private function toProduct(ProductModel $model): Product
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