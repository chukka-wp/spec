<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

/**
 * No-op: PeriodStart handles the transition back to InProgress.
 */
final class HalftimeEndHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        // PeriodStart handles the transition.
    }
}
