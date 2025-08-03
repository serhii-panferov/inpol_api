<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypesPeopleCase extends Model
{
    protected $table = 'types_people_cases';

    protected $fillable = [
        'type_id',
        'name',
    ];

    protected $casts = [
        'type_id' => 'string',
        'name' => 'string',
    ];

    public function reservationQueues(): HasMany
    {
        return $this->hasMany(ReservationQueues::class, 'type_people_case_id');
    }
}
