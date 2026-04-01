<?php

namespace ChukkaWp\ChukkaSpec;

use ChukkaWp\ChukkaSpec\Services\CorrectionService;
use ChukkaWp\ChukkaSpec\Services\EventDispatcher;
use ChukkaWp\ChukkaSpec\Services\GameStateService;
use ChukkaWp\ChukkaSpec\Services\MatchSummaryBuilder;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ChukkaSpecServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('chukka-spec')
            ->hasConfigFile()
            ->discoversMigrations();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(GameStateService::class);
        $this->app->singleton(EventDispatcher::class);
        $this->app->singleton(CorrectionService::class);
        $this->app->singleton(MatchSummaryBuilder::class);
    }
}
