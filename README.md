# Filament Local Logins

[![Latest Version on Packagist](https://img.shields.io/packagist/v/better-futures-studio/filament-local-logins.svg?style=flat-square)](https://packagist.org/packages/better-futures-studio/filament-local-logins)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/better-futures-studio/filament-local-logins/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/better-futures-studio/filament-local-logins/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/better-futures-studio/filament-local-logins/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/better-futures-studio/filament-local-logins/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/better-futures-studio/filament-local-logins.svg?style=flat-square)](https://packagist.org/packages/better-futures-studio/filament-local-logins)

This package allows developers to log in locally using pre-set email addresses, eliminating the need for repeated email and password entries. It can be used in an admin panel or multiple panels, with configuration options available in the config file.

Set `ADMIN_PANEL_LOCAL_LOGINS_ENABLED` and `ADMIN_PANEL_LOCAL_LOGIN_EMAILS` in your .env file to use this package.

**NOTE:** The emails that will be set in the environment file must be registered in the database.

## Requirements

This package requires the following:

-   `PHP:^8.1`
-   `Filament:^3.0`

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/filament-local-logins.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/filament-local-logins)

We invest a significant amount of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [purchasing one of our paid products](https://spatie.be/open-source/support-us).

We greatly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

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

You can use it in multiple panels so if you want to add a configuration for a new panel, you can add a new config key with the panel id. For example, users have the same values but with a different environment key like `USERS_PANEL_LOCAL_LOGINS_ENABLED` and `USERS_PANEL_LOCAL_LOGIN_EMAILS`.

```php
return [
    'panels' => [
        'admin' => [
            'enabled' => env('ADMIN_PANEL_LOCAL_LOGINS_ENABLED', env('APP_ENV') === 'local'),
            'emails' => array_filter(array_map('trim', explode(',', env('ADMIN_PANEL_LOCAL_LOGIN_EMAILS', '')))),
        ],
        'users' => [
            'enabled' => env('USERS_PANEL_LOCAL_LOGINS_ENABLED', env('APP_ENV') === 'local'),
            'emails' => array_filter(array_map('trim', explode(',', env('USERS_PANEL_LOCAL_LOGIN_EMAILS', '')))),
        ],
    ],
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-local-logins-views"
```

## Usage

```php
$panel->plugin(new LocalLogins());
```

## Output

![SCR-20240103-lftm.png](https://i.postimg.cc/VvpVkct5/SCR-20240103-lftm.png)

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

-   [Karim Ali](https://github.com/better-futures-studio)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
