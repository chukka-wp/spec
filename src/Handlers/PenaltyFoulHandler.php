<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

/**
 * No-op: personal foul recording and exclusion are handled by separate events.
 */
final class PenaltyFoulHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        // Personal foul count is updated by PersonalFoulRecorded.
        // Exclusion (if any) is handled by the corresponding exclusion event.
    }
}
