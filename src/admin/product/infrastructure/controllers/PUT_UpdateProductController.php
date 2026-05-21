<?php

namespace Src\admin\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Src\admin\product\application\dto\ProductRequest;
use Src\admin\product\application\useCases\UpdateProductUseCase;
use Src\shared\domain\exceptions\ProductNotFoundException;
use Src\admin\product\infrastructure\validators\UpdateProductRequest;

final class PUT_UpdateProductController extends Controller
{
    public function __construct(
        private UpdateProductUseCase $updateProductUseCase
    ) {}

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        try {
            $result = $this->updateProductUseCase->execute(new ProductRequest(
                id: $id,
                artist: $request->input('artist'),
                albumTitle: $request->input('album_title'),
                price: $request->input('price') !== null ? (float) $request->input('price') : null,
                stock: $request->input('stock') !== null ? (int) $request->input('stock') : null,
                slug: $request->input('slug'),
                genre: $request->input('genre'),
                releaseYear: $request->input('release_year') !== null ? (int) $request->input('release_year') : null,
                country: $request->input('country'),
                label: $request->input('label'),
                description: $request->input('description'),
                coverImageUrl: $request->input('cover_image_url'),
            ));

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (ProductNotFoundException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        }
    }
}