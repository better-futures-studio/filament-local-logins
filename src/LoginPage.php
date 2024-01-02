<?php

namespace BetterFuturesStudio\FilamentLocalLogins;

use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Pages\Auth\Login;
use Filament\Panel;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\SessionGuard;

class LoginPage extends Login
{
    /**
     * @return array<string>
     */
    public function localLoginEmails(): array
    {
        if (! $this->allowsLocalLogin()) {
            return [];
        }
        $panel = Filament::getCurrentPanel();

        $emails = config("filament-local-logins.panels.{$panel->getId()}.emails");

        if (! is_array($emails)) {
            return [];
        }

        return $emails;
    }

    public function loginUser(string $email): ?LoginResponse
    {
        if (! $this->allowsLocalLogin()) {
            abort(403);
        }

        $panel = Filament::getCurrentPanel();
        throw_unless($panel instanceof Panel, 'The panel must be an instance of '.Panel::class);

        $guard = $panel->auth();
        throw_unless($guard instanceof SessionGuard, 'The guard must be an instance of '.SessionGuard::class);

        $provider = $guard->getProvider();
        throw_unless($provider instanceof EloquentUserProvider, 'The provider must be an instance of '.EloquentUserProvider::class);

        $user = $provider->retrieveByCredentials([
            'email' => $email,
        ]);
        $modelClass = $provider->getModel();
        abort_unless($user instanceof $modelClass, 404);

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

    protected function allowsLocalLogin(): bool
    {
        $panel = Filament::getCurrentPanel();

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
