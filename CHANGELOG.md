# Changelog

All notable changes to `filament-local-logins` will be documented in this file.

## 1.5.0 - 2026-08-10

### Compatibility

- Add Filament 4 and 5 support while retaining Filament 3 compatibility.
- Add Livewire 4 and Laravel 13 support while retaining Livewire 3 and Laravel 10–12 compatibility.
- Support PHP 8.1–8.5, subject to the installed Laravel and Filament versions.
- Bridge the authentication page and response namespaces moved by Filament 4 while preserving the package's existing `LoginPage` API.

### Security and reliability

- Accept local-login actions only for email addresses configured for the current panel.
- Continue enforcing Filament's `canAccessPanel()` authorization check.
- Normalize configured email addresses and safely encode Livewire action arguments.
- Return no local accounts when the plugin is missing or disabled instead of throwing an exception.

### Tests and maintenance

- Replace the obsolete Pest 2 setup with a behavioral PHPUnit 10–13 test suite.
- Test nine representative combinations across Filament 3–5, Livewire 3–4, Laravel 10–13, PHP 8.1–8.5, Linux, and Windows.
- Update the GitHub Actions toolchain and merge the revalidated Dependabot pull requests [#21](https://github.com/better-futures-studio/filament-local-logins/pull/21), [#23](https://github.com/better-futures-studio/filament-local-logins/pull/23), [#24](https://github.com/better-futures-studio/filament-local-logins/pull/24), and [#25](https://github.com/better-futures-studio/filament-local-logins/pull/25).

**Full Changelog**: https://github.com/better-futures-studio/filament-local-logins/compare/1.4.0...1.5.0

## 1.4.0 - 2025-05-05

Add Laravel 12 support.

## 1.3.0 - 2025-02-12

Add `make()` method to match filament structure.

## 1.2.0 - 2024-04-14

### What's Changed

* Bump dependabot/fetch-metadata from 1.6.0 to 2.0.0 by @dependabot in https://github.com/better-futures-studio/filament-local-logins/pull/9
* Fix overriding custom login page issue. by @KarimAlii in https://github.com/better-futures-studio/filament-local-logins/pull/11

**Full Changelog**: https://github.com/better-futures-studio/filament-local-logins/compare/1.1.0...1.2.0

## 1.1.0 - 2024-03-17

### What's Changed

* Fix changelog update. by @KarimAlii in https://github.com/better-futures-studio/filament-local-logins/pull/4
* Bump ramsey/composer-install from 2 to 3 by @dependabot in https://github.com/better-futures-studio/filament-local-logins/pull/5
* Update for Laravel 11 by @sidis405 in https://github.com/better-futures-studio/filament-local-logins/pull/7

### New Contributors

* @sidis405 made their first contribution in https://github.com/better-futures-studio/filament-local-logins/pull/7

**Full Changelog**: https://github.com/better-futures-studio/filament-local-logins/compare/1.0.1...1.1.0

## 1.0.0 - 2024-01-27

Initial release
