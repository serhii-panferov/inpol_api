<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationQueues extends Model
{
    protected $table = 'reservation_queues';

    protected $fillable = [
        'local_id',
        'address',
        'english_name',
        'type_people_case_id',
    ];

    protected $casts = [
        'local_id' => 'string',
    ];

    public function typePeopleCase(): BelongsTo
    {
        return $this->belongsTo(TypesPeopleCase::class, 'type_people_case_id');
    }

    public static function updateOrCreateMany(mixed $data)
    {
        foreach ($data as $typeId => $locations) {
           $caseTypeId = TypesPeopleCase::where(['type_id' => $typeId])
                ->get('id')
                ->first()
                ->toArray()['id'];
            foreach ($locations as $location) {
                self::updateOrCreate(
                    ['local_id' => $location['id']],
                    [
                        'type_people_case_id' => $caseTypeId,
                        'address' => $location['localization'],
                        'english_name' => $location['english'],
                    ]
                );
            }
        }
    }
}
