<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Enums\Possession;
use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\Payloads\TimeoutStartPayload;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

final class TimeoutStartHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        /** @var TimeoutStartPayload $payload */
        $side = $state->resolveTeamSide($payload->teamId);

        if ($side === Possession::Home) {
            $state->setHomeTimeoutsRemaining($payload->timeoutsRemainingAfter);
            return;
        }

        if ($side === Possession::Away) {
            $state->setAwayTimeoutsRemaining($payload->timeoutsRemainingAfter);
        }
    }
}
