<?php

namespace Src\admin\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Src\admin\product\application\useCases\DeleteProductUseCase;

final class DELETE_AdminProductWebController extends Controller
{
    public function __construct(
        private DeleteProductUseCase $deleteProductUseCase,
    ) {}

    public function destroy(int $id): RedirectResponse
    {
        $this->deleteProductUseCase->execute($id);

        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado correctamente.');
    }
}
