<?php

namespace Src\customer\product\infrastructure\controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;
use Src\customer\product\domain\entities\Product;
use Src\customer\product\domain\repositories\ProductRepositoryInterface;

final class GET_WelcomeController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function index(): Response
    {
        $products = $this->productRepository->searchAll();

        $vinylImages = array_filter(
            array_map(fn (Product $p) => $p->coverImageUrl(), $products),
            fn (?string $url) => $url !== null && $url !== '',
        );

        shuffle($vinylImages);
        $vinylImages = array_values(array_slice($vinylImages, 0, random_int(15, 20)));

        return Inertia::render('Welcome', [
            'canRegister' => Features::enabled(Features::registration()),
            'vinylImages' => $vinylImages,
        ]);
    }
}
