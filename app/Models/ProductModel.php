<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $artist
 * @property string $album_title
 * @property string $slug
 * @property string|null $genre
 * @property int|null $release_year
 * @property string|null $country
 * @property string|null $label
 * @property numeric $price
 * @property int $stock_quantity
 * @property string|null $description
 * @property string|null $cover_image_url
 * @property bool|null $is_active
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel whereAlbumTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel whereArtist($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel whereCoverImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel whereGenre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel whereReleaseYear($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel whereStockQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductModel extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'artist',
        'album_title',
        'slug',
        'genre',
        'release_year',
        'country',
        'label',
        'price',
        'stock_quantity',
        'description',
        'cover_image_url',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'release_year' => 'integer',
        'is_active' => 'boolean',
    ];
}