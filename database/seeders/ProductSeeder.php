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
            ['artist' => 'Led Zeppelin',     'album_title' => 'IV',                       'genre' => 'Rock',       'release_year' => 1971, 'country' => 'Reino Unido', 'label' => 'Atlantic',      'price' => 29.99, 'stock' => 8],
            ['artist' => 'Pink Floyd',        'album_title' => 'The Dark Side of the Moon','genre' => 'Rock',       'release_year' => 1973, 'country' => 'Reino Unido', 'label' => 'Harvest',       'price' => 34.99, 'stock' => 5],
            ['artist' => 'The Beatles',       'album_title' => 'Abbey Road',               'genre' => 'Rock',       'release_year' => 1969, 'country' => 'Reino Unido', 'label' => 'Apple Records', 'price' => 32.99, 'stock' => 6],
            ['artist' => 'Radiohead',         'album_title' => 'OK Computer',              'genre' => 'Rock',       'release_year' => 1997, 'country' => 'Reino Unido', 'label' => 'Parlophone',    'price' => 27.99, 'stock' => 10],
            ['artist' => 'Nirvana',           'album_title' => 'Nevermind',                'genre' => 'Rock',       'release_year' => 1991, 'country' => 'Estados Unidos', 'label' => 'DGC Records', 'price' => 26.99, 'stock' => 12],
            ['artist' => 'The Velvet Underground', 'album_title' => 'The Velvet Underground & Nico', 'genre' => 'Rock', 'release_year' => 1967, 'country' => 'Estados Unidos', 'label' => 'Verve', 'price' => 31.99, 'stock' => 4],

            // Jazz
            ['artist' => 'Miles Davis',       'album_title' => 'Kind of Blue',             'genre' => 'Jazz',       'release_year' => 1959, 'country' => 'Estados Unidos', 'label' => 'Columbia',    'price' => 28.99, 'stock' => 7],
            ['artist' => 'John Coltrane',     'album_title' => 'A Love Supreme',           'genre' => 'Jazz',       'release_year' => 1965, 'country' => 'Estados Unidos', 'label' => 'Impulse!',    'price' => 30.99, 'stock' => 5],
            ['artist' => 'Bill Evans',        'album_title' => 'Waltz for Debby',          'genre' => 'Jazz',       'release_year' => 1962, 'country' => 'Estados Unidos', 'label' => 'Riverside',   'price' => 27.99, 'stock' => 6],

            // Electronic
            ['artist' => 'Daft Punk',         'album_title' => 'Random Access Memories',   'genre' => 'Electronic', 'release_year' => 2013, 'country' => 'Francia',      'label' => 'Columbia',    'price' => 39.99, 'stock' => 9],
            ['artist' => 'Aphex Twin',        'album_title' => 'Selected Ambient Works 85–92', 'genre' => 'Electronic', 'release_year' => 1992, 'country' => 'Reino Unido', 'label' => 'Apollo',  'price' => 33.99, 'stock' => 4],
            ['artist' => 'Boards of Canada',  'album_title' => 'Music Has the Right to Children', 'genre' => 'Electronic', 'release_year' => 1998, 'country' => 'Reino Unido', 'label' => 'Warp', 'price' => 35.99, 'stock' => 3],

            // Soul & Funk
            ['artist' => 'Marvin Gaye',       'album_title' => "What's Going On",          'genre' => 'Soul',       'release_year' => 1971, 'country' => 'Estados Unidos', 'label' => 'Tamla',     'price' => 25.99, 'stock' => 11],
            ['artist' => 'James Brown',       'album_title' => 'Live at the Apollo',        'genre' => 'Funk',       'release_year' => 1963, 'country' => 'Estados Unidos', 'label' => 'King',      'price' => 24.99, 'stock' => 8],

            // Pop
            ['artist' => 'David Bowie',       'album_title' => 'Ziggy Stardust',           'genre' => 'Pop',        'release_year' => 1972, 'country' => 'Reino Unido', 'label' => 'RCA',          'price' => 29.99, 'stock' => 7],
            ['artist' => 'Fleetwood Mac',     'album_title' => 'Rumours',                  'genre' => 'Pop',        'release_year' => 1977, 'country' => 'Estados Unidos', 'label' => 'Warner',    'price' => 28.99, 'stock' => 10],

            // Clásica
            ['artist' => 'Berliner Philharmoniker', 'album_title' => 'Beethoven: Symphony No. 9', 'genre' => 'Clásica', 'release_year' => 1963, 'country' => 'Alemania', 'label' => 'Deutsche Grammophon', 'price' => 22.99, 'stock' => 5],

            // Latina
            ['artist' => 'Carlos Santana',    'album_title' => 'Abraxas',                  'genre' => 'Rock Latino','release_year' => 1970, 'country' => 'México',       'label' => 'Columbia',    'price' => 26.99, 'stock' => 6],
            ['artist' => 'Celia Cruz',        'album_title' => 'La Negra Tiene Tumbao',    'genre' => 'Salsa',      'release_year' => 2001, 'country' => 'Cuba',         'label' => 'Sony',        'price' => 23.99, 'stock' => 9],

            // Hip-Hop
            ['artist' => 'A Tribe Called Quest', 'album_title' => 'The Low End Theory',   'genre' => 'Hip-Hop',    'release_year' => 1991, 'country' => 'Estados Unidos', 'label' => 'Jive',      'price' => 29.99, 'stock' => 7],
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
                'is_active'       => true,
            ]);
        }
    }
}
