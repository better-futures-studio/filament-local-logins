<?php

namespace BetterFuturesStudio\FilamentLocalLogins\Concerns;

use BetterFuturesStudio\FilamentLocalLogins\LocalLogins;
use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Support\Responsable;

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

        /** @var LocalLogins $plugin */
        $plugin = $panel->getPlugin(LocalLogins::class);

        return $plugin->getEmails($panel->getId());
    }

    public function loginUser(string $email): Responsable
    {
        $panel = Filament::getCurrentPanel();

        abort_unless($this->allowsLocalLogin($panel), 403, 'Local login is not allowed for this panel.');
        abort_unless(in_array($email, $this->localLoginEmails(), true), 403, 'This account is not configured for local login.');

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

        $response = app($this->getLoginResponseContract());

        throw_unless($response instanceof Responsable, 'The login response must implement '.Responsable::class);

        return $response;
    }

    protected function allowsLocalLogin(?Panel $panel): bool
    {
        if (! $panel?->hasPlugin(LocalLogins::class)) {
            return false;
        }

        $plugin = $panel->getPlugin(LocalLogins::class);

        if (! $plugin instanceof LocalLogins) {
            return false;
        }

        return $plugin->isEnabled($panel->getId());
    }

    protected function getLoginResponseContract(): string
    {
        $modernContract = 'Filament\\Auth\\Http\\Responses\\Contracts\\LoginResponse';

        if (interface_exists($modernContract)) {
            return $modernContract;
        }

        return 'Filament\\Http\\Responses\\Auth\\Contracts\\LoginResponse';
    }
}
