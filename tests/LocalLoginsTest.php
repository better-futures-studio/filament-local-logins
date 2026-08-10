<?php

namespace BetterFuturesStudio\FilamentLocalLogins\Tests;

use BetterFuturesStudio\FilamentLocalLogins\Concerns\HasLocalLogins;
use BetterFuturesStudio\FilamentLocalLogins\Filament\Pages\Auth\LoginPage;
use BetterFuturesStudio\FilamentLocalLogins\LocalLogins;
use BetterFuturesStudio\FilamentLocalLogins\Tests\Fixtures\User;
use Filament\Facades\Filament;
use Filament\Panel;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class TestLoginPage
{
    use HasLocalLogins;

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.email' => 'These credentials do not match our records.',
        ]);
    }
}

final class TestLoginResponse implements Responsable
{
    public function toResponse($request)
    {
        return response()->noContent();
    }
}

final class LocalLoginsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->enableLocalLogins();
        $this->bindLoginResponse();
    }

    public function test_it_extends_the_login_page_used_by_the_installed_filament_version(): void
    {
        $filamentLoginPage = class_exists('Filament\\Auth\\Pages\\Login')
            ? 'Filament\\Auth\\Pages\\Login'
            : 'Filament\\Pages\\Auth\\Login';

        self::assertTrue(is_subclass_of(LoginPage::class, $filamentLoginPage));
    }

    public function test_it_registers_the_configured_login_page_when_enabled(): void
    {
        $panel = $this->makePanel();

        self::assertSame(LoginPage::class, $panel->getLoginRouteAction());
        self::assertTrue($panel->hasPlugin(LocalLogins::class));
    }

    public function test_it_does_not_replace_the_login_page_when_disabled(): void
    {
        config()->set('filament-local-logins.panels.admin.enabled', false);

        $panel = $this->makePanel();

        self::assertNull($panel->getLoginRouteAction());
    }

    public function test_it_normalizes_configured_email_addresses(): void
    {
        $this->enableLocalLogins([
            ' developer@example.com ',
            'developer@example.com',
            '',
            null,
            'second@example.com',
        ]);

        self::assertSame([
            'developer@example.com',
            'second@example.com',
        ], LocalLogins::make()->getEmails('admin'));
    }

    public function test_it_returns_no_accounts_when_the_plugin_is_not_registered(): void
    {
        $this->makePanel(registerPlugin: false);

        self::assertSame([], (new TestLoginPage)->localLoginEmails());
    }

    public function test_it_returns_only_configured_accounts_for_an_enabled_panel(): void
    {
        $this->enableLocalLogins(['first@example.com', 'second@example.com']);
        $this->makePanel();

        self::assertSame([
            'first@example.com',
            'second@example.com',
        ], (new TestLoginPage)->localLoginEmails());
    }

    public function test_the_login_buttons_layout_does_not_depend_on_compiled_tailwind_utilities(): void
    {
        $view = file_get_contents(__DIR__.'/../resources/views/login-buttons.blade.php');

        self::assertIsString($view);
        self::assertStringContainsString('style="display: grid; gap: 0.5rem;"', $view);
        self::assertStringContainsString('style="width: 100%;"', $view);
        self::assertStringNotContainsString('class="flex flex-col gap-y-2"', $view);
        self::assertStringNotContainsString('class="mb-2 w-full"', $view);
    }

    public function test_it_rejects_an_account_that_is_not_configured_for_local_login(): void
    {
        User::query()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->makePanel();

        try {
            (new TestLoginPage)->loginUser('admin@example.com');
            self::fail('An unconfigured account was allowed to log in.');
        } catch (HttpException $exception) {
            self::assertSame(403, $exception->getStatusCode());
            self::assertSame('This account is not configured for local login.', $exception->getMessage());
        }
    }

    public function test_it_rejects_a_configured_account_that_cannot_access_the_panel(): void
    {
        User::query()->create([
            'email' => 'developer@example.com',
            'password' => bcrypt('password'),
            'can_access_panel' => false,
        ]);
        $this->makePanel();

        try {
            (new TestLoginPage)->loginUser('developer@example.com');
            self::fail('A user without panel access was allowed to log in.');
        } catch (ValidationException) {
            self::assertFalse(auth('web')->check());
        }
    }

    public function test_it_logs_in_a_configured_account_that_can_access_the_panel(): void
    {
        $user = User::query()->create([
            'email' => 'developer@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->makePanel();

        $response = (new TestLoginPage)->loginUser('developer@example.com');

        self::assertInstanceOf(TestLoginResponse::class, $response);
        self::assertSame($user->getKey(), auth('web')->id());
    }

    private function makePanel(bool $registerPlugin = true): Panel
    {
        $panel = Panel::make()
            ->id('admin')
            ->authGuard('web');

        if ($registerPlugin) {
            $panel->plugin(LocalLogins::make());
        }

        Filament::setCurrentPanel($panel);

        return $panel;
    }

    /**
     * @param  array<mixed>  $emails
     */
    private function enableLocalLogins(array $emails = ['developer@example.com']): void
    {
        config()->set('filament-local-logins.panels.admin', [
            'enabled' => true,
            'emails' => $emails,
            'login_page' => LoginPage::class,
        ]);
    }

    private function bindLoginResponse(): void
    {
        $contract = interface_exists('Filament\\Auth\\Http\\Responses\\Contracts\\LoginResponse')
            ? 'Filament\\Auth\\Http\\Responses\\Contracts\\LoginResponse'
            : 'Filament\\Http\\Responses\\Auth\\Contracts\\LoginResponse';

        app()->bind($contract, fn (): TestLoginResponse => new TestLoginResponse);
    }
}
