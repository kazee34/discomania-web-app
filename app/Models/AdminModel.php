<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
class AdminModel extends Model
{
    protected $table = 'admins';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id', 'role', 'is_active', 'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(AdminModel::class, 'created_by');
    }
}
