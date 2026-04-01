<?php

namespace ChukkaWp\ChukkaSpec\Services;

use ChukkaWp\ChukkaSpec\Enums\PossessionClockMode;
use ChukkaWp\ChukkaSpec\Enums\PossessionClockResetReason;
use ChukkaWp\ChukkaSpec\Models\RuleSet;

class PossessionClockCalculator
{
    /**
     * @return array{
     *     seconds: int,
     *     mode: PossessionClockMode
     * }
     */
    public function calculate(
        PossessionClockResetReason $reason,
        int $currentClockSeconds,
        RuleSet $ruleSet,
    ): array {
        if (! $ruleSet->possession_clock_enabled) {
            return [
                'seconds' => 0,
                'mode' => PossessionClockMode::Standard,
            ];
        }

        $standard = $ruleSet->possession_time_seconds;
        $reduced = $ruleSet->second_possession_time_seconds;

        return match ($reason) {
            PossessionClockResetReason::NewPossession,
            PossessionClockResetReason::GoalThrow,
            PossessionClockResetReason::NeutralThrow => [
                'seconds' => $standard,
                'mode' => PossessionClockMode::Standard,
            ],

            PossessionClockResetReason::ShotReboundAttacking,
            PossessionClockResetReason::TwoMeterThrow,
            PossessionClockResetReason::PenaltyThrowNoChange => [
                'seconds' => $reduced,
                'mode' => PossessionClockMode::Reduced,
            ],

            PossessionClockResetReason::ExclusionFoul => [
                'seconds' => $currentClockSeconds > $reduced ? $currentClockSeconds : $reduced,
                'mode' => $currentClockSeconds > $reduced ? PossessionClockMode::Standard : PossessionClockMode::Reduced,
            ],

            PossessionClockResetReason::TimeoutEnd => [
                'seconds' => $currentClockSeconds,
                'mode' => $currentClockSeconds > $reduced ? PossessionClockMode::Standard : PossessionClockMode::Reduced,
            ],
        };
    }
}
