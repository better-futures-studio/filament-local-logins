<?php

namespace BetterFuturesStudio\FilamentLocalLogins;

use BetterFuturesStudio\FilamentLocalLogins\Filament\Pages\Auth\LoginPage;
use Filament\Contracts\Plugin;
use Filament\Panel;

class LocalLogins implements Plugin
{
    public function getId(): string
    {
        return self::class;
    }

    public function register(Panel $panel): void
    {
        if (! $this->isEnabled($panel->getId())) {
            return;
        }

        $panel
            ->login(LoginPage::class)
            ->renderHook('panels::auth.login.form.before', fn () => view('filament-local-logins::login-buttons'));
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public function isEnabled(string $panelId): bool
    {
        return (bool) config("filament-local-logins.panels.{$panelId}.enabled") && ! empty(config("filament-local-logins.panels.{$panelId}.emails"));
    }
}
