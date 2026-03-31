<?php

namespace ChukkaWp\ChukkaSpec\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamMembership extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'joined_at' => 'date',
            'left_at' => 'date',
        ];
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
