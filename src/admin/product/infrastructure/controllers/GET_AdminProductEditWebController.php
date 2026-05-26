<?php

namespace Src\admin\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Src\admin\product\application\useCases\FindProductUseCase;

final class GET_AdminProductEditWebController extends Controller
{
    public function __construct(
        private FindProductUseCase $findProductUseCase,
    ) {}

    public function edit(int $id): Response
    {
        $p = $this->findProductUseCase->execute($id);

        return Inertia::render('admin/products/Form', [
            'product' => [
                'id' => $p->id,
                'artist' => $p->artist,
                'albumTitle' => $p->albumTitle,
                'price' => $p->price,
                'stock' => $p->stock,
                'slug' => $p->slug,
                'genre' => $p->genre,
                'releaseYear' => $p->releaseYear,
                'country' => $p->country,
                'label' => $p->label,
                'description' => $p->description,
                'coverImageUrl' => $p->coverImageUrl,
                'isActive' => $p->isActive,
            ],
        ]);
    }
}
