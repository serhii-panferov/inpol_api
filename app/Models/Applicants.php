<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Applicants extends Model
{
    protected $table = 'applicants';

    protected $fillable = [
        'name',
        'last_name',
        'birth_date',
        'inpol_account_id',
        'person_id',
    ];

    protected $casts = [
        'name' => 'string',
        'last_name' => 'string',
        'birth_date' => 'date',
        'inpol_account_id' => 'integer',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(InpolAccount::class, 'inpol_account_id');
    }
}
