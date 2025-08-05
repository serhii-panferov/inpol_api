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

    public function cases(): HasMany
    {
        return $this->hasMany(PeopleCase::class);
    }

    public function cookies(): HasMany
    {
        return $this->hasMany(InpolCookies::class);
    }

    public function applicants(): HasMany
    {
        return $this->hasMany(Applicants::class);
    }
}
