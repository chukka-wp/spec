<?php

namespace ChukkaWp\ChukkaSpec\Models;

use ChukkaWp\ChukkaSpec\Enums\CapNumberScheme;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RuleSet extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_bundled' => 'boolean',
            'running_time' => 'boolean',
            'possession_clock_enabled' => 'boolean',
            'foul_limit_enforced' => 'boolean',
            'cap_number_scheme' => CapNumberScheme::class,
        ];
    }

    public function matches(): HasMany
    {
        return $this->hasMany(config('chukka-spec.models.match'));
    }
}
