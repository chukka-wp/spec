<?php

namespace ChukkaWp\ChukkaSpec\Tests;

use ChukkaWp\ChukkaSpec\ChukkaSpecServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ChukkaSpecServiceProvider::class,
        ];
    }

    protected function defineDatabaseMigrations(): void
    {
        $migrationPath = __DIR__.'/../database/migrations';

        $stubs = glob("{$migrationPath}/*.php.stub");
        sort($stubs);

        foreach ($stubs as $stub) {
            (require $stub)->up();
        }
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
