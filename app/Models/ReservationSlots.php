<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationSlots extends Model
{
    protected $table = 'reservation_slots';

    protected $fillable = [
        'slot_id',
        'date',
        'count',
        'type_people_case_id',
    ];

    protected $casts = [
        'slot_id' => 'string',
    ];

    public function typePeopleCase(): BelongsTo
    {
        return $this->belongsTo(TypesPeopleCase::class, 'type_people_case_id');
    }

    public static function updateOrCreateMany(mixed $data, $peopleCaseType)
    {
        foreach ($data as $slots) {
            $caseTypeId = TypesPeopleCase::where(['type_id' => $peopleCaseType])
                ->get('id')
                ->first()
                ->toArray()['id'];
            foreach ($slots as $slot) {
                self::updateOrCreate(
                    ['slot_id' => $slot['id']],
                    [
                        'type_people_case_id' => $caseTypeId,
                        'date' => $slot['date'],
                        'count' => $slot['count'],
                    ]
                );
            }
        }
    }
}
