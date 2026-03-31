<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Enums\ExclusionType;
use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\FoulOutPayload;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\ValueObjects\ActiveExclusion;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

final class FoulOutHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        /** @var FoulOutPayload $payload */
        if ($payload->enforced) {
            $state->excludePlayerForGame($payload->playerId);
        }

        $state->addActiveExclusion(new ActiveExclusion(
            playerId: $payload->playerId,
            teamId: $payload->teamId,
            capNumber: $payload->capNumber,
            remainingSeconds: 0,
            exclusionType: ExclusionType::ForGame,
        ));
    }
}
