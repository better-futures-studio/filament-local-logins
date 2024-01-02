<?php

namespace BetterFuturesStudio\FilamentLocalLogins;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentLocalLoginsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-local-logins')
            ->hasConfigFile()
            ->hasViews();
    }
}
