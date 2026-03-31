<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Enums\PenaltyOutcome;
use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\Payloads\PenaltyThrowTakenPayload;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

final class PenaltyThrowTakenHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        /** @var PenaltyThrowTakenPayload $payload */
        if ($payload->outcome !== PenaltyOutcome::Goal->value) {
            return;
        }

        if ($payload->homeScoreAfter === null || $payload->awayScoreAfter === null) {
            return;
        }

        $state->setHomeScore($payload->homeScoreAfter);
        $state->setAwayScore($payload->awayScoreAfter);
    }
}
