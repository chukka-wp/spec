<?php

namespace ChukkaWp\ChukkaSpec\Payloads;

use ChukkaWp\ChukkaSpec\Enums\EventType;

class PayloadFactory
{
    public static function make(EventType $type, array $data): Payload
    {
        $class = match ($type) {
            EventType::MatchStart,
            EventType::PeriodStart,
            EventType::PeriodEnd,
            EventType::HalftimeStart,
            EventType::HalftimeEnd,
            EventType::MatchEnd,
            EventType::MatchAbandoned,
            EventType::TimeoutEnd,
            EventType::RefereeTimeoutStart,
            EventType::RefereeTimeoutEnd,
            EventType::ShootoutStart,
            EventType::VarReviewStart => EmptyPayload::class,

            EventType::SwimOff => SwimOffPayload::class,
            EventType::Goal => GoalPayload::class,
            EventType::GoalThrow => GoalThrowPayload::class,
            EventType::CornerThrow => CornerThrowPayload::class,
            EventType::NeutralThrow => NeutralThrowPayload::class,
            EventType::OrdinaryFoul => OrdinaryFoulPayload::class,
            EventType::ExclusionFoul => ExclusionFoulPayload::class,
            EventType::ExclusionExpiry => ExclusionExpiryPayload::class,
            EventType::PenaltyFoul => PenaltyFoulPayload::class,
            EventType::PenaltyThrowTaken => PenaltyThrowTakenPayload::class,
            EventType::ViolentActionExclusion => ViolentActionExclusionPayload::class,
            EventType::MisconductExclusion => MisconductExclusionPayload::class,
            EventType::SimultaneousExclusion => SimultaneousExclusionPayload::class,
            EventType::PersonalFoulRecorded => PersonalFoulRecordedPayload::class,
            EventType::FoulOut => FoulOutPayload::class,
            EventType::YellowCard => YellowCardPayload::class,
            EventType::RedCard => RedCardPayload::class,
            EventType::TimeoutStart => TimeoutStartPayload::class,
            EventType::Substitution => SubstitutionPayload::class,
            EventType::GoalkeeperSubstitution => GoalkeeperSubstitutionPayload::class,
            EventType::PossessionChange => PossessionChangePayload::class,
            EventType::PossessionClockReset => PossessionClockResetPayload::class,
            EventType::PossessionClockExpiry => PossessionClockExpiryPayload::class,
            EventType::ShootoutShot => ShootoutShotPayload::class,
            EventType::ShootoutEnd => ShootoutEndPayload::class,
            EventType::InjuryStoppage => InjuryStoppagePayload::class,
            EventType::VarReviewEnd => VarReviewEndPayload::class,
            EventType::CoachChallenge => CoachChallengePayload::class,
            EventType::Correction => CorrectionPayload::class,
        };

        return $class::fromArray($data);
    }
}
