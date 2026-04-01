<?php

namespace ChukkaWp\ChukkaSpec\Models;

use ChukkaWp\ChukkaSpec\Enums\OfficialRole;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchOfficial extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'role' => OfficialRole::class,
        ];
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(config('chukka-spec.models.match'));
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(config('chukka-spec.models.team'));
    }
}
