<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property bool|null $is_vip
 * @property string $first_name
 * @property string $last_name
 * @property string|null $phone
 * @property \Carbon\CarbonImmutable|null $birth_date
 * @property string|null $dni_nif
 * @property string|null $shipping_street
 * @property string|null $shipping_street_number
 * @property string|null $shipping_apartment
 * @property string|null $shipping_city
 * @property string|null $shipping_postal_code
 * @property string|null $shipping_state_province
 * @property string|null $shipping_country
 * @property string|null $shipping_iso_country_code
 * @property string|null $tax_name
 * @property string|null $tax_vat_number
 * @property int|null $total_orders
 * @property array<array-key, mixed>|null $wishlist
 * @property string|null $preferred_language
 * @property string|null $preferred_currency
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\UserModel $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereDniNif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel wherePreferredCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel wherePreferredLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereShippingApartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereShippingCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereShippingCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereShippingIsoCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereShippingPostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereShippingStateProvince($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereShippingStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereShippingStreetNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereTaxName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereTaxVatNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereTotalOrders($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CustomerModel whereWishlist($value)
 *
 * @mixin \Eloquent
 */
class CustomerModel extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'user_id',
        'is_active',
        'is_vip',
        'first_name',
        'last_name',
        'phone',
        'birth_date',
        'dni_nif',
        'shipping_street',
        'shipping_street_number',
        'shipping_apartment',
        'shipping_city',
        'shipping_postal_code',
        'shipping_state_province',
        'shipping_country',
        'shipping_iso_country_code',
        'tax_name',
        'tax_vat_number',
        'total_orders',
        'wishlist',
        'preferred_language',
        'preferred_currency',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean',
        'is_vip' => 'boolean',
        'wishlist' => 'array',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(OrderModel::class, 'customer_id');
    }
}
