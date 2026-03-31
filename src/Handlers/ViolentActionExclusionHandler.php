<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Enums\ExclusionType;
use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\Payloads\ViolentActionExclusionPayload;
use ChukkaWp\ChukkaSpec\ValueObjects\ActiveExclusion;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

final class ViolentActionExclusionHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        /** @var ViolentActionExclusionPayload $payload */
        $state->addActiveExclusion(new ActiveExclusion(
            playerId: $payload->offendingPlayerId,
            teamId: $payload->offendingTeamId,
            capNumber: $payload->offendingCapNumber,
            remainingSeconds: 0,
            exclusionType: ExclusionType::ViolentAction,
            substituteEligibleAt: $payload->substituteEligibleAfterSeconds,
        ));

        $state->excludePlayerForGame($payload->offendingPlayerId);
    }
}
