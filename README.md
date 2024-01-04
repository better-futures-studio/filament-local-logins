# Filament Local Logins

[![Latest Version on Packagist](https://img.shields.io/packagist/v/better-futures-studio/filament-local-logins.svg?style=flat-square)](https://packagist.org/packages/better-futures-studio/filament-local-logins)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/better-futures-studio/filament-local-logins/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/better-futures-studio/filament-local-logins/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/better-futures-studio/filament-local-logins/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/better-futures-studio/filament-local-logins/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/better-futures-studio/filament-local-logins.svg?style=flat-square)](https://packagist.org/packages/better-futures-studio/filament-local-logins)

This package allows you to log in locally using pre-set email addresses, making it easy to log into one or multiple development user accounts. It can be used in an admin panel or multiple panels.

**NOTE:** You must have created the user accounts in order to use them with the login buttons, this package doesn't support the creation of user accounts.

## Output

![SCR-20240103-qijm.png](https://i.postimg.cc/bYW7M5MZ/SCR-20240103-qijm.png)

## Requirements

This package requires the following:

- `PHP:^8.1`
- `Filament:^3.0`

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
return [
    'panels' => [
        'admin' => [
            'enabled' => env('ADMIN_PANEL_LOCAL_LOGINS_ENABLED', env('APP_ENV') === 'local'),
            'emails' => array_filter(array_map('trim', explode(',', env('ADMIN_PANEL_LOCAL_LOGIN_EMAILS', '')))),
        ],
    ],
];
```

You can use it in multiple panels so if you want to add a configuration for a new panel, you can add a new config key with the panel id. For example, you can add `user` panel configuration like this:

```php
return [
    'panels' => [
        'admin' => [
            'enabled' => env('ADMIN_PANEL_LOCAL_LOGINS_ENABLED', env('APP_ENV') === 'local'),
            'emails' => array_filter(array_map('trim', explode(',', env('ADMIN_PANEL_LOCAL_LOGIN_EMAILS', '')))),
        ],
        'user' => [
            'enabled' => env('USER_PANEL_LOCAL_LOGINS_ENABLED', env('APP_ENV') === 'local'),
            'emails' => array_filter(array_map('trim', explode(',', env('USER_PANEL_LOCAL_LOGIN_EMAILS', '')))),
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

In your Filament panel provider, typically `AdminPanelProvider`, you need to register the plugin:

```php
use BetterFuturesStudio\FilamentLocalLogins\LocalLogins;
...
$panel->plugin(new LocalLogins());
```

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
