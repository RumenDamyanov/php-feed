# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.1.0] - 2026-10-26

### Security

- Accept only `atom` and `rss` in `Feed::render()` unless a custom view is set, so `$format` cannot be turned into a view name such as `feed::../config/config`.
- Escape `Feed::link()` URL, type, title, and language attributes.
- Document that PHP2Shell ([CVE-2026-109618](https://www.cve.org/CVERecord?id=CVE-2026-109618), [CVE-2026-109619](https://www.cve.org/CVERecord?id=CVE-2026-109619)) is in the PHP engine: this package cannot patch it. Run PHP **8.3.36+**, **8.4.27+**, or **8.5.12+** ([PHP Foundation advisory](https://thephp.foundation/blog/2026/10/21/todays-php-security-release-and-how-were-strengthening-php-security/), [CISA alert](https://www.cisa.gov/news-events/alerts/2026/10/21/critical-vulnerabilities-php-require-immediate-patching)).

### Changed

- Require PHP **^8.3**. Composer previously allowed 8.2 even though the docs already required 8.3. PHP 8.2 reaches upstream end of life on 31 December 2026; stay on 1.0.x if you must remain on 8.2 (and use **8.2.35+** until then).
- CI tests PHP 8.3, 8.4, and 8.5. Coverage is collected on 8.4 only.
- Upgrade dev tooling to Pest 4 and PHPUnit 12 so `composer update` can resolve on PHP 8.5.

## [1.0.3] - 2026-08-25

### Security

- Escape `]]>` in every CDATA-wrapped field (item description/summary/content, channel description, titles) so a CDATA breakout cannot inject XML ([GHSA-264h-qh97-mgwf](https://github.com/RumenDamyanov/php-feed/security/advisories/GHSA-264h-qh97-mgwf)).

## [1.0.0] - TBD

### Added

- Framework-agnostic architecture with dependency injection
- Laravel adapter with service provider and auto-discovery
- Symfony adapter for cache, config, response, and view
- RSS 2.0 and Atom 1.0 feed generation
- Comprehensive caching support
- Custom view templates
- 100% test coverage with Pest
- PHPStan static analysis (level 6)
- PSR-12 code style compliance
- Comprehensive documentation

### Changed

- Minimum PHP version requirement to 8.3+
- Strict typing throughout the codebase
- Properties are now private with public getters/setters
- Moved to modern PHP testing with Pest

### Removed

- Direct property access (replaced with getter/setter methods)
- Support for PHP versions below 8.3

### Initial Release

- Initial release of the framework-agnostic PHP Feed package
- Support for Laravel, Symfony, and plain PHP
- Modern PHP 8.3+ architecture
- Comprehensive test suite
- Full documentation
