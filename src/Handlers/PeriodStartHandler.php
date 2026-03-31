<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Enums\GameStatus;
use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Payloads\Payload;
use ChukkaWp\ChukkaSpec\ValueObjects\GameStateBuilder;

final class PeriodStartHandler implements EventHandlerInterface
{
    public function __invoke(GameStateBuilder $state, Payload $payload, RuleSet $ruleSet): void
    {
        $previousStatus = $state->getStatus();

        if ($previousStatus === GameStatus::PeriodBreak || $previousStatus === GameStatus::Halftime) {
            $state->setCurrentPeriod($state->getCurrentPeriod() + 1);
        }

        $state->setStatus(GameStatus::InProgress);

        $isOvertime = $state->getCurrentPeriod() > $ruleSet->periods;

        $clockSeconds = $isOvertime
            ? $ruleSet->overtime_period_duration_seconds
            : $ruleSet->period_duration_seconds;

        $state->setPeriodClockSeconds($clockSeconds);
    }
}
