<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

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
class UserModel extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $table = 'users';

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }
}
