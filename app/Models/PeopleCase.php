<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property int $status
 * @property string|null $signature
 * @property string $type_id
 * @property array<array-key, mixed> $type_names
 * @property string $person
 * @property \Illuminate\Support\Carbon $creation_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereCreationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase wherePerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereTypeNames($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PeopleCase whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PeopleCase extends Model
{
    public const STATUS_NEW = 1;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'people_cases';

    protected $fillable = [
        'id',
        'status',
        'signature',
        'type_names',
        'type_id',
        'person',
        'inpol_account_id',
        'creation_date',
    ];

    protected $casts = [
        'type_names' => 'array',
        'creation_date' => 'datetime',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(InpolAccount::class, 'inpol_account_id');
    }

    public static function updateOrCreateMany(mixed $data)
    {
        foreach ($data as $item) {
            if ($item['status'] !== self::STATUS_NEW) {
                continue;
            }
            self::updateOrCreate(
                ['id' => $item['id']],
                [
                    'id' => $item['id'],
                    'status' => $item['status'],
                    'person' => $item['person'],
                    'signature' => $item['signature'],
                    'creation_date' => $item['creationDate'],
                    'type_id' => $item['type']['id'],
                    'type_names' => [
                        'polish' => $item['type']['polish'],
                        'english' => $item['type']['english'],
                        'russian' => $item['type']['russian'],
                        'ukrainian' => $item['type']['ukrainian'],
                    ],
                ]
            );
        }
    }
}
