<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationQueues extends Model
{
    protected $table = 'reservation_queues';

    protected $fillable = [
        'local_id',
        'address',
        'english_name',
    ];

    protected $casts = [
        'local_id' => 'string',
    ];

    public static function updateOrCreateMany(mixed $data)
    {
        foreach ($data as $item) {
            self::updateOrCreate(
                ['local_id' => $item['local_id']],
                [
                    'address' => $item['localization'],
                    'english_name' => $item['english'],
                ]
            );
        }
    }
}
