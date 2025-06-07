<?php
require_once __DIR__ . '/../../src/Rumenx/Feed/Feed.php';

use Rumenx\Feed\Feed;
use Rumenx\Feed\FeedCacheInterface;
use Rumenx\Feed\FeedConfigInterface;
use Rumenx\Feed\FeedResponseInterface;
use Rumenx\Feed\FeedViewInterface;

describe('Feed uncovered methods', function () {
    // Minimal valid config for Feed
    $config = [
        'cache' => new class implements \Rumenx\Feed\FeedCacheInterface {
            public function has(string $key): bool { return false; }
            public function get(string $key, mixed $default = null): mixed { return $default; }
            public function put(string $key, mixed $value, int $ttl): void {}
            public function forget(string $key): void {}
        },
        'config' => new class implements \Rumenx\Feed\FeedConfigInterface {
            public function get(string $key, mixed $default = null): mixed { return $default; }
        },
        'response' => new class implements \Rumenx\Feed\FeedResponseInterface {
            public function make(mixed $content, int $status = 200, array $headers = []): mixed { return $content; }
        },
        'view' => new class implements \Rumenx\Feed\FeedViewInterface {
            public function make(string $view, array $data = []): mixed { return ''; }
        },
    ];

    it('can set and get custom view', function () use ($config) {
        $feed = new Feed($config);
        $feed->setCustomView('my-view');
        expect($feed->getCustomView())->toBe('my-view');
    });

    it('can set and get namespaces', function () use ($config) {
        $feed = new Feed($config);
        $feed->setNamespaces(['foo' => 'bar']);
        expect($feed->getNamespaces())->toBe(['foo' => 'bar']);
    });

    it('can set and get date format', function () use ($config) {
        $feed = new Feed($config);
        $feed->setDateFormat('Y-m-d');
        expect($feed->getDateFormat())->toBe('Y-m-d');
    });

    it('can set and get shortening limit', function () use ($config) {
        $feed = new Feed($config);
        $feed->setShorteningLimit(42);
        expect($feed->getShorteningLimit())->toBe(42);
    });

    it('can set and get cache key and duration', function () use ($config) {
        $feed = new Feed($config);
        $feed->setCache(123, 'my-key');
        expect($feed->getCacheKey())->toBe('my-key');
        expect($feed->getCacheDuration())->toBe(123);
    });

    it('setTextLimit and getTextLimit work', function () use ($config) {
        $feed = new Feed($config);
        $feed->setTextLimit(77);
        expect($feed->getTextLimit())->toBe(77);
    });

    it('setShortening and getShortening work', function () use ($config) {
        $feed = new Feed($config);
        $feed->setShortening(true);
        expect($feed->getShortening())->toBeTrue();
        $feed->setShortening(false);
        expect($feed->getShortening())->toBeFalse();
    });

    it('formatDate covers all branches', function () use ($config) {
        $feed = new Feed($config);
        // Default: datetime
        expect($feed->formatDate('2020-01-01 12:00:00', 'atom'))->toBe(date('c', strtotime('2020-01-01 12:00:00')));
        expect($feed->formatDate('2020-01-01 12:00:00', 'rss'))->toBe(date('D, d M Y H:i:s O', strtotime('2020-01-01 12:00:00')));
        // Timestamp
        $feed->setDateFormat('timestamp');
        $ts = strtotime('2020-01-01 12:00:00');
        expect($feed->formatDate($ts, 'atom'))->toBe(date('c', strtotime('@'.$ts)));
        expect($feed->formatDate($ts, 'rss'))->toBe(date('D, d M Y H:i:s O', strtotime('@'.$ts)));
        // Carbon (simulate with DateTime object with toDateTimeString)
        $feed->setDateFormat('carbon');
        $carbon = new class {
            public function toDateTimeString() { return '2020-01-01 12:00:00'; }
        };
        expect($feed->formatDate($carbon, 'atom'))->toBe(date('c', strtotime('2020-01-01 12:00:00')));
        expect($feed->formatDate($carbon, 'rss'))->toBe(date('D, d M Y H:i:s O', strtotime('2020-01-01 12:00:00')));
    });

    it('Feed::link covers all branches', function () {
        expect(Feed::link('url'))->toBe('<link rel="alternate" type="application/atom+xml" href="url">');
        expect(Feed::link('url', 'rss'))->toBe('<link rel="alternate" type="application/rss+xml" href="url">');
        expect(Feed::link('url', 'atom', 'Title'))->toBe('<link rel="alternate" type="application/atom+xml" href="url" title="Title">');
        expect(Feed::link('url', 'atom', null, 'en'))->toBe('<link rel="alternate" hreflang="en" type="application/atom+xml" href="url">');
        expect(Feed::link('url', 'custom', 'T', 'fr'))->toBe('<link rel="alternate" hreflang="fr" type="custom" href="url" title="T">');
    });

    it('clearCache does not error if not cached', function () use ($config) {
        $feed = new Feed($config);
        $feed->clearCache();
        expect(true)->toBeTrue();
    });

    // Add more tests for any other uncovered methods as needed
});
