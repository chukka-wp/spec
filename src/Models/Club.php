<?php

namespace ChukkaWp\ChukkaSpec\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Club extends Model
{
    use HasUuids;

    protected $guarded = [];

    public function teams(): HasMany
    {
        return $this->hasMany(config('chukka-spec.models.team'));
    }

    public function players(): HasMany
    {
        return $this->hasMany(config('chukka-spec.models.player'));
    }
}
