<?php

namespace ChukkaWp\ChukkaSpec\Models;

use ChukkaWp\ChukkaSpec\Enums\Gender;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'gender' => Gender::class,
            'is_goalkeeper' => 'boolean',
        ];
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(config('chukka-spec.models.club'));
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(
            config('chukka-spec.models.team'),
            'team_memberships',
        )->withPivot('joined_at', 'left_at')->withTimestamps();
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(config('chukka-spec.models.team_membership'));
    }

    public function rosterEntries(): HasMany
    {
        return $this->hasMany(config('chukka-spec.models.roster_entry'));
    }
}
