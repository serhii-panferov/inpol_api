<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestLogs extends Model
{
    protected $table = 'request_logs';

    protected $fillable = [
        'request_type',
        'status',
        'url',
        'request_body',
        'request_headers',
        'response_body',
        'response_headers',
        'cookies',
        'duration_ms',
        'created_at',
    ];

    protected $casts = [
        'request_headers' => 'array',
        'response_headers' => 'array',
    ];
}
