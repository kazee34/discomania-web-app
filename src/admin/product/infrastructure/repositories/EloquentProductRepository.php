<?php

namespace Src\admin\product\infrastructure\repositories;

use Src\admin\product\domain\entities\Product;
use Src\admin\product\domain\repositories\ProductRepositoryInterface;
use App\Models\ProductModel;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function save(Product $product): void
    {
        if ($product->id() === null) {
            ProductModel::create([
                'artist' => $product->artist(),
                'album_title' => $product->albumTitle(),
                'slug' => $product->slug(),
                'genre' => $product->genre(),
                'release_year' => $product->releaseYear(),
                'country' => $product->country(),
                'label' => $product->label(),
                'price' => $product->price(),
                'stock' => $product->stock(),
                'description' => $product->description(),
                'cover_image_url' => $product->coverImageUrl(),
                'is_active' => $product->isActive(),
            ]);


        } else {
            ProductModel::where('id', $product->id())->update([
                'artist' => $product->artist(),
                'album_title' => $product->albumTitle(),
                'slug' => $product->slug(),
                'genre' => $product->genre(),
                'release_year' => $product->releaseYear(),
                'country' => $product->country(),
                'label' => $product->label(),
                'price' => $product->price()->value(),
                'stock' => $product->stock()->value(),
                'description' => $product->description(),
                'cover_image_url' => $product->coverImageUrl(),
                'is_active' => $product->isActive(),
            ]);
        }
    }

    public function findById(int $id): ?Product
    {
        $model = ProductModel::find('id', $id);

        if (!$model) {
            return null;
        }

        return $this->toProduct($model);
    }

    public function searchAll(): array
    {
        return ProductModel::all()->map(function ($model) {
            return $this->toProduct($model);
        })->toArray();
    }

    public function delete(int $id): void
    {
        ProductModel::destroy('id', $id);
    }

    public function findBySlug(string $slug): ?Product
    {
        $model = ProductModel::find('slug', $slug)->first();

        if (!$model) {
            return null;
        }

        return $this->toProduct($model);
    }

    // FilterPattern?
    /*
        * For more complex search, we could implement a specification pattern or a query builder
        * that allows us to build dynamic queries based on various criteria (artist, genre, release year, etc.)
        * @return Product[]
    */

    public function search(string $query): array
    {
        return ProductModel::query()->where('artist', 'like', "%{$query}%")
            ->orWhere('album_title', 'like', "%{$query}%")
            ->orWhere('genre', 'like', "%{$query}%")
            ->get()
            ->map(function ($model) {
                return Product::fromPrimitives(
                    id: $model->id,
                    artist: $model->artist,
                    albumTitle: $model->album_title,
                    price: $model->price,
                    stock: $model->stock,
                    slug: $model->slug,
                    genre: $model->genre,
                    releaseYear: $model->release_year,
                    country: $model->country,
                    label: $model->label,
                    description: $model->description,
                    coverImageUrl: $model->cover_image_url
                );
            })->toArray();
    }

    private function toProduct(ProductModel $model): Product
    {
        return Product::fromPrimitives(
            id: $model->id,
            artist: $model->artist,
            albumTitle: $model->album_title,
            price: $model->price,
            stock: $model->stock,
            slug: $model->slug,
            genre: $model->genre,
            releaseYear: $model->release_year,
            country: $model->country,
            label: $model->label,
            description: $model->description,
            coverImageUrl: $model->cover_image_url
        );
    }
}