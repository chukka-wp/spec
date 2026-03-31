<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

/**
 * No-op: referee timeout start carries no state-changing data.
 */
final class RefereeTimeoutStartHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        // Empty payload — nothing to update.
    }
}
