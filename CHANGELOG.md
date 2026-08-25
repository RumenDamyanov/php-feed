# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
