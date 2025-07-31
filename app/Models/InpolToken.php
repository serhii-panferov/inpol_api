<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InpolToken whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InpolToken extends Model
{
    protected $fillable = [
        'token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
