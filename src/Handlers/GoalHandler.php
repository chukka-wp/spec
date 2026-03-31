<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\GoalPayload;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

final class GoalHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        /** @var GoalPayload $payload */
        $state->setHomeScore($payload->homeScoreAfter);
        $state->setAwayScore($payload->awayScoreAfter);
    }
}
