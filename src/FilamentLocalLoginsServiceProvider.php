<?php

namespace BetterFuturesStudio\FilamentLocalLogins;

use BetterFuturesStudio\FilamentLocalLogins\Commands\FilamentLocalLoginsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentLocalLoginsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-local-logins')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(FilamentLocalLoginsCommand::class);
    }
}
