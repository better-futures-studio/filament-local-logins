<?php

namespace BetterFuturesStudio\FilamentLocalLogins;

use BetterFuturesStudio\FilamentLocalLogins\Filament\Pages\Auth\LoginPage;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\Factory as ViewFactory;

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
            ->login(config("filament-local-logins.panels.{$panel->getId()}.login_page", LoginPage::class))
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn () => app(ViewFactory::class)->make('filament-local-logins::login-buttons'),
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function isEnabled(string $panelId): bool
    {
        return (bool) config("filament-local-logins.panels.{$panelId}.enabled") && ($this->getEmails($panelId) !== []);
    }

    /**
     * @return list<string>
     */
    public function getEmails(string $panelId): array
    {
        $configuredEmails = config("filament-local-logins.panels.{$panelId}.emails", []);

        if (! is_array($configuredEmails)) {
            return [];
        }

        $emails = [];

        foreach ($configuredEmails as $configuredEmail) {
            if (! is_string($configuredEmail)) {
                continue;
            }

            $email = trim($configuredEmail);

            if (($email === '') || in_array($email, $emails, true)) {
                continue;
            }

            $emails[] = $email;
        }

        return $emails;
    }
}
