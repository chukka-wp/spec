<?php

namespace ChukkaWp\ChukkaSpec\Models;

use ChukkaWp\ChukkaSpec\Enums\Gender;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
        ];
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(config('chukka-spec.models.club'));
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(
            config('chukka-spec.models.player'),
            'team_memberships',
        )->withPivot('joined_at', 'left_at')->withTimestamps();
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(config('chukka-spec.models.team_membership'));
    }

    public function homeMatches(): HasMany
    {
        return $this->hasMany(config('chukka-spec.models.match'), 'home_team_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(config('chukka-spec.models.match'), 'away_team_id');
    }
}
