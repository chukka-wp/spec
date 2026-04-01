<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum EventType: string
{
    case MatchStart = 'match_start';
    case SwimOff = 'swim_off';
    case PeriodStart = 'period_start';
    case PeriodEnd = 'period_end';
    case HalftimeStart = 'halftime_start';
    case HalftimeEnd = 'halftime_end';
    case MatchEnd = 'match_end';
    case MatchAbandoned = 'match_abandoned';
    case Goal = 'goal';
    case GoalThrow = 'goal_throw';
    case TwoMeterThrow = 'two_meter_throw';
    case FreeThrow = 'free_throw';
    case NeutralThrow = 'neutral_throw';
    case Shot = 'shot';
    case OrdinaryFoul = 'ordinary_foul';
    case ExclusionFoul = 'exclusion_foul';
    case ExclusionExpiry = 'exclusion_expiry';
    case PenaltyFoul = 'penalty_foul';
    case PenaltyThrowTaken = 'penalty_throw_taken';
    case ViolentActionExclusion = 'violent_action_exclusion';
    case MisconductExclusion = 'misconduct_exclusion';
    case SimultaneousExclusion = 'simultaneous_exclusion';
    case PersonalFoulRecorded = 'personal_foul_recorded';
    case FoulOut = 'foul_out';
    case YellowCard = 'yellow_card';
    case RedCard = 'red_card';
    case TimeoutStart = 'timeout_start';
    case TimeoutEnd = 'timeout_end';
    case RefereeTimeoutStart = 'referee_timeout_start';
    case RefereeTimeoutEnd = 'referee_timeout_end';
    case Substitution = 'substitution';
    case GoalkeeperSubstitution = 'goalkeeper_substitution';
    case PossessionChange = 'possession_change';
    case PossessionClockReset = 'possession_clock_reset';
    case PossessionClockExpiry = 'possession_clock_expiry';
    case ShootoutStart = 'shootout_start';
    case ShootoutShot = 'shootout_shot';
    case ShootoutEnd = 'shootout_end';
    case InjuryStoppage = 'injury_stoppage';
    case VarReviewStart = 'var_review_start';
    case VarReviewEnd = 'var_review_end';
    case CoachChallenge = 'coach_challenge';
    case Correction = 'correction';

    public function label(): string
    {
        return match ($this) {
            self::MatchStart => 'Match Start',
            self::SwimOff => 'Swim Off',
            self::PeriodStart => 'Period Start',
            self::PeriodEnd => 'Period End',
            self::HalftimeStart => 'Halftime Start',
            self::HalftimeEnd => 'Halftime End',
            self::MatchEnd => 'Match End',
            self::MatchAbandoned => 'Match Abandoned',
            self::Goal => 'Goal',
            self::GoalThrow => 'Goal Throw',
            self::TwoMeterThrow => 'Two-Meter Throw',
            self::FreeThrow => 'Free Throw',
            self::NeutralThrow => 'Neutral Throw',
            self::Shot => 'Shot',
            self::OrdinaryFoul => 'Ordinary Foul',
            self::ExclusionFoul => 'Exclusion Foul',
            self::ExclusionExpiry => 'Exclusion Expiry',
            self::PenaltyFoul => 'Penalty Foul',
            self::PenaltyThrowTaken => 'Penalty Throw Taken',
            self::ViolentActionExclusion => 'Violent Action Exclusion',
            self::MisconductExclusion => 'Misconduct Exclusion',
            self::SimultaneousExclusion => 'Simultaneous Exclusion',
            self::PersonalFoulRecorded => 'Personal Foul Recorded',
            self::FoulOut => 'Foul Out',
            self::YellowCard => 'Yellow Card',
            self::RedCard => 'Red Card',
            self::TimeoutStart => 'Timeout Start',
            self::TimeoutEnd => 'Timeout End',
            self::RefereeTimeoutStart => 'Referee Timeout Start',
            self::RefereeTimeoutEnd => 'Referee Timeout End',
            self::Substitution => 'Substitution',
            self::GoalkeeperSubstitution => 'Goalkeeper Substitution',
            self::PossessionChange => 'Possession Change',
            self::PossessionClockReset => 'Possession Clock Reset',
            self::PossessionClockExpiry => 'Possession Clock Expiry',
            self::ShootoutStart => 'Shootout Start',
            self::ShootoutShot => 'Shootout Shot',
            self::ShootoutEnd => 'Shootout End',
            self::InjuryStoppage => 'Injury Stoppage',
            self::VarReviewStart => 'VAR Review Start',
            self::VarReviewEnd => 'VAR Review End',
            self::CoachChallenge => 'Coach Challenge',
            self::Correction => 'Correction',
        };
    }

    public function hasPayload(): bool
    {
        return ! in_array($this, [
            self::MatchStart,
            self::PeriodStart,
            self::PeriodEnd,
            self::HalftimeStart,
            self::HalftimeEnd,
            self::MatchEnd,
            self::MatchAbandoned,
            self::TimeoutEnd,
            self::RefereeTimeoutStart,
            self::RefereeTimeoutEnd,
            self::ShootoutStart,
            self::VarReviewStart,
        ]);
    }

    public function terminatesMatch(): bool
    {
        return in_array($this, [
            self::MatchEnd,
            self::MatchAbandoned,
        ]);
    }
}
