<?php

namespace ChukkaWp\ChukkaSpec\ValueObjects;

use ChukkaWp\ChukkaSpec\Enums\EventType;
use ChukkaWp\ChukkaSpec\Enums\ExclusionType;
use ChukkaWp\ChukkaSpec\Enums\GameStatus;
use ChukkaWp\ChukkaSpec\Enums\Possession;
use ChukkaWp\ChukkaSpec\Enums\PossessionClockMode;
use ChukkaWp\ChukkaSpec\Models\RuleSet;

final class GameStateBuilder
{
    private string $matchId = '';
    private string $homeTeamId = '';
    private string $awayTeamId = '';
    private GameStatus $status;
    private int $currentPeriod = 1;
    private int $periodClockSeconds;
    private int $homeScore = 0;
    private int $awayScore = 0;
    private Possession $possession;
    private ?int $possessionClockSeconds = null;
    private ?PossessionClockMode $possessionClockMode = null;
    private int $homeTimeoutsRemaining;
    private int $awayTimeoutsRemaining;

    /** @var array<int, ActiveExclusion> */
    private array $activeExclusions = [];

    /** @var array<string, int> */
    private array $playerFoulCounts = [];

    /** @var array<int, string> */
    private array $playersExcludedForGame = [];

    private ?ShootoutState $shootoutState = null;

    private ?int $lastEventSequence = null;
    private ?EventType $lastEventType = null;
    private bool $runningTime = false;
    private bool $possessionClockEnabled = true;

    private function __construct() {}

    public static function fromRuleSet(RuleSet $ruleSet, string $matchId = ''): self
    {
        $builder = new self;

        $builder->matchId = $matchId;
        $builder->status = GameStatus::NotStarted;
        $builder->periodClockSeconds = $ruleSet->period_duration_seconds;
        $builder->possession = Possession::None;
        $builder->homeTimeoutsRemaining = $ruleSet->timeouts_per_team;
        $builder->awayTimeoutsRemaining = $ruleSet->timeouts_per_team;

        $builder->runningTime = $ruleSet->running_time;
        $builder->possessionClockEnabled = $ruleSet->possession_clock_enabled;

        if ($ruleSet->possession_clock_enabled) {
            $builder->possessionClockSeconds = $ruleSet->possession_time_seconds;
            $builder->possessionClockMode = PossessionClockMode::Standard;
        }

        return $builder;
    }

    public function build(): GameState
    {
        return new GameState(
            matchId: $this->matchId,
            status: $this->status,
            currentPeriod: $this->currentPeriod,
            periodClockSeconds: $this->periodClockSeconds,
            homeScore: $this->homeScore,
            awayScore: $this->awayScore,
            possession: $this->possession,
            possessionClockSeconds: $this->possessionClockSeconds,
            possessionClockMode: $this->possessionClockMode,
            homeTimeoutsRemaining: $this->homeTimeoutsRemaining,
            awayTimeoutsRemaining: $this->awayTimeoutsRemaining,
            activeExclusions: $this->activeExclusions,
            playerFoulCounts: $this->playerFoulCounts,
            playersExcludedForGame: $this->playersExcludedForGame,
            shootoutState: $this->shootoutState,
            lastEventSequence: $this->lastEventSequence,
            lastEventType: $this->lastEventType,
            runningTime: $this->runningTime,
            possessionClockEnabled: $this->possessionClockEnabled,
        );
    }

    // --- Getters ---

    public function getMatchId(): string
    {
        return $this->matchId;
    }

    public function getHomeTeamId(): string
    {
        return $this->homeTeamId;
    }

    public function getAwayTeamId(): string
    {
        return $this->awayTeamId;
    }

    public function setHomeTeamId(string $teamId): self
    {
        $this->homeTeamId = $teamId;

        return $this;
    }

    public function setAwayTeamId(string $teamId): self
    {
        $this->awayTeamId = $teamId;

        return $this;
    }

    public function resolveTeamSide(string $teamId): Possession
    {
        if ($teamId === $this->homeTeamId) {
            return Possession::Home;
        }

        if ($teamId === $this->awayTeamId) {
            return Possession::Away;
        }

        return Possession::None;
    }

    public function getStatus(): GameStatus
    {
        return $this->status;
    }

    public function getCurrentPeriod(): int
    {
        return $this->currentPeriod;
    }

    public function getPeriodClockSeconds(): int
    {
        return $this->periodClockSeconds;
    }

    public function getHomeScore(): int
    {
        return $this->homeScore;
    }

    public function getAwayScore(): int
    {
        return $this->awayScore;
    }

    public function getPossession(): Possession
    {
        return $this->possession;
    }

    public function getPossessionClockSeconds(): ?int
    {
        return $this->possessionClockSeconds;
    }

    public function getPossessionClockMode(): ?PossessionClockMode
    {
        return $this->possessionClockMode;
    }

    public function getHomeTimeoutsRemaining(): int
    {
        return $this->homeTimeoutsRemaining;
    }

    public function getAwayTimeoutsRemaining(): int
    {
        return $this->awayTimeoutsRemaining;
    }

    /** @return array<int, ActiveExclusion> */
    public function getActiveExclusions(): array
    {
        return $this->activeExclusions;
    }

    public function getFoulCount(string $playerId): int
    {
        return $this->playerFoulCounts[$playerId] ?? 0;
    }

    /** @return array<string, int> */
    public function getPlayerFoulCounts(): array
    {
        return $this->playerFoulCounts;
    }

    /** @return array<int, string> */
    public function getPlayersExcludedForGame(): array
    {
        return $this->playersExcludedForGame;
    }

    public function getShootoutState(): ?ShootoutState
    {
        return $this->shootoutState;
    }

    // --- Setters ---

    public function setStatus(GameStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setCurrentPeriod(int $period): self
    {
        $this->currentPeriod = $period;

        return $this;
    }

    public function setPeriodClockSeconds(int $seconds): self
    {
        $this->periodClockSeconds = $seconds;

        return $this;
    }

    public function setHomeScore(int $score): self
    {
        $this->homeScore = $score;

        return $this;
    }

    public function setAwayScore(int $score): self
    {
        $this->awayScore = $score;

        return $this;
    }

    public function setPossession(Possession $possession): self
    {
        $this->possession = $possession;

        return $this;
    }

    public function setPossessionClockSeconds(?int $seconds): self
    {
        $this->possessionClockSeconds = $seconds;

        return $this;
    }

    public function setPossessionClockMode(?PossessionClockMode $mode): self
    {
        $this->possessionClockMode = $mode;

        return $this;
    }

    public function setHomeTimeoutsRemaining(int $count): self
    {
        $this->homeTimeoutsRemaining = $count;

        return $this;
    }

    public function setAwayTimeoutsRemaining(int $count): self
    {
        $this->awayTimeoutsRemaining = $count;

        return $this;
    }

    public function decrementHomeTimeouts(): self
    {
        $this->homeTimeoutsRemaining = max(0, $this->homeTimeoutsRemaining - 1);

        return $this;
    }

    public function decrementAwayTimeouts(): self
    {
        $this->awayTimeoutsRemaining = max(0, $this->awayTimeoutsRemaining - 1);

        return $this;
    }

    public function addActiveExclusion(ActiveExclusion $exclusion): self
    {
        $this->activeExclusions[] = $exclusion;

        return $this;
    }

    public function removeActiveExclusion(string $playerId): self
    {
        $this->activeExclusions = array_values(
            array_filter($this->activeExclusions, fn (ActiveExclusion $e) => $e->playerId !== $playerId)
        );

        return $this;
    }

    public function clearExclusionsForTeam(string $teamId): self
    {
        $this->activeExclusions = array_values(
            array_filter($this->activeExclusions, fn (ActiveExclusion $e) => $e->teamId !== $teamId)
        );

        return $this;
    }

    public function clearAllActiveExclusions(): self
    {
        $this->activeExclusions = [];

        return $this;
    }

    public function setFoulCount(string $playerId, int $count): self
    {
        $this->playerFoulCounts[$playerId] = $count;

        return $this;
    }

    public function incrementFoulCount(string $playerId): self
    {
        $this->playerFoulCounts[$playerId] = ($this->playerFoulCounts[$playerId] ?? 0) + 1;

        return $this;
    }

    public function excludePlayerForGame(string $playerId): self
    {
        if (! in_array($playerId, $this->playersExcludedForGame)) {
            $this->playersExcludedForGame[] = $playerId;
        }

        return $this;
    }

    public function setShootoutState(?ShootoutState $state): self
    {
        $this->shootoutState = $state;

        return $this;
    }

    public function initShootout(Possession $firstShooter): self
    {
        $this->shootoutState = new ShootoutState(
            homeScore: 0,
            awayScore: 0,
            currentRound: 1,
            shots: [],
            nextShootingTeam: $firstShooter,
        );

        return $this;
    }

    public function addShootoutShot(ShootoutShot $shot, int $homeScoreAfter, int $awayScoreAfter, Possession $nextTeam): self
    {
        if (! $this->shootoutState) {
            return $this;
        }

        $this->shootoutState = new ShootoutState(
            homeScore: $homeScoreAfter,
            awayScore: $awayScoreAfter,
            currentRound: $shot->round,
            shots: [...$this->shootoutState->shots, $shot],
            nextShootingTeam: $nextTeam,
        );

        return $this;
    }

    public function setLastEvent(int $sequence, EventType $type): self
    {
        $this->lastEventSequence = $sequence;
        $this->lastEventType = $type;

        return $this;
    }
}
