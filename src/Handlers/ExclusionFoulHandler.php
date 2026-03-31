<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Enums\ExclusionType;
use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\ExclusionFoulPayload;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\ValueObjects\ActiveExclusion;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

final class ExclusionFoulHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        /** @var ExclusionFoulPayload $payload */
        $state->addActiveExclusion(new ActiveExclusion(
            playerId: $payload->offendingPlayerId,
            teamId: $payload->offendingTeamId,
            capNumber: $payload->offendingCapNumber,
            remainingSeconds: $payload->exclusionDurationSeconds,
            exclusionType: ExclusionType::Standard,
        ));
    }
}
