<?php

namespace ChukkaWp\ChukkaSpec\Handlers;

use ChukkaWp\ChukkaSpec\Enums\EventType;
use ChukkaWp\ChukkaSpec\Exceptions\InvalidEventException;

class EventHandlerRegistry
{
    /** @return array<string, class-string<EventHandlerInterface>> */
    public static function handlers(): array
    {
        return [
            EventType::MatchStart->value => MatchStartHandler::class,
            EventType::SwimOff->value => SwimOffHandler::class,
            EventType::PeriodStart->value => PeriodStartHandler::class,
            EventType::PeriodEnd->value => PeriodEndHandler::class,
            EventType::HalftimeStart->value => HalftimeStartHandler::class,
            EventType::HalftimeEnd->value => HalftimeEndHandler::class,
            EventType::MatchEnd->value => MatchEndHandler::class,
            EventType::MatchAbandoned->value => MatchAbandonedHandler::class,
            EventType::Goal->value => GoalHandler::class,
            EventType::GoalThrow->value => GoalThrowHandler::class,
            EventType::TwoMeterThrow->value => TwoMeterThrowHandler::class,
            EventType::FreeThrow->value => FreeThrowHandler::class,
            EventType::NeutralThrow->value => NeutralThrowHandler::class,
            EventType::Shot->value => ShotHandler::class,
            EventType::OrdinaryFoul->value => OrdinaryFoulHandler::class,
            EventType::ExclusionFoul->value => ExclusionFoulHandler::class,
            EventType::ExclusionExpiry->value => ExclusionExpiryHandler::class,
            EventType::PenaltyFoul->value => PenaltyFoulHandler::class,
            EventType::PenaltyThrowTaken->value => PenaltyThrowTakenHandler::class,
            EventType::ViolentActionExclusion->value => ViolentActionExclusionHandler::class,
            EventType::MisconductExclusion->value => MisconductExclusionHandler::class,
            EventType::SimultaneousExclusion->value => SimultaneousExclusionHandler::class,
            EventType::PersonalFoulRecorded->value => PersonalFoulRecordedHandler::class,
            EventType::FoulOut->value => FoulOutHandler::class,
            EventType::YellowCard->value => YellowCardHandler::class,
            EventType::RedCard->value => RedCardHandler::class,
            EventType::TimeoutStart->value => TimeoutStartHandler::class,
            EventType::TimeoutEnd->value => TimeoutEndHandler::class,
            EventType::RefereeTimeoutStart->value => RefereeTimeoutStartHandler::class,
            EventType::RefereeTimeoutEnd->value => RefereeTimeoutEndHandler::class,
            EventType::Substitution->value => SubstitutionHandler::class,
            EventType::GoalkeeperSubstitution->value => GoalkeeperSubstitutionHandler::class,
            EventType::PossessionChange->value => PossessionChangeHandler::class,
            EventType::PossessionClockReset->value => PossessionClockResetHandler::class,
            EventType::PossessionClockExpiry->value => PossessionClockExpiryHandler::class,
            EventType::ShootoutStart->value => ShootoutStartHandler::class,
            EventType::ShootoutShot->value => ShootoutShotHandler::class,
            EventType::ShootoutEnd->value => ShootoutEndHandler::class,
            EventType::InjuryStoppage->value => InjuryStoppageHandler::class,
            EventType::VarReviewStart->value => VarReviewStartHandler::class,
            EventType::VarReviewEnd->value => VarReviewEndHandler::class,
            EventType::CoachChallenge->value => CoachChallengeHandler::class,
        ];
    }

    public static function resolve(string $eventType): EventHandlerInterface
    {
        $handlers = self::handlers();

        if (! isset($handlers[$eventType])) {
            throw new InvalidEventException("No handler registered for event type: {$eventType}");
        }

        return new $handlers[$eventType];
    }
}
