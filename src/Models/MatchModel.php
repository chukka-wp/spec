<?php

namespace ChukkaWp\ChukkaSpec\Models;

use ChukkaWp\ChukkaSpec\Enums\MatchStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatchModel extends Model
{
    use HasUuids;

    protected $table = 'matches';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'status' => MatchStatus::class,
        ];
    }

    public function ruleSet(): BelongsTo
    {
        return $this->belongsTo(config('chukka-spec.models.rule_set'));
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(config('chukka-spec.models.team'), 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(config('chukka-spec.models.team'), 'away_team_id');
    }

    public function rosterEntries(): HasMany
    {
        return $this->hasMany(config('chukka-spec.models.roster_entry'), 'match_id');
    }

    public function officials(): HasMany
    {
        return $this->hasMany(config('chukka-spec.models.match_official'), 'match_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(config('chukka-spec.models.event'), 'match_id')->orderBy('sequence');
    }
}
