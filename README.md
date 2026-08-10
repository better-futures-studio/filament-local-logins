# Filament Local Logins

[![Latest Version on Packagist](https://img.shields.io/packagist/v/better-futures-studio/filament-local-logins.svg?style=flat-square)](https://packagist.org/packages/better-futures-studio/filament-local-logins)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/better-futures-studio/filament-local-logins/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/better-futures-studio/filament-local-logins/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/better-futures-studio/filament-local-logins/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/better-futures-studio/filament-local-logins/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/better-futures-studio/filament-local-logins.svg?style=flat-square)](https://packagist.org/packages/better-futures-studio/filament-local-logins)

This package allows you to log in locally using pre-set email addresses, making it easy to log into one or multiple development user accounts. It can be used in an admin panel or multiple panels.

> [!IMPORTANT]
> Only enable local logins in trusted local environments. The package never creates users: every configured email must already belong to a user who can access the panel.

## Output

![SCR-20240103-qijm.png](https://i.postimg.cc/bYW7M5MZ/SCR-20240103-qijm.png)

## Requirements

Version 1 supports Filament 3, 4, and 5 while preserving the original PHP 8.1 and Laravel 10 support where the framework permits it.

| Filament | Livewire | Laravel | PHP |
| --- | --- | --- | --- |
| `^3.0` | `^3.0` | 10–12 | `^8.1` |
| `^4.0` | `^3.5` | 11–12 | `^8.2` |
| `^5.0` | `^4.1` | 11–13 | `^8.2` |

Composer will select the compatible versions for your application. Individual Laravel and Filament releases may require a newer PHP patch or minor version within these ranges.

## Installation

You can install the package via composer:

```bash
composer require better-futures-studio/filament-local-logins
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-local-logins-config"
```

This is the contents of the published config file:

```php
use BetterFuturesStudio\FilamentLocalLogins\Filament\Pages\Auth\LoginPage;

return [
    'panels' => [
        'admin' => [
            'enabled' => env('ADMIN_PANEL_LOCAL_LOGINS_ENABLED', env('APP_ENV') === 'local'),
            'emails' => array_filter(array_map('trim', explode(',', env('ADMIN_PANEL_LOCAL_LOGIN_EMAILS', '')))),
            'login_page' => LoginPage::class,
        ],
    ],
];
```

You can use it in multiple panels so if you want to add a configuration for a new panel, you can add a new config key with the panel id. For example, you can add `user` panel configuration like this:

```php
use BetterFuturesStudio\FilamentLocalLogins\Filament\Pages\Auth\LoginPage;

return [
    'panels' => [
        'admin' => [
            'enabled' => env('ADMIN_PANEL_LOCAL_LOGINS_ENABLED', env('APP_ENV') === 'local'),
            'emails' => array_filter(array_map('trim', explode(',', env('ADMIN_PANEL_LOCAL_LOGIN_EMAILS', '')))),
            'login_page' => LoginPage::class,
        ],
        'user' => [
            'enabled' => env('USER_PANEL_LOCAL_LOGINS_ENABLED', env('APP_ENV') === 'local'),
            'emails' => array_filter(array_map('trim', explode(',', env('USER_PANEL_LOCAL_LOGIN_EMAILS', '')))),
            'login_page' => LoginPage::class,
        ],
    ],
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-local-logins-views"
```

## Usage

Set the `ADMIN_PANEL_LOCAL_LOGIN_EMAILS` in your .env file to use this package.

In your .env file, add the following:

```bash
ADMIN_PANEL_LOCAL_LOGIN_EMAILS="free-user@example.com,paid-user@example.com" # Provide a comma-separated list of emails that can log in locally
```

If you wish to customize the default login page, change the `'login_page' => LoginPage::class,` line to point to your class and use the `HasLocalLogins` trait. Filament moved its login page namespace in version 4, so use the matching base class.

For Filament 4 and 5:

```php
use BetterFuturesStudio\FilamentLocalLogins\Concerns\HasLocalLogins;
use Filament\Auth\Pages\Login;

class YourCustomLoginPage extends Login
{
    use HasLocalLogins;
}
```

For Filament 3:

```php
use BetterFuturesStudio\FilamentLocalLogins\Concerns\HasLocalLogins;
use Filament\Pages\Auth\Login;

class YourCustomLoginPage extends Login
{
    use HasLocalLogins;
}
```

In your Filament panel provider, typically `AdminPanelProvider`, you need to register the plugin:

```php
use BetterFuturesStudio\FilamentLocalLogins\LocalLogins;

$panel->plugin(new LocalLogins());
```

Login requests are accepted only for the email addresses configured for the current panel. The selected user must also pass Filament's `canAccessPanel()` check.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Karim Ali](https://github.com/KarimAlii)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
