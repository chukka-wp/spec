<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Enums\ShootoutShotOutcome;
use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\Payloads\ShootoutShotPayload;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;
use ChukkaWp\ChukkaSpec\ValueObjects\ShootoutShot;

final class ShootoutShotHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        /** @var ShootoutShotPayload $payload */
        $shot = new ShootoutShot(
            teamId: $payload->teamId,
            playerId: $payload->playerId,
            capNumber: $payload->capNumber,
            round: $payload->round,
            outcome: ShootoutShotOutcome::from($payload->outcome),
        );

        $nextTeam = $state->resolveTeamSide($payload->teamId)->opposite();

        $state->addShootoutShot(
            $shot,
            $payload->homeShootoutScoreAfter,
            $payload->awayShootoutScoreAfter,
            $nextTeam,
        );
    }
}
