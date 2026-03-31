<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

/**
 * No-op: substitutions affect who is in the water, which is not part of
 * the computed GameState fields we track.
 */
final class SubstitutionHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        // Substitutions do not affect computed game state.
    }
}
