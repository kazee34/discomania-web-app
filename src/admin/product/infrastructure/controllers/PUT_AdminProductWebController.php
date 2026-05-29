<?php

namespace Src\admin\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Src\admin\product\application\dto\ProductRequest;
use Src\admin\product\application\useCases\UpdateProductUseCase;
use Src\admin\product\infrastructure\validators\UpdateProductRequest;

final class PUT_AdminProductWebController extends Controller
{
    public function __construct(
        private UpdateProductUseCase $updateProductUseCase,
    ) {}

    public function update(UpdateProductRequest $request, int $id): RedirectResponse
    {
        $this->updateProductUseCase->execute(new ProductRequest(
            id: $id,
            artist: $request->input('artist'),
            albumTitle: $request->input('album_title'),
            price: $request->input('price') !== null ? (float) $request->input('price') : null,
            stock: $request->input('stock') !== null ? (int) $request->input('stock') : null,
            genre: $request->input('genre'),
            releaseYear: $request->input('release_year') ? (int) $request->input('release_year') : null,
            country: $request->input('country'),
            label: $request->input('label'),
            description: $request->input('description'),
            coverImageUrl: $request->input('cover_image_url'),
        ));

        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado correctamente.');
    }
}
