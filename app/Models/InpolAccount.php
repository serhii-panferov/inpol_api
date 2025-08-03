<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InpolAccount extends Model
{
    protected $fillable = ['email'];

    public function tokens(): HasMany
    {
        return $this->hasMany(InpolToken::class);
    }
}
