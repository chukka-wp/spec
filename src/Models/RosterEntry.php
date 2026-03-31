<?php

namespace ChukkaWp\ChukkaSpec\Models;

use ChukkaWp\ChukkaSpec\Enums\RosterRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RosterEntry extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_starting' => 'boolean',
            'role' => RosterRole::class,
        ];
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(config('chukka-spec.models.match'));
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(config('chukka-spec.models.player'));
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(config('chukka-spec.models.team'));
    }
}
