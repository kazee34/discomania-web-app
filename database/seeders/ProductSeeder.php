<?php

namespace Database\Seeders;

use App\Models\ProductModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'artist' => 'Led Zeppelin', 'album_title' => 'IV',
                'genre' => 'Rock', 'release_year' => 1971, 'country' => 'Reino Unido',
                'label' => 'Atlantic', 'price' => 29.99, 'stock' => 8,
                'cover' => 'https://coverartarchive.org/release/eec0b9f7-3044-3cd2-9140-db912dbc97ad/front-500',
            ],
            [
                'artist' => 'Pink Floyd', 'album_title' => 'The Dark Side of the Moon',
                'genre' => 'Rock', 'release_year' => 1973, 'country' => 'Reino Unido',
                'label' => 'Harvest', 'price' => 34.99, 'stock' => 5,
                'cover' => 'https://coverartarchive.org/release/9a4df5ff-faa2-4e62-8542-dc7684e62bf2/front-500',
            ],
            [
                'artist' => 'The Beatles', 'album_title' => 'Abbey Road',
                'genre' => 'Rock', 'release_year' => 1969, 'country' => 'Reino Unido',
                'label' => 'Apple Records', 'price' => 32.99, 'stock' => 6,
                'cover' => 'https://coverartarchive.org/release/03437e02-835f-3a0a-a37c-48a36c2e852a/front-500',
            ],
            [
                'artist' => 'Radiohead', 'album_title' => 'OK Computer',
                'genre' => 'Rock', 'release_year' => 1997, 'country' => 'Reino Unido',
                'label' => 'Parlophone', 'price' => 27.99, 'stock' => 10,
                'cover' => 'https://coverartarchive.org/release/4b3d18cc-8937-36f4-8de0-481088be58e6/front-500',
            ],
            [
                'artist' => 'Nirvana', 'album_title' => 'Nevermind',
                'genre' => 'Rock', 'release_year' => 1991, 'country' => 'Estados Unidos',
                'label' => 'DGC Records', 'price' => 26.99, 'stock' => 12,
                'cover' => 'https://coverartarchive.org/release/006cb56d-6eff-4b7d-853f-ecd2db97f3b2/front-500',
            ],
            [
                'artist' => 'The Velvet Underground', 'album_title' => 'The Velvet Underground & Nico',
                'genre' => 'Rock', 'release_year' => 1967, 'country' => 'Estados Unidos',
                'label' => 'Verve', 'price' => 31.99, 'stock' => 4,
                'cover' => 'https://coverartarchive.org/release/2657e677-49ce-480f-8868-04a0b354aec2/front-500',
            ],
            [
                'artist' => 'Miles Davis', 'album_title' => 'Kind of Blue',
                'genre' => 'Jazz', 'release_year' => 1959, 'country' => 'Estados Unidos',
                'label' => 'Columbia', 'price' => 28.99, 'stock' => 7,
                'cover' => 'https://coverartarchive.org/release/e32a3f0b-1c19-3170-bb1c-650893774744/front-500',
            ],
            [
                'artist' => 'John Coltrane', 'album_title' => 'A Love Supreme',
                'genre' => 'Jazz', 'release_year' => 1965, 'country' => 'Estados Unidos',
                'label' => 'Impulse!', 'price' => 30.99, 'stock' => 5,
                'cover' => 'https://coverartarchive.org/release/77d06b99-117a-3ba3-8230-69361ad7be91/front-500',
            ],
            [
                'artist' => 'Bill Evans', 'album_title' => 'Waltz for Debby',
                'genre' => 'Jazz', 'release_year' => 1962, 'country' => 'Estados Unidos',
                'label' => 'Riverside', 'price' => 27.99, 'stock' => 6,
                'cover' => 'https://coverartarchive.org/release/e791c133-bdda-434e-bc12-7ceb08de2701/front-500',
            ],
            [
                'artist' => 'Daft Punk', 'album_title' => 'Random Access Memories',
                'genre' => 'Electronic', 'release_year' => 2013, 'country' => 'Francia',
                'label' => 'Columbia', 'price' => 39.99, 'stock' => 9,
                'cover' => 'https://coverartarchive.org/release/ec116461-5b0d-4c98-bb44-a4de5de63076/12639807914-500.jpg',
            ],
            [
                'artist' => 'Aphex Twin', 'album_title' => 'Selected Ambient Works 85–92',
                'genre' => 'Electronic', 'release_year' => 1992, 'country' => 'Reino Unido',
                'label' => 'Apollo', 'price' => 33.99, 'stock' => 4,
                'cover' => 'https://coverartarchive.org/release/8e102a2b-1955-4346-873a-c11f14626f45/front-500',
            ],
            [
                'artist' => 'Boards of Canada', 'album_title' => 'Music Has the Right to Children',
                'genre' => 'Electronic', 'release_year' => 1998, 'country' => 'Reino Unido',
                'label' => 'Warp', 'price' => 35.99, 'stock' => 3,
                'cover' => 'https://coverartarchive.org/release/3fdcf49c-e297-4c21-880d-dba4c40d98dd/front-500',
            ],
            [
                'artist' => 'Marvin Gaye', 'album_title' => "What's Going On",
                'genre' => 'Soul', 'release_year' => 1971, 'country' => 'Estados Unidos',
                'label' => 'Tamla', 'price' => 25.99, 'stock' => 11,
                'cover' => 'https://coverartarchive.org/release/ba91f9e2-2391-4bbd-9114-c9eff138fd98/front-500',
            ],
            [
                'artist' => 'James Brown', 'album_title' => 'Live at the Apollo',
                'genre' => 'Funk', 'release_year' => 1963, 'country' => 'Estados Unidos',
                'label' => 'King', 'price' => 24.99, 'stock' => 8,
                'cover' => 'https://coverartarchive.org/release/395199e3-2cb6-343a-a065-2537c8c4ecd4/front-500',
            ],
            [
                'artist' => 'David Bowie', 'album_title' => 'Ziggy Stardust',
                'genre' => 'Pop', 'release_year' => 1972, 'country' => 'Reino Unido',
                'label' => 'RCA', 'price' => 29.99, 'stock' => 7,
                'cover' => 'https://coverartarchive.org/release/da4db8d1-13b2-3d5e-a447-e2ad7733476a/front-500',
            ],
            [
                'artist' => 'Fleetwood Mac', 'album_title' => 'Rumours',
                'genre' => 'Pop', 'release_year' => 1977, 'country' => 'Estados Unidos',
                'label' => 'Warner', 'price' => 28.99, 'stock' => 10,
                'cover' => 'https://coverartarchive.org/release/7ec069c0-4424-3169-8ed0-d5e2473e0e84/front-500',
            ],
            [
                'artist' => 'Berliner Philharmoniker', 'album_title' => 'Beethoven: Symphony No. 9',
                'genre' => 'Clásica', 'release_year' => 1963, 'country' => 'Alemania',
                'label' => 'Deutsche Grammophon', 'price' => 22.99, 'stock' => 5,
                'cover' => 'https://coverartarchive.org/release/c07a0583-e763-31d9-a682-a00af4baf661/front-500',
            ],
            [
                'artist' => 'Carlos Santana', 'album_title' => 'Abraxas',
                'genre' => 'Rock Latino', 'release_year' => 1970, 'country' => 'México',
                'label' => 'Columbia', 'price' => 26.99, 'stock' => 6,
                'cover' => 'https://coverartarchive.org/release/00bc5be5-1c32-413b-9ce9-6999291616a0/front-500',
            ],
            [
                'artist' => 'Celia Cruz', 'album_title' => 'La Negra Tiene Tumbao',
                'genre' => 'Salsa', 'release_year' => 2001, 'country' => 'Cuba',
                'label' => 'Sony', 'price' => 23.99, 'stock' => 9,
                'cover' => 'https://coverartarchive.org/release/d77e87b9-641f-44f2-b15c-36ae1c2a9239/front-500',
            ],
            [
                'artist' => 'A Tribe Called Quest', 'album_title' => 'The Low End Theory',
                'genre' => 'Hip-Hop', 'release_year' => 1991, 'country' => 'Estados Unidos',
                'label' => 'Jive', 'price' => 29.99, 'stock' => 7,
                'cover' => 'https://coverartarchive.org/release/057796e8-30bb-4397-8e3d-3a8e6f0d7b19/front-500',
            ],
            [
                'artist' => 'Death', 'album_title' => 'Symbolic',
                'genre' => 'Death Metal', 'release_year' => 1995, 'country' => 'Estados Unidos',
                'label' => 'Relativity Records', 'price' => 29.99, 'stock' => 6,
                'cover' => 'https://coverartarchive.org/release/85ac792c-bbb6-4c6a-9540-e0edda90e6d4/front-500',
            ],
            [
                'artist' => 'Morbid Angel', 'album_title' => 'Altars of Madness',
                'genre' => 'Death Metal', 'release_year' => 1989, 'country' => 'Estados Unidos',
                'label' => 'Earache Records', 'price' => 27.99, 'stock' => 5,
                'cover' => 'https://coverartarchive.org/release/0e47ca20-9a60-3bd0-afe0-a1e090a75f5e/front-500',
            ],
            [
                'artist' => 'Obituary', 'album_title' => 'Slowly We Rot',
                'genre' => 'Death Metal', 'release_year' => 1989, 'country' => 'Estados Unidos',
                'label' => 'Roadrunner Records', 'price' => 26.99, 'stock' => 7,
                'cover' => 'https://coverartarchive.org/release/a0f8af73-73ea-445a-af1a-f2eaa86969d1/front-500',
            ],
            [
                'artist' => 'Cannibal Corpse', 'album_title' => 'Tomb of the Mutilated',
                'genre' => 'Death Metal', 'release_year' => 1992, 'country' => 'Estados Unidos',
                'label' => 'Metal Blade Records', 'price' => 28.99, 'stock' => 4,
                'cover' => 'https://coverartarchive.org/release/6878457f-9bb4-4571-b02b-e5154ad96f6c/front-500',
            ],
            [
                'artist' => 'Possessed', 'album_title' => 'Seven Churches',
                'genre' => 'Death Metal', 'release_year' => 1985, 'country' => 'Estados Unidos',
                'label' => 'Combat Records', 'price' => 27.99, 'stock' => 3,
                'cover' => 'https://coverartarchive.org/release/6f903ac2-3fdb-39a8-b22c-084e8e6896ad/front-500',
            ],
            [
                'artist' => 'Suffocation', 'album_title' => 'Effigy of the Forgotten',
                'genre' => 'Brutal Death Metal', 'release_year' => 1991, 'country' => 'Estados Unidos',
                'label' => 'Roadrunner Records', 'price' => 29.99, 'stock' => 5,
                'cover' => 'https://coverartarchive.org/release/b83458f4-58db-4b00-952d-1c3890b3fd1b/front-500',
            ],
            [
                'artist' => 'Dying Fetus', 'album_title' => 'Destroy the Opposition',
                'genre' => 'Brutal Death Metal', 'release_year' => 2000, 'country' => 'Estados Unidos',
                'label' => 'Relapse Records', 'price' => 27.99, 'stock' => 6,
                'cover' => 'https://coverartarchive.org/release/8b4c9a14-0190-483c-9f94-0aec0694954e/front-500',
            ],
            [
                'artist' => 'Cryptopsy', 'album_title' => 'None So Vile',
                'genre' => 'Brutal Death Metal', 'release_year' => 1996, 'country' => 'Canadá',
                'label' => 'Wrong Again Records', 'price' => 29.99, 'stock' => 4,
                'cover' => 'https://coverartarchive.org/release/39bc6188-e3a5-4fea-b163-70c99adb2049/front-500',
            ],
            [
                'artist' => 'Napalm Death', 'album_title' => 'Scum',
                'genre' => 'Grindcore', 'release_year' => 1987, 'country' => 'Reino Unido',
                'label' => 'Earache Records', 'price' => 26.99, 'stock' => 8,
                'cover' => 'https://coverartarchive.org/release/82e6b5e6-e50c-331e-b989-21defc1be5c4/front-500',
            ],
            [
                'artist' => 'Terrorizer', 'album_title' => 'World Downfall',
                'genre' => 'Grindcore', 'release_year' => 1989, 'country' => 'Estados Unidos',
                'label' => 'Earache Records', 'price' => 27.99, 'stock' => 5,
                'cover' => 'https://coverartarchive.org/release/d64cad88-f123-4c67-85c5-7f4b6d073ac9/front-500',
            ],
            [
                'artist' => 'Repulsion', 'album_title' => 'Horrified',
                'genre' => 'Grindcore', 'release_year' => 1989, 'country' => 'Estados Unidos',
                'label' => 'Necrosis Records', 'price' => 25.99, 'stock' => 4,
                'cover' => 'https://coverartarchive.org/release/012e500b-dd17-3fa3-807e-ceec7995b30a/front-500',
            ],
            [
                'artist' => 'Brutal Truth', 'album_title' => 'Extreme Conditions Demand Extreme Responses',
                'genre' => 'Grindcore', 'release_year' => 1992, 'country' => 'Estados Unidos',
                'label' => 'Earache Records', 'price' => 26.99, 'stock' => 5,
                'cover' => 'https://coverartarchive.org/release/93d0c13c-f46c-417c-bc0a-20864732dd9a/front-500',
            ],
        ];

        foreach ($products as $data) {
            ProductModel::create([
                'artist' => $data['artist'],
                'album_title' => $data['album_title'],
                'slug' => Str::slug($data['artist'].'-'.$data['album_title']),
                'genre' => $data['genre'],
                'release_year' => $data['release_year'],
                'country' => $data['country'],
                'label' => $data['label'],
                'price' => $data['price'],
                'stock_quantity' => $data['stock'],
                'cover_image_url' => $data['cover'],
                'is_active' => true,
            ]);
        }
    }
}
