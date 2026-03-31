<?php

use ChukkaWp\ChukkaSpec\Enums\CapNumberScheme;
use ChukkaWp\ChukkaSpec\Enums\EventType;
use ChukkaWp\ChukkaSpec\Enums\Gender;
use ChukkaWp\ChukkaSpec\Enums\MatchStatus;
use ChukkaWp\ChukkaSpec\Enums\RosterRole;
use ChukkaWp\ChukkaSpec\Models\Club;
use ChukkaWp\ChukkaSpec\Models\Event;
use ChukkaWp\ChukkaSpec\Models\MatchModel;
use ChukkaWp\ChukkaSpec\Models\Player;
use ChukkaWp\ChukkaSpec\Models\RosterEntry;
use ChukkaWp\ChukkaSpec\Models\RuleSet;
use ChukkaWp\ChukkaSpec\Models\Team;
use ChukkaWp\ChukkaSpec\Models\TeamMembership;

// ── Club ────────────────────────────────────────────────────────────

it('club has many teams', function () {
    $club = Club::create(['name' => 'Dolphins SC']);

    Team::create(['club_id' => $club->id, 'name' => 'Dolphins U14', 'gender' => 'male']);
    Team::create(['club_id' => $club->id, 'name' => 'Dolphins U16', 'gender' => 'female']);

    expect($club->teams)->toHaveCount(2)
        ->each->toBeInstanceOf(Team::class);
});

it('club has many players', function () {
    $club = Club::create(['name' => 'Sharks WPC']);

    Player::create(['club_id' => $club->id, 'name' => 'Alice Smith']);
    Player::create(['club_id' => $club->id, 'name' => 'Bob Jones']);
    Player::create(['club_id' => $club->id, 'name' => 'Carol Lee']);

    expect($club->players)->toHaveCount(3)
        ->each->toBeInstanceOf(Player::class);
});

// ── Team ────────────────────────────────────────────────────────────

it('team belongs to club', function () {
    $club = Club::create(['name' => 'Tigers WP']);
    $team = Team::create(['club_id' => $club->id, 'name' => 'Tigers Open', 'gender' => 'mixed']);

    expect($team->club)->toBeInstanceOf(Club::class)
        ->and($team->club->id)->toBe($club->id);
});

it('team casts gender to Gender enum', function () {
    $club = Club::create(['name' => 'Eagles']);
    $team = Team::create(['club_id' => $club->id, 'name' => 'Eagles Men', 'gender' => 'male']);

    expect($team->gender)->toBe(Gender::Male);
});

it('team has many-to-many players via team memberships', function () {
    $club = Club::create(['name' => 'Barracudas']);
    $team = Team::create(['club_id' => $club->id, 'name' => 'Barracudas A', 'gender' => 'male']);

    $playerA = Player::create(['club_id' => $club->id, 'name' => 'Player One']);
    $playerB = Player::create(['club_id' => $club->id, 'name' => 'Player Two']);

    TeamMembership::create([
        'player_id' => $playerA->id,
        'team_id' => $team->id,
        'joined_at' => '2025-01-01',
    ]);
    TeamMembership::create([
        'player_id' => $playerB->id,
        'team_id' => $team->id,
        'joined_at' => '2025-03-01',
    ]);

    expect($team->players)->toHaveCount(2)
        ->each->toBeInstanceOf(Player::class);
});

it('team has many memberships', function () {
    $club = Club::create(['name' => 'Stingrays']);
    $team = Team::create(['club_id' => $club->id, 'name' => 'Stingrays B', 'gender' => 'female']);
    $player = Player::create(['club_id' => $club->id, 'name' => 'Dana White']);

    TeamMembership::create([
        'player_id' => $player->id,
        'team_id' => $team->id,
        'joined_at' => '2024-06-01',
    ]);

    expect($team->memberships)->toHaveCount(1)
        ->each->toBeInstanceOf(TeamMembership::class);
});

// ── Player ──────────────────────────────────────────────────────────

it('player belongs to club', function () {
    $club = Club::create(['name' => 'Marlins']);
    $player = Player::create(['club_id' => $club->id, 'name' => 'Eve Taylor']);

    expect($player->club)->toBeInstanceOf(Club::class)
        ->and($player->club->id)->toBe($club->id);
});

it('player has many-to-many teams via team memberships', function () {
    $club = Club::create(['name' => 'Piranhas']);
    $player = Player::create(['club_id' => $club->id, 'name' => 'Frank Green']);

    $teamA = Team::create(['club_id' => $club->id, 'name' => 'Piranhas U12', 'gender' => 'mixed']);
    $teamB = Team::create(['club_id' => $club->id, 'name' => 'Piranhas U14', 'gender' => 'mixed']);

    TeamMembership::create([
        'player_id' => $player->id,
        'team_id' => $teamA->id,
        'joined_at' => '2024-01-01',
    ]);
    TeamMembership::create([
        'player_id' => $player->id,
        'team_id' => $teamB->id,
        'joined_at' => '2025-01-01',
    ]);

    expect($player->teams)->toHaveCount(2)
        ->each->toBeInstanceOf(Team::class);
});

it('player casts is_goalkeeper to boolean', function () {
    $club = Club::create(['name' => 'Orcas']);
    $player = Player::create(['club_id' => $club->id, 'name' => 'Gina Hall', 'is_goalkeeper' => true]);

    $player->refresh();

    expect($player->is_goalkeeper)->toBeTrue();
});

// ── TeamMembership ──────────────────────────────────────────────────

it('team membership belongs to player and team', function () {
    $club = Club::create(['name' => 'Seals']);
    $team = Team::create(['club_id' => $club->id, 'name' => 'Seals A', 'gender' => 'male']);
    $player = Player::create(['club_id' => $club->id, 'name' => 'Hank Morris']);

    $membership = TeamMembership::create([
        'player_id' => $player->id,
        'team_id' => $team->id,
        'joined_at' => '2025-02-15',
    ]);

    expect($membership->player)->toBeInstanceOf(Player::class)
        ->and($membership->player->id)->toBe($player->id)
        ->and($membership->team)->toBeInstanceOf(Team::class)
        ->and($membership->team->id)->toBe($team->id);
});

it('team membership casts dates', function () {
    $club = Club::create(['name' => 'Otters']);
    $team = Team::create(['club_id' => $club->id, 'name' => 'Otters A', 'gender' => 'female']);
    $player = Player::create(['club_id' => $club->id, 'name' => 'Iris Ng']);

    $membership = TeamMembership::create([
        'player_id' => $player->id,
        'team_id' => $team->id,
        'joined_at' => '2025-01-10',
        'left_at' => '2025-12-31',
    ]);

    $membership->refresh();

    expect($membership->joined_at)->toBeInstanceOf(Carbon\Carbon::class)
        ->and($membership->left_at)->toBeInstanceOf(Carbon\Carbon::class);
});

// ── RuleSet ─────────────────────────────────────────────────────────

it('rule set stands alone with no parent relationships', function () {
    $ruleSet = RuleSet::create(['name' => 'Test Rules']);

    expect($ruleSet)->toBeInstanceOf(RuleSet::class)
        ->and($ruleSet->id)->not->toBeNull();
});

it('rule set has many matches', function () {
    $club = Club::create(['name' => 'Club A']);
    $homeTeam = Team::create(['club_id' => $club->id, 'name' => 'Home Team', 'gender' => 'male']);
    $awayTeam = Team::create(['club_id' => $club->id, 'name' => 'Away Team', 'gender' => 'male']);
    $ruleSet = RuleSet::create(['name' => 'Standard Rules']);

    MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $homeTeam->id,
        'away_team_id' => $awayTeam->id,
        'status' => 'scheduled',
    ]);

    expect($ruleSet->matches)->toHaveCount(1)
        ->each->toBeInstanceOf(MatchModel::class);
});

it('rule set casts boolean and enum fields', function () {
    $ruleSet = RuleSet::create([
        'name' => 'Cast Test',
        'is_bundled' => true,
        'running_time' => true,
        'possession_clock_enabled' => false,
        'foul_limit_enforced' => true,
        'cap_number_scheme' => 'open',
    ]);

    $ruleSet->refresh();

    expect($ruleSet->is_bundled)->toBeTrue()
        ->and($ruleSet->running_time)->toBeTrue()
        ->and($ruleSet->possession_clock_enabled)->toBeFalse()
        ->and($ruleSet->foul_limit_enforced)->toBeTrue()
        ->and($ruleSet->cap_number_scheme)->toBe(CapNumberScheme::Open);
});

// ── MatchModel ──────────────────────────────────────────────────────

it('match belongs to rule set', function () {
    $club = Club::create(['name' => 'Club B']);
    $home = Team::create(['club_id' => $club->id, 'name' => 'Team Home', 'gender' => 'male']);
    $away = Team::create(['club_id' => $club->id, 'name' => 'Team Away', 'gender' => 'male']);
    $ruleSet = RuleSet::create(['name' => 'WA 2025']);

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $home->id,
        'away_team_id' => $away->id,
        'status' => 'scheduled',
    ]);

    expect($match->ruleSet)->toBeInstanceOf(RuleSet::class)
        ->and($match->ruleSet->id)->toBe($ruleSet->id);
});

it('match belongs to home team and away team', function () {
    $club = Club::create(['name' => 'Club C']);
    $home = Team::create(['club_id' => $club->id, 'name' => 'Whites', 'gender' => 'female']);
    $away = Team::create(['club_id' => $club->id, 'name' => 'Blues', 'gender' => 'female']);
    $ruleSet = RuleSet::create(['name' => 'Rules']);

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $home->id,
        'away_team_id' => $away->id,
        'status' => 'scheduled',
    ]);

    expect($match->homeTeam)->toBeInstanceOf(Team::class)
        ->and($match->homeTeam->id)->toBe($home->id)
        ->and($match->awayTeam)->toBeInstanceOf(Team::class)
        ->and($match->awayTeam->id)->toBe($away->id);
});

it('match casts status to MatchStatus enum', function () {
    $club = Club::create(['name' => 'Club D']);
    $home = Team::create(['club_id' => $club->id, 'name' => 'Alpha', 'gender' => 'male']);
    $away = Team::create(['club_id' => $club->id, 'name' => 'Beta', 'gender' => 'male']);
    $ruleSet = RuleSet::create(['name' => 'Rules']);

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $home->id,
        'away_team_id' => $away->id,
        'status' => 'in_progress',
    ]);

    expect($match->status)->toBe(MatchStatus::InProgress);
});

it('match has many events', function () {
    $club = Club::create(['name' => 'Club E']);
    $home = Team::create(['club_id' => $club->id, 'name' => 'North', 'gender' => 'male']);
    $away = Team::create(['club_id' => $club->id, 'name' => 'South', 'gender' => 'male']);
    $ruleSet = RuleSet::create(['name' => 'Rules']);

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $home->id,
        'away_team_id' => $away->id,
        'status' => 'in_progress',
    ]);

    Event::create([
        'match_id' => $match->id,
        'sequence' => 1,
        'type' => 'match_start',
        'period' => 1,
        'period_clock_seconds' => 480,
        'recorded_at' => now(),
    ]);
    Event::create([
        'match_id' => $match->id,
        'sequence' => 2,
        'type' => 'swim_off',
        'period' => 1,
        'period_clock_seconds' => 480,
        'recorded_at' => now(),
        'payload' => ['possession' => 'home'],
    ]);

    expect($match->events)->toHaveCount(2)
        ->each->toBeInstanceOf(Event::class);
});

it('match has many roster entries', function () {
    $club = Club::create(['name' => 'Club F']);
    $home = Team::create(['club_id' => $club->id, 'name' => 'East', 'gender' => 'female']);
    $away = Team::create(['club_id' => $club->id, 'name' => 'West', 'gender' => 'female']);
    $ruleSet = RuleSet::create(['name' => 'Rules']);
    $player = Player::create(['club_id' => $club->id, 'name' => 'Jane Doe']);

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $home->id,
        'away_team_id' => $away->id,
        'status' => 'scheduled',
    ]);

    RosterEntry::create([
        'match_id' => $match->id,
        'player_id' => $player->id,
        'team_id' => $home->id,
        'cap_number' => 1,
        'role' => 'goalkeeper',
        'is_starting' => true,
    ]);

    expect($match->rosterEntries)->toHaveCount(1)
        ->each->toBeInstanceOf(RosterEntry::class);
});

// ── Event ───────────────────────────────────────────────────────────

it('event belongs to match', function () {
    $club = Club::create(['name' => 'Club G']);
    $home = Team::create(['club_id' => $club->id, 'name' => 'Red', 'gender' => 'male']);
    $away = Team::create(['club_id' => $club->id, 'name' => 'Blue', 'gender' => 'male']);
    $ruleSet = RuleSet::create(['name' => 'Rules']);

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $home->id,
        'away_team_id' => $away->id,
        'status' => 'in_progress',
    ]);

    $event = Event::create([
        'match_id' => $match->id,
        'sequence' => 1,
        'type' => 'period_start',
        'period' => 1,
        'period_clock_seconds' => 480,
        'recorded_at' => now(),
    ]);

    expect($event->match)->toBeInstanceOf(MatchModel::class)
        ->and($event->match->id)->toBe($match->id);
});

it('event casts type to EventType enum', function () {
    $club = Club::create(['name' => 'Club H']);
    $home = Team::create(['club_id' => $club->id, 'name' => 'Gold', 'gender' => 'male']);
    $away = Team::create(['club_id' => $club->id, 'name' => 'Silver', 'gender' => 'male']);
    $ruleSet = RuleSet::create(['name' => 'Rules']);

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $home->id,
        'away_team_id' => $away->id,
        'status' => 'in_progress',
    ]);

    $event = Event::create([
        'match_id' => $match->id,
        'sequence' => 1,
        'type' => 'goal',
        'period' => 2,
        'period_clock_seconds' => 300,
        'recorded_at' => now(),
        'payload' => ['team' => 'home', 'method' => 'action'],
    ]);

    expect($event->type)->toBe(EventType::Goal);
});

it('event casts payload to array', function () {
    $club = Club::create(['name' => 'Club I']);
    $home = Team::create(['club_id' => $club->id, 'name' => 'Stars', 'gender' => 'female']);
    $away = Team::create(['club_id' => $club->id, 'name' => 'Comets', 'gender' => 'female']);
    $ruleSet = RuleSet::create(['name' => 'Rules']);

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $home->id,
        'away_team_id' => $away->id,
        'status' => 'in_progress',
    ]);

    $payloadData = ['team' => 'away', 'player_cap' => 5, 'method' => 'extra_player'];

    $event = Event::create([
        'match_id' => $match->id,
        'sequence' => 1,
        'type' => 'goal',
        'period' => 3,
        'period_clock_seconds' => 120,
        'recorded_at' => now(),
        'payload' => $payloadData,
    ]);

    $event->refresh();

    expect($event->payload)->toBeArray()
        ->and($event->payload['team'])->toBe('away')
        ->and($event->payload['player_cap'])->toBe(5)
        ->and($event->payload['method'])->toBe('extra_player');
});

it('event payload can be null', function () {
    $club = Club::create(['name' => 'Club J']);
    $home = Team::create(['club_id' => $club->id, 'name' => 'Lions', 'gender' => 'male']);
    $away = Team::create(['club_id' => $club->id, 'name' => 'Bears', 'gender' => 'male']);
    $ruleSet = RuleSet::create(['name' => 'Rules']);

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $home->id,
        'away_team_id' => $away->id,
        'status' => 'in_progress',
    ]);

    $event = Event::create([
        'match_id' => $match->id,
        'sequence' => 1,
        'type' => 'match_start',
        'period' => 1,
        'period_clock_seconds' => 480,
        'recorded_at' => now(),
        'payload' => null,
    ]);

    $event->refresh();

    expect($event->payload)->toBeNull();
});

// ── RosterEntry ─────────────────────────────────────────────────────

it('roster entry belongs to match, player, and team', function () {
    $club = Club::create(['name' => 'Club K']);
    $home = Team::create(['club_id' => $club->id, 'name' => 'Hawks', 'gender' => 'male']);
    $away = Team::create(['club_id' => $club->id, 'name' => 'Falcons', 'gender' => 'male']);
    $ruleSet = RuleSet::create(['name' => 'Rules']);
    $player = Player::create(['club_id' => $club->id, 'name' => 'Sam Wilson']);

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $home->id,
        'away_team_id' => $away->id,
        'status' => 'scheduled',
    ]);

    $entry = RosterEntry::create([
        'match_id' => $match->id,
        'player_id' => $player->id,
        'team_id' => $home->id,
        'cap_number' => 7,
        'role' => 'field_player',
        'is_starting' => true,
    ]);

    expect($entry->match)->toBeInstanceOf(MatchModel::class)
        ->and($entry->match->id)->toBe($match->id)
        ->and($entry->player)->toBeInstanceOf(Player::class)
        ->and($entry->player->id)->toBe($player->id)
        ->and($entry->team)->toBeInstanceOf(Team::class)
        ->and($entry->team->id)->toBe($home->id);
});

it('roster entry casts role to RosterRole enum', function () {
    $club = Club::create(['name' => 'Club L']);
    $home = Team::create(['club_id' => $club->id, 'name' => 'Wolves', 'gender' => 'female']);
    $away = Team::create(['club_id' => $club->id, 'name' => 'Panthers', 'gender' => 'female']);
    $ruleSet = RuleSet::create(['name' => 'Rules']);
    $player = Player::create(['club_id' => $club->id, 'name' => 'Tina Brown', 'is_goalkeeper' => true]);

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $home->id,
        'away_team_id' => $away->id,
        'status' => 'scheduled',
    ]);

    $entry = RosterEntry::create([
        'match_id' => $match->id,
        'player_id' => $player->id,
        'team_id' => $home->id,
        'cap_number' => 1,
        'role' => 'goalkeeper',
        'is_starting' => true,
    ]);

    expect($entry->role)->toBe(RosterRole::Goalkeeper);
});

it('roster entry casts is_starting to boolean', function () {
    $club = Club::create(['name' => 'Club M']);
    $home = Team::create(['club_id' => $club->id, 'name' => 'Rams', 'gender' => 'male']);
    $away = Team::create(['club_id' => $club->id, 'name' => 'Bulls', 'gender' => 'male']);
    $ruleSet = RuleSet::create(['name' => 'Rules']);
    $player = Player::create(['club_id' => $club->id, 'name' => 'Uma Black']);

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $home->id,
        'away_team_id' => $away->id,
        'status' => 'scheduled',
    ]);

    $entry = RosterEntry::create([
        'match_id' => $match->id,
        'player_id' => $player->id,
        'team_id' => $home->id,
        'cap_number' => 10,
        'role' => 'field_player',
        'is_starting' => false,
    ]);

    $entry->refresh();

    expect($entry->is_starting)->toBeFalse();
});

// ── UUID traits ─────────────────────────────────────────────────────

it('models with HasUuids generate uuid primary keys', function () {
    $club = Club::create(['name' => 'UUID Club']);
    $team = Team::create(['club_id' => $club->id, 'name' => 'UUID Team', 'gender' => 'male']);
    $player = Player::create(['club_id' => $club->id, 'name' => 'UUID Player']);
    $ruleSet = RuleSet::create(['name' => 'UUID Rules']);

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $team->id,
        'away_team_id' => $team->id,
        'status' => 'scheduled',
    ]);

    $event = Event::create([
        'match_id' => $match->id,
        'sequence' => 1,
        'type' => 'match_start',
        'period' => 1,
        'period_clock_seconds' => 480,
        'recorded_at' => now(),
    ]);

    $uuidPattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/';

    expect($club->id)->toMatch($uuidPattern)
        ->and($team->id)->toMatch($uuidPattern)
        ->and($player->id)->toMatch($uuidPattern)
        ->and($ruleSet->id)->toMatch($uuidPattern)
        ->and($match->id)->toMatch($uuidPattern)
        ->and($event->id)->toMatch($uuidPattern);
});

it('team membership and roster entry use auto-incrementing ids', function () {
    $club = Club::create(['name' => 'Inc Club']);
    $team = Team::create(['club_id' => $club->id, 'name' => 'Inc Team', 'gender' => 'male']);
    $player = Player::create(['club_id' => $club->id, 'name' => 'Inc Player']);
    $ruleSet = RuleSet::create(['name' => 'Inc Rules']);

    $membership = TeamMembership::create([
        'player_id' => $player->id,
        'team_id' => $team->id,
        'joined_at' => '2025-01-01',
    ]);

    $match = MatchModel::create([
        'rule_set_id' => $ruleSet->id,
        'home_team_id' => $team->id,
        'away_team_id' => $team->id,
        'status' => 'scheduled',
    ]);

    $rosterEntry = RosterEntry::create([
        'match_id' => $match->id,
        'player_id' => $player->id,
        'team_id' => $team->id,
        'cap_number' => 1,
        'role' => 'field_player',
    ]);

    expect($membership->id)->toBeInt()
        ->and($rosterEntry->id)->toBeInt();
});
