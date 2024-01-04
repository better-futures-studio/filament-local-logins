<?php

namespace BetterFuturesStudio\FilamentLocalLogins\Concerns;

use BetterFuturesStudio\FilamentLocalLogins\LocalLogins;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\SessionGuard;

trait HasLocalLogins
{
    /**
     * @return array<string>
     */
    public function localLoginEmails(): array
    {
        $panel = Filament::getCurrentPanel();

        if (! $this->allowsLocalLogin($panel)) {
            return [];
        }

        $emails = config("filament-local-logins.panels.{$panel->getId()}.emails", []);

        if (! is_array($emails)) {
            return [];
        }

        return $emails;
    }

    public function loginUser(string $email): LoginResponse
    {
        $panel = Filament::getCurrentPanel();

        abort_unless($this->allowsLocalLogin($panel), 403, 'Local login is not allowed for this panel.');

        throw_unless($panel instanceof Panel, 'The panel must be an instance of '.Panel::class);

        $guard = $panel->auth();
        throw_unless($guard instanceof SessionGuard, 'The guard must be an instance of '.SessionGuard::class);

        $provider = $guard->getProvider();
        throw_unless($provider instanceof EloquentUserProvider, 'The provider must be an instance of '.EloquentUserProvider::class);

        $user = $provider->retrieveByCredentials([
            'email' => $email,
        ]);
        $modelClass = $provider->getModel();

        if (! $user instanceof $modelClass) {
            $this->throwFailureValidationException();
        }

        $guard->login($user);

        $user = $guard->user();

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel($panel))
        ) {
            $guard->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    protected function allowsLocalLogin(?Panel $panel): bool
    {
        if (empty($panel)) {
            return false;
        }

        $plugin = $panel->getPlugin(LocalLogins::class);

        if (! $plugin instanceof LocalLogins) {
            return false;
        }

        return $plugin->isEnabled($panel->getId());
    }
}
