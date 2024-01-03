<?php

namespace BetterFuturesStudio\FilamentLocalLogins\Tests;

use BetterFuturesStudio\FilamentLocalLogins\FilamentLocalLoginsServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'BetterFuturesStudio\\FilamentLocalLogins\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            FilamentLocalLoginsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_filament-local-logins_table.php.stub';
        $migration->up();
        */
    }
}
