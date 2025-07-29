# PHP Feed (Framework-Agnostic)

[![CI](https://github.com/RumenDamyanov/php-feed/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/RumenDamyanov/php-feed/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/RumenDamyanov/php-feed/branch/master/graph/badge.svg)](https://codecov.io/gh/RumenDamyanov/php-feed)
[![PHP Version Require](http://poser.pugx.org/rumenx/php-feed/require/php)](https://packagist.org/packages/rumenx/php-feed)
[![Latest Stable Version](http://poser.pugx.org/rumenx/php-feed/v)](https://packagist.org/packages/rumenx/php-feed)
[![License](http://poser.pugx.org/rumenx/php-feed/license)](https://packagist.org/packages/rumenx/php-feed)

A modern, framework-agnostic PHP Feed generator for Laravel, Symfony, and any PHP project. Generate RSS and Atom feeds with full caching support and customizable views.

## Features

- 🚀 **Framework Agnostic**: Works with Laravel, Symfony, or any PHP project
- 📡 **Multiple Formats**: RSS 2.0 and Atom 1.0 support
- ⚡ **Caching**: Built-in caching support with framework adapters
- 🎨 **Custom Views**: Use your own templates for feed generation
- 🔧 **Dependency Injection**: Clean architecture with adapter pattern
- ✅ **100% Test Coverage**: Thoroughly tested with Pest
- 📋 **PSR-12 Compliant**: Follows modern PHP standards
- 🔒 **Type Safe**: Full PHP 8.3+ type declarations

## Requirements

- PHP 8.3+
- Composer

## Installation

```bash
composer require rumenx/php-feed
```

## Usage Examples

### Laravel

**Basic Usage:**

```php
use Rumenx\Feed\FeedFactory;

class FeedController extends Controller
{
    public function feed()
    {
        $feed = FeedFactory::create();
        $feed->setTitle('My Blog Feed');
        $feed->setDescription('Latest posts from my blog');
        $feed->setLink('https://example.com');
        
        $feed->addItem([
            'title' => 'First Post',
            'author' => 'Rumen',
            'link' => 'https://example.com/post/1',
            'pubdate' => now(),
            'description' => 'This is the first post.'
        ]);
        
        // Return XML string directly
        $xml = $feed->render('rss');
        return response($xml, 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
```

**Using Laravel Views (Optional):**

For more control, you can use the included Blade templates:

```php
use Rumenx\Feed\FeedFactory;

class FeedController extends Controller  
{
    public function feed()
    {
        $feed = FeedFactory::create();
        $feed->setTitle('My Blog Feed');
        $feed->addItem([
            'title' => 'First Post',
            'author' => 'Rumen', 
            'link' => 'https://example.com/post/1',
            'pubdate' => now(),
            'description' => 'This is the first post.'
        ]);
        
        // Get data for your own view template
        $items = $feed->getItems();
        $channel = [
            'title' => $feed->getTitle(),
            'description' => $feed->getDescription(),
            'link' => $feed->getLink()
        ];
        
        return response()->view('feed.rss', compact('items', 'channel'), 200, [
            'Content-Type' => 'application/xml'
        ]);
    }
}
```

### Symfony

**Basic Usage:**

```php
use Rumenx\Feed\FeedFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class FeedController extends AbstractController
{
    public function feed(): Response
    {
        $feed = FeedFactory::create();
        $feed->setTitle('My Blog Feed');
        $feed->setDescription('Latest posts from my blog');
        $feed->setLink('https://example.com');
        
        $feed->addItem([
            'title' => 'First Post',
            'author' => 'Rumen',
            'link' => 'https://example.com/post/1',
            'pubdate' => new \DateTime(),
            'description' => 'This is the first post.'
        ]);
        
        // Return XML response
        $xml = $feed->render('atom');
        return new Response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
```

**Using Symfony Views (Optional):**

For more control, you can use Twig templates:

```php
class FeedController extends AbstractController
{
    public function feed(): Response
    {
        $feed = FeedFactory::create();
        $feed->setTitle('My Blog Feed');
        $feed->addItem([
            'title' => 'First Post',
            'author' => 'Rumen',
            'link' => 'https://example.com/post/1',
            'pubdate' => new \DateTime(),
            'description' => 'This is the first post.'
        ]);
        
        // Get data for your own Twig template
        $items = $feed->getItems();
        $channel = [
            'title' => $feed->getTitle(),
            'description' => $feed->getDescription(),
            'link' => $feed->getLink()
        ];
        
        return $this->render('feed/atom.xml.twig', [
            'items' => $items,
            'channel' => $channel
        ], new Response('', 200, ['Content-Type' => 'application/xml']));
    }
}
```

### Plain PHP / Other Frameworks

**Simple Usage (Recommended):**

```php
require 'vendor/autoload.php';

use Rumenx\Feed\FeedFactory;

// Create feed with simple built-in adapters
$feed = FeedFactory::create();
$feed->setTitle('My Feed');
$feed->setDescription('Feed description');
$feed->setLink('https://example.com');

$feed->addItem([
    'title' => 'Hello World',
    'author' => 'Rumen',
    'link' => 'https://example.com/hello',
    'pubdate' => date('c'),
    'description' => 'Hello world post!'
]);

// Output RSS feed
header('Content-Type: application/xml');
echo $feed->render('rss');
```

**Advanced - Custom Adapters:**

You can provide your own implementations for cache, config, response, and view:

```php
use Rumenx\Feed\Feed;

$feed = new Feed([
    'cache' => new MyCacheAdapter(),
    'config' => new MyConfigAdapter(),
    'response' => new MyResponseAdapter(),
    'view' => new MyViewAdapter(),
]);

$feed->setTitle('My Feed');
$feed->addItem([
    'title' => 'Hello',
    'author' => 'Rumen',
    'link' => 'https://example.com/hello',
    'pubdate' => date('c'),
    'description' => 'Hello world!'
]);

// Use render() for framework-specific response
// or render() for XML string in plain PHP
echo $feed->render('rss');
```

## Development

### Available Composer Scripts

```bash
# Run tests
composer test

# Run tests with coverage
composer test:coverage

# Run tests with HTML coverage report
composer test:coverage-html

# Watch tests (automatically re-run on file changes)
composer test:watch

# Static analysis with PHPStan
composer analyse

# Check code style (PSR-12)
composer style

# Fix code style automatically
composer style:fix

# Run all checks (tests + analysis + style)
composer check

# Run CI checks (coverage + analysis + style)
composer ci
```

## API Reference

### Factory Method

```php
// Create feed with simple adapters (recommended for most users)
$feed = FeedFactory::create($config);
```

### Core Methods

```php
// Feed configuration
$feed->setTitle(string $title): self
$feed->setDescription(string $description): self
$feed->setLink(string $link): self
$feed->setDateFormat(string $format): self
$feed->setLanguage(string $language): self

// Item management
$feed->addItem(array $item): self
$feed->addItems(array $items): self

// Rendering
$feed->render(string $format = 'rss'): mixed // Returns framework-specific response or XML string

// Caching
$feed->isCached(string $key): bool
$feed->clearCache(string $key): self
```

## Architecture

This package follows a clean architecture pattern with dependency injection:

- **Feed**: Core feed generator class
- **Adapters**: Framework-specific implementations
  - `FeedCacheInterface`: Caching operations
  - `FeedConfigInterface`: Configuration access
  - `FeedResponseInterface`: HTTP response handling
  - `FeedViewInterface`: Template rendering

## Support This Project

If you find this project useful, please consider supporting its development:

[![GitHub Sponsors](https://img.shields.io/github/sponsors/RumenDamyanov?style=for-the-badge&logo=github-sponsors&logoColor=white)](https://github.com/sponsors/RumenDamyanov)

Other ways to support:

- ⭐ **Star this repository**
- 🐛 **Report bugs and suggest improvements**
- 💻 **Contribute code or documentation**
- 💖 **Make a donation** - See [FUNDING.md](FUNDING.md) for details

## Contributing

We welcome contributions! Please see our [Contributing Guide](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please check our [Security Policy](SECURITY.md).

## License

This package is open-sourced software licensed under the [MIT License](LICENSE.md).

## Links

- [Contributing Guidelines](CONTRIBUTING.md)
- [Security Policy](SECURITY.md)
- [License](LICENSE.md)
- [Changelog](CHANGELOG.md)
- [Funding & Support](FUNDING.md)
- [Issues](https://github.com/RumenDamyanov/php-feed/issues)
- [GitHub Sponsors](.github/FUNDING.yml)
