<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Enums\ExclusionType;
use ChukkaWp\ChukkaSpec\Enums\Possession;
use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\Payloads\SimultaneousExclusionPayload;
use ChukkaWp\ChukkaSpec\ValueObjects\ActiveExclusion;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

final class SimultaneousExclusionHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        /** @var SimultaneousExclusionPayload $payload */
        $homeTeamId = $state->resolveTeamSide($payload->homePlayerId) === Possession::None
            ? 'home'
            : $state->getHomeTeamId();

        $awayTeamId = $state->resolveTeamSide($payload->awayPlayerId) === Possession::None
            ? 'away'
            : $state->getAwayTeamId();

        $state->addActiveExclusion(new ActiveExclusion(
            playerId: $payload->homePlayerId,
            teamId: $homeTeamId,
            capNumber: $payload->homeCapNumber,
            remainingSeconds: $payload->exclusionDurationSeconds,
            exclusionType: ExclusionType::Standard,
        ));

        $state->addActiveExclusion(new ActiveExclusion(
            playerId: $payload->awayPlayerId,
            teamId: $awayTeamId,
            capNumber: $payload->awayCapNumber,
            remainingSeconds: $payload->exclusionDurationSeconds,
            exclusionType: ExclusionType::Standard,
        ));
    }
}
