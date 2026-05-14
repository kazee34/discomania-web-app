<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $role
 * @property bool|null $is_active
 * @property string|null $last_login_at
 * @property int|null $created_by
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read AdminModel|null $creator
 * @property-read \App\Models\UserModel $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminModel whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminModel whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminModel whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminModel whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminModel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdminModel whereUserId($value)
 * @mixin \Eloquent
 */
	class AdminModel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
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
 * @mixin \Eloquent
 */
	class CustomerModel extends \Eloquent {}
}

namespace App\Models{
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
	class ProductModel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Carbon\CarbonImmutable|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property \Carbon\CarbonImmutable|null $two_factor_confirmed_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class UserModel extends \Eloquent {}
}

