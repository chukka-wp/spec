<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects;

use ChukkaWp\ChukkaSpec\Enums\EventType;
use ChukkaWp\ChukkaSpec\Enums\GameStatus;
use ChukkaWp\ChukkaSpec\Enums\Possession;
use ChukkaWp\ChukkaSpec\Enums\PossessionClockMode;

final readonly class GameState
{
    /**
     * @param array<int, ActiveExclusion> $activeExclusions
     * @param array<string, int> $playerFoulCounts
     * @param array<int, string> $playersExcludedForGame
     */
    public function __construct(
        public string $matchId,
        public GameStatus $status,
        public int $currentPeriod,
        public int $periodClockSeconds,
        public int $homeScore,
        public int $awayScore,
        public Possession $possession,
        public ?int $possessionClockSeconds,
        public ?PossessionClockMode $possessionClockMode,
        public int $homeTimeoutsRemaining,
        public int $awayTimeoutsRemaining,
        public array $activeExclusions,
        public array $playerFoulCounts,
        public array $playersExcludedForGame,
        public ?ShootoutState $shootoutState,
        public ?int $lastEventSequence = null,
        public ?EventType $lastEventType = null,
        public bool $runningTime = false,
        public bool $possessionClockEnabled = true,
    ) {}

    public function toArray(): array
    {
        return [
            'match_id' => $this->matchId,
            'status' => $this->status->value,
            'current_period' => $this->currentPeriod,
            'period_clock_seconds' => $this->periodClockSeconds,
            'home_score' => $this->homeScore,
            'away_score' => $this->awayScore,
            'possession' => $this->possession->value,
            'possession_clock_seconds' => $this->possessionClockSeconds,
            'possession_clock_mode' => $this->possessionClockMode?->value,
            'home_timeouts_remaining' => $this->homeTimeoutsRemaining,
            'away_timeouts_remaining' => $this->awayTimeoutsRemaining,
            'active_exclusions' => array_map(fn (ActiveExclusion $e) => $e->toArray(), $this->activeExclusions),
            'player_foul_counts' => $this->playerFoulCounts,
            'players_excluded_for_game' => $this->playersExcludedForGame,
            'shootout_state' => $this->shootoutState?->toArray(),
            'last_event_sequence' => $this->lastEventSequence,
            'last_event_type' => $this->lastEventType?->value,
            'running_time' => $this->runningTime,
            'possession_clock_enabled' => $this->possessionClockEnabled,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            matchId: $data['match_id'],
            status: GameStatus::from($data['status']),
            currentPeriod: $data['current_period'],
            periodClockSeconds: $data['period_clock_seconds'],
            homeScore: $data['home_score'],
            awayScore: $data['away_score'],
            possession: Possession::from($data['possession']),
            possessionClockSeconds: $data['possession_clock_seconds'] ?? null,
            possessionClockMode: isset($data['possession_clock_mode']) ? PossessionClockMode::from($data['possession_clock_mode']) : null,
            homeTimeoutsRemaining: $data['home_timeouts_remaining'],
            awayTimeoutsRemaining: $data['away_timeouts_remaining'],
            activeExclusions: array_map(fn (array $e) => ActiveExclusion::fromArray($e), $data['active_exclusions'] ?? []),
            playerFoulCounts: $data['player_foul_counts'] ?? [],
            playersExcludedForGame: $data['players_excluded_for_game'] ?? [],
            shootoutState: isset($data['shootout_state']) ? ShootoutState::fromArray($data['shootout_state']) : null,
            lastEventSequence: $data['last_event_sequence'] ?? null,
            lastEventType: isset($data['last_event_type']) ? EventType::from($data['last_event_type']) : null,
            runningTime: $data['running_time'] ?? false,
            possessionClockEnabled: $data['possession_clock_enabled'] ?? true,
        );
    }
}
