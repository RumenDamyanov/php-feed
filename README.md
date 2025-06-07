# PHP Feed (Framework-Agnostic)

[![CI](https://github.com/RumenDamyanov/php-feed/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/RumenDamyanov/php-feed/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/RumenDamyanov/php-feed/branch/master/graph/badge.svg)](https://codecov.io/gh/RumenDamyanov/php-feed)

A modern, framework-agnostic PHP Feed generator for Laravel, Symfony, and any PHP project.

## Installation

```bash
composer require rumenx/php-feed
```

## Usage Examples

### Laravel

Register the service provider (auto-discovery is supported for Laravel 5.5+):

```php
// config/app.php
'providers' => [
    // ...existing code...
    Rumenx\Feed\FeedServiceProvider::class,
],
```

Publish package views (optional):

```bash
php artisan vendor:publish --provider="Rumenx\Feed\FeedServiceProvider"
```

Use the Feed in your controller:

```php
use Rumenx\Feed\Feed;

public function feed(Feed $feed)
{
    $feed->setTitle('My Blog Feed');
    $feed->addItem([
        'title' => 'First Post',
        'author' => 'Rumen',
        'link' => 'https://example.com/post/1',
        'pubdate' => now(),
        'description' => 'This is the first post.'
    ]);
    return $feed->render('rss');
}
```

### Symfony

Register the adapters as services in your Symfony config:

```yaml
# config/services.yaml
services:
    Rumenx\Feed\Feed:
        arguments:
            $params:
                cache: '@Rumenx\Feed\SymfonyCacheAdapterImpl'
                config: '@Rumenx\Feed\SymfonyConfigAdapter'
                response: '@Rumenx\Feed\SymfonyResponseAdapter'
                view: '@Rumenx\Feed\SymfonyViewAdapter'
```

Use the Feed in your controller:

```php
use Rumenx\Feed\Feed;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FeedController extends AbstractController
{
    public function feed(Feed $feed)
    {
        $feed->setTitle('My Blog Feed');
        $feed->addItem([
            'title' => 'First Post',
            'author' => 'Rumen',
            'link' => 'https://example.com/post/1',
            'pubdate' => new \DateTime(),
            'description' => 'This is the first post.'
        ]);
        return $feed->render('atom');
    }
}
```

### Plain PHP / Other Frameworks

You can use the Feed class by providing your own implementations for cache, config, response, and view:

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
echo $feed->render('rss');
```

## Features

- RSS and Atom support
- Caching
- Custom views
- Framework-agnostic adapters for Laravel and Symfony

## Notes

- All feed metadata is now accessed via public getter/setter methods. Direct property access is not supported.
- You can extend the package by implementing the provided interfaces for cache, config, response, and view.

## License

This package is open-sourced software licensed under the [MIT License](LICENSE.md).
