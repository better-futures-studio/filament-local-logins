<?php

namespace BetterFuturesStudio\FilamentLocalLogins\Tests;

use BetterFuturesStudio\FilamentLocalLogins\FilamentLocalLoginsServiceProvider;
use BetterFuturesStudio\FilamentLocalLogins\Tests\Fixtures\User;
use Filament\FilamentServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('can_access_panel')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            FilamentServiceProvider::class,
            FilamentLocalLoginsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');
        config()->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        config()->set('auth.defaults.guard', 'web');
        config()->set('auth.guards.web', [
            'driver' => 'session',
            'provider' => 'users',
        ]);
        config()->set('auth.providers.users', [
            'driver' => 'eloquent',
            'model' => User::class,
        ]);
    }
}
