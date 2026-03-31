<?php

namespace ChukkaWp\ChukkaSpec\Services;

use ChukkaWp\ChukkaSpec\Handlers\CorrectionPreFilter;
use ChukkaWp\ChukkaSpec\Handlers\EventHandlerRegistry;
use ChukkaWp\ChukkaSpec\Models\MatchModel;
use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\PayloadFactory;
use ChukkaWp\ChukkaSpec\ValueObjects\GameState;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;
use Illuminate\Support\Collection;

class GameStateService
{
    public function compute(
        Collection $events,
        RuleSet $ruleSet,
        string $matchId = '',
        string $homeTeamId = '',
        string $awayTeamId = '',
    ): GameState {
        $preFilter = new CorrectionPreFilter;
        $result = $preFilter->filter($events);
        $effectiveEvents = $result['events'];

        $builder = GameStateBuilder::fromRuleSet($ruleSet, $matchId);
        $builder->setHomeTeamId($homeTeamId);
        $builder->setAwayTeamId($awayTeamId);

        foreach ($effectiveEvents as $event) {
            $handler = EventHandlerRegistry::resolve($event->type->value);
            $payload = PayloadFactory::make($event->type, $event->payload ?? []);

            $handler($builder, $payload, $ruleSet);
            $builder->setLastEvent($event->sequence, $event->type);
        }

        return $builder->build();
    }

    public function computeForMatch(MatchModel $match): GameState
    {
        $match->loadMissing(['events', 'ruleSet']);

        return $this->compute(
            events: $match->events,
            ruleSet: $match->ruleSet,
            matchId: $match->id,
            homeTeamId: $match->home_team_id,
            awayTeamId: $match->away_team_id,
        );
    }
}
