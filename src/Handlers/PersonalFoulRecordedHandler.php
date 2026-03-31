<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\Payloads\PersonalFoulRecordedPayload;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

final class PersonalFoulRecordedHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        /** @var PersonalFoulRecordedPayload $payload */
        $state->setFoulCount($payload->playerId, $payload->foulCountAfter);
    }
}
