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
            // Rock
            [
                'artist' => 'Led Zeppelin', 'album_title' => 'IV',
                'genre' => 'Rock', 'release_year' => 1971, 'country' => 'Reino Unido',
                'label' => 'Atlantic', 'price' => 29.99, 'stock' => 8,
                'cover' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b9/Led_Zeppelin_-_Led_Zeppelin_IV.jpg/500px-Led_Zeppelin_-_Led_Zeppelin_IV.jpg',
            ],
            [
                'artist' => 'Pink Floyd', 'album_title' => 'The Dark Side of the Moon',
                'genre' => 'Rock', 'release_year' => 1973, 'country' => 'Reino Unido',
                'label' => 'Harvest', 'price' => 34.99, 'stock' => 5,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/3/3b/Dark_Side_of_the_Moon.png',
            ],
            [
                'artist' => 'The Beatles', 'album_title' => 'Abbey Road',
                'genre' => 'Rock', 'release_year' => 1969, 'country' => 'Reino Unido',
                'label' => 'Apple Records', 'price' => 32.99, 'stock' => 6,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/4/42/Beatles_-_Abbey_Road.jpg',
            ],
            [
                'artist' => 'Radiohead', 'album_title' => 'OK Computer',
                'genre' => 'Rock', 'release_year' => 1997, 'country' => 'Reino Unido',
                'label' => 'Parlophone', 'price' => 27.99, 'stock' => 10,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/b/ba/Radioheadokcomputer.png',
            ],
            [
                'artist' => 'Nirvana', 'album_title' => 'Nevermind',
                'genre' => 'Rock', 'release_year' => 1991, 'country' => 'Estados Unidos',
                'label' => 'DGC Records', 'price' => 26.99, 'stock' => 12,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/b/b7/NirvanaNevermindalbumcover.jpg',
            ],
            [
                'artist' => 'The Velvet Underground', 'album_title' => 'The Velvet Underground & Nico',
                'genre' => 'Rock', 'release_year' => 1967, 'country' => 'Estados Unidos',
                'label' => 'Verve', 'price' => 31.99, 'stock' => 4,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/0/0d/TheVelvetUndergroundAndNico.jpg',
            ],

            // Jazz
            [
                'artist' => 'Miles Davis', 'album_title' => 'Kind of Blue',
                'genre' => 'Jazz', 'release_year' => 1959, 'country' => 'Estados Unidos',
                'label' => 'Columbia', 'price' => 28.99, 'stock' => 7,
                'cover' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/MilesDavisKindofBlue.jpg/500px-MilesDavisKindofBlue.jpg',
            ],
            [
                'artist' => 'John Coltrane', 'album_title' => 'A Love Supreme',
                'genre' => 'Jazz', 'release_year' => 1965, 'country' => 'Estados Unidos',
                'label' => 'Impulse!', 'price' => 30.99, 'stock' => 5,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/d/d2/John_Coltrane_-_A_Love_Supreme.jpg',
            ],
            [
                'artist' => 'Bill Evans', 'album_title' => 'Waltz for Debby',
                'genre' => 'Jazz', 'release_year' => 1962, 'country' => 'Estados Unidos',
                'label' => 'Riverside', 'price' => 27.99, 'stock' => 6,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/4/41/WaltzForDebby.jpg',
            ],

            // Electronic
            [
                'artist' => 'Daft Punk', 'album_title' => 'Random Access Memories',
                'genre' => 'Electronic', 'release_year' => 2013, 'country' => 'Francia',
                'label' => 'Columbia', 'price' => 39.99, 'stock' => 9,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/a/a0/Daft_Punk_-_Random_Access_Memories.png',
            ],
            [
                'artist' => 'Aphex Twin', 'album_title' => 'Selected Ambient Works 85–92',
                'genre' => 'Electronic', 'release_year' => 1992, 'country' => 'Reino Unido',
                'label' => 'Apollo', 'price' => 33.99, 'stock' => 4,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/4/47/Aphex_Twin_-_Selected_Ambient_Works_85-92.png',
            ],
            [
                'artist' => 'Boards of Canada', 'album_title' => 'Music Has the Right to Children',
                'genre' => 'Electronic', 'release_year' => 1998, 'country' => 'Reino Unido',
                'label' => 'Warp', 'price' => 35.99, 'stock' => 3,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/2/23/Boards_of_Canada_-_Music_Has_the_Right_to_Children.jpg',
            ],

            // Soul & Funk
            [
                'artist' => 'Marvin Gaye', 'album_title' => "What's Going On",
                'genre' => 'Soul', 'release_year' => 1971, 'country' => 'Estados Unidos',
                'label' => 'Tamla', 'price' => 25.99, 'stock' => 11,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/b/bc/MarvinGayeWhatsGoingOnalbumcover.jpg',
            ],
            [
                'artist' => 'James Brown', 'album_title' => 'Live at the Apollo',
                'genre' => 'Funk', 'release_year' => 1963, 'country' => 'Estados Unidos',
                'label' => 'King', 'price' => 24.99, 'stock' => 8,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/7/76/JamesBrown_LiveAtTheApollo.jpg',
            ],

            // Pop
            [
                'artist' => 'David Bowie', 'album_title' => 'Ziggy Stardust',
                'genre' => 'Pop', 'release_year' => 1972, 'country' => 'Reino Unido',
                'label' => 'RCA', 'price' => 29.99, 'stock' => 7,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/thumb/c/c2/David_Bowie_-_The_Rise_and_Fall_of_Ziggy_Stardust.jpg/500px-David_Bowie_-_The_Rise_and_Fall_of_Ziggy_Stardust.jpg',
            ],
            [
                'artist' => 'Fleetwood Mac', 'album_title' => 'Rumours',
                'genre' => 'Pop', 'release_year' => 1977, 'country' => 'Estados Unidos',
                'label' => 'Warner', 'price' => 28.99, 'stock' => 10,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/f/f1/Fleetwood_Mac_-_Rumours.png',
            ],

            // Clásica
            [
                'artist' => 'Berliner Philharmoniker', 'album_title' => 'Beethoven: Symphony No. 9',
                'genre' => 'Clásica', 'release_year' => 1963, 'country' => 'Alemania',
                'label' => 'Deutsche Grammophon', 'price' => 22.99, 'stock' => 5,
                'cover' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6f/Beethoven.jpg/500px-Beethoven.jpg',
            ],

            // Latina
            [
                'artist' => 'Carlos Santana', 'album_title' => 'Abraxas',
                'genre' => 'Rock Latino', 'release_year' => 1970, 'country' => 'México',
                'label' => 'Columbia', 'price' => 26.99, 'stock' => 6,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/9/91/Santana_Abraxas.jpg',
            ],
            [
                'artist' => 'Celia Cruz', 'album_title' => 'La Negra Tiene Tumbao',
                'genre' => 'Salsa', 'release_year' => 2001, 'country' => 'Cuba',
                'label' => 'Sony', 'price' => 23.99, 'stock' => 9,
                'cover' => null,
            ],

            // Hip-Hop
            [
                'artist' => 'A Tribe Called Quest', 'album_title' => 'The Low End Theory',
                'genre' => 'Hip-Hop', 'release_year' => 1991, 'country' => 'Estados Unidos',
                'label' => 'Jive', 'price' => 29.99, 'stock' => 7,
                'cover' => 'https://upload.wikimedia.org/wikipedia/en/4/4f/ATCQLowEndTheory.jpg',
            ],
        ];

        foreach ($products as $data) {
            ProductModel::create([
                'artist'          => $data['artist'],
                'album_title'     => $data['album_title'],
                'slug'            => Str::slug($data['artist'] . '-' . $data['album_title']),
                'genre'           => $data['genre'],
                'release_year'    => $data['release_year'],
                'country'         => $data['country'],
                'label'           => $data['label'],
                'price'           => $data['price'],
                'stock_quantity'  => $data['stock'],
                'cover_image_url' => $data['cover'],
                'is_active'       => true,
            ]);
        }
    }
}
