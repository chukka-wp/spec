<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Enums\PossessionClockMode;
use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\Payloads\PossessionClockResetPayload;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

final class PossessionClockResetHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        if (! $ruleSet->possession_clock_enabled) {
            return;
        }

        /** @var PossessionClockResetPayload $payload */
        $state->setPossessionClockSeconds($payload->newValueSeconds);
        $state->setPossessionClockMode(PossessionClockMode::from($payload->mode));
    }
}
