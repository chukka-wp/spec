<?php

namespace ChukkaWp\ChukkaSpec\Enums;

enum OfficialRole: string
{
    case Referee = 'referee';
    case GoalJudge = 'goal_judge';
    case Secretary = 'secretary';
    case Timekeeper = 'timekeeper';
    case HeadCoach = 'head_coach';
    case AssistantCoach = 'assistant_coach';
    case TeamManager = 'team_manager';
    case VarOfficial = 'var_official';

    public function label(): string
    {
        return match ($this) {
            self::Referee => 'Referee',
            self::GoalJudge => 'Goal Judge',
            self::Secretary => 'Secretary',
            self::Timekeeper => 'Timekeeper',
            self::HeadCoach => 'Head Coach',
            self::AssistantCoach => 'Assistant Coach',
            self::TeamManager => 'Team Manager',
            self::VarOfficial => 'VAR Official',
        };
    }

    public function isTeamRole(): bool
    {
        return match ($this) {
            self::HeadCoach, self::AssistantCoach, self::TeamManager => true,
            default => false,
        };
    }
}
