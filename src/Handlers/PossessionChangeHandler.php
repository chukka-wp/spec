<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\Payloads\PossessionChangePayload;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

/**
 * Sets the current possession based on the team that gained the ball.
 *
 * Requires that GameStateBuilder has been configured with home/away team IDs
 * via setHomeTeamId/setAwayTeamId so that resolveTeamSide() can map the
 * payload's team UUID to a Possession enum value.
 */
final class PossessionChangeHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        /** @var PossessionChangePayload $payload */
        $state->setPossession($state->resolveTeamSide($payload->teamId));
    }
}
