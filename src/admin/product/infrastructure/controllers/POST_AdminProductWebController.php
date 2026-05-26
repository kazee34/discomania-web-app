<?php

namespace Src\admin\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Src\admin\product\application\dto\ProductRequest;
use Src\admin\product\application\useCases\CreateProductUseCase;
use Src\admin\product\infrastructure\validators\CreateProductRequest;

final class POST_AdminProductWebController extends Controller
{
    public function __construct(
        private CreateProductUseCase $createProductUseCase,
    ) {}

    public function store(CreateProductRequest $request): RedirectResponse
    {
        $this->createProductUseCase->execute(new ProductRequest(
            artist: $request->input('artist'),
            albumTitle: $request->input('album_title'),
            price: (float) $request->input('price'),
            stock: (int) $request->input('stock'),
            slug: $request->input('slug'),
            genre: $request->input('genre'),
            releaseYear: $request->input('release_year') ? (int) $request->input('release_year') : null,
            country: $request->input('country'),
            label: $request->input('label'),
            description: $request->input('description'),
            coverImageUrl: $request->input('cover_image_url'),
        ));

        return redirect()->route('admin.products.index')->with('success', 'Producto creado correctamente.');
    }
}
