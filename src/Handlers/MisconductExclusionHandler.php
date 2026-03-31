<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Enums\ExclusionType;
use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\MisconductExclusionPayload;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\ValueObjects\ActiveExclusion;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

final class MisconductExclusionHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        /** @var MisconductExclusionPayload $payload */
        $state->excludePlayerForGame($payload->offendingPlayerId);

        $state->addActiveExclusion(new ActiveExclusion(
            playerId: $payload->offendingPlayerId,
            teamId: $payload->offendingTeamId,
            capNumber: $payload->offendingCapNumber,
            remainingSeconds: 0,
            exclusionType: ExclusionType::ForGame,
        ));
    }
}
