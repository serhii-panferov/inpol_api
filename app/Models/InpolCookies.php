<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InpolCookies extends Model
{
    protected $fillable = [
        'cookie',
        'inpol_account_id',
    ];

    protected $casts = [
        'cookie' => 'string',
        'inpol_account_id' => 'integer',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(InpolAccount::class);
    }
}
