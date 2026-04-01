# Chukka Spec

Open data standard for water polo match representation. Provides Eloquent models, migrations, event types, payload validation, game state computation, and correction handling.

This is the foundation package used by all other [Chukka](https://github.com/chukka-wp/chukka) components.

## Features

- Eloquent models: Club, Team, Player, Match, RosterEntry, Event, RuleSet
- `GameStateService` — computes full match state from event log
- `EventDispatcher` — validates and persists match events
- `CorrectionService` — void and replace events without deletion
- Bundled rule sets (FINA 2025 defaults, configurable per competition)
- Database migrations and seeders

## Installation

```bash
composer require chukka-wp/chukka-spec
```

## Usage

Publish migrations:

```bash
php artisan vendor:publish --tag=chukka-spec-migrations
```

Configure model overrides in `config/chukka-spec.php`:

```php
return [
    'models' => [
        'club' => \App\Models\Club::class,
        'match' => \App\Models\MatchModel::class,
        // ...
    ],
];
```

## License

[MIT License](LICENSE.md)
