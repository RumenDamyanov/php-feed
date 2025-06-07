<?php

use Rumenx\Feed\Feed;
use Carbon\Carbon;

describe('Feed formatDate', function () {
    it('formats carbon date', function () {
        $feed = new Feed([
            'cache' => new class implements \Rumenx\Feed\FeedCacheInterface {
                public function has(string $key): bool { return false; }
                public function get(string $key, mixed $default = null): mixed { return null; }
                public function put(string $key, mixed $value, int $ttl): void {}
                public function forget(string $key): void {}
            },
            'config' => new class implements \Rumenx\Feed\FeedConfigInterface {
                public function get(string $key, mixed $default = null): mixed { return 'en'; }
            },
            'response' => new class implements \Rumenx\Feed\FeedResponseInterface {
                public function make(mixed $content, int $status = 200, array $headers = []): mixed { return $content; }
            },
            'view' => new class implements \Rumenx\Feed\FeedViewInterface {
                public function make(string $view, array $data = []): mixed { return ''; }
            },
        ]);
        $feed->setDateFormat('carbon');
        $date = Carbon::create(2024, 1, 2, 3, 4, 5);
        expect($feed->formatDate($date, 'atom'))->toBe('2024-01-02T03:04:05+00:00');
        expect($feed->formatDate($date, 'rss'))->toBe('Tue, 02 Jan 2024 03:04:05 +0000');
    });

    it('formats timestamp', function () {
        $feed = new Feed([
            'cache' => new class implements \Rumenx\Feed\FeedCacheInterface {
                public function has(string $key): bool { return false; }
                public function get(string $key, mixed $default = null): mixed { return null; }
                public function put(string $key, mixed $value, int $ttl): void {}
                public function forget(string $key): void {}
            },
            'config' => new class implements \Rumenx\Feed\FeedConfigInterface {
                public function get(string $key, mixed $default = null): mixed { return 'en'; }
            },
            'response' => new class implements \Rumenx\Feed\FeedResponseInterface {
                public function make(mixed $content, int $status = 200, array $headers = []): mixed { return $content; }
            },
            'view' => new class implements \Rumenx\Feed\FeedViewInterface {
                public function make(string $view, array $data = []): mixed { return ''; }
            },
        ]);
        $feed->setDateFormat('timestamp');
        $timestamp = strtotime('2024-01-02 03:04:05');
        expect($feed->formatDate($timestamp, 'atom'))->toBe('2024-01-02T03:04:05+00:00');
        expect($feed->formatDate($timestamp, 'rss'))->toBe('Tue, 02 Jan 2024 03:04:05 +0000');
    });

    it('formats datetime string', function () {
        $feed = new Feed([
            'cache' => new class implements \Rumenx\Feed\FeedCacheInterface {
                public function has(string $key): bool { return false; }
                public function get(string $key, mixed $default = null): mixed { return null; }
                public function put(string $key, mixed $value, int $ttl): void {}
                public function forget(string $key): void {}
            },
            'config' => new class implements \Rumenx\Feed\FeedConfigInterface {
                public function get(string $key, mixed $default = null): mixed { return 'en'; }
            },
            'response' => new class implements \Rumenx\Feed\FeedResponseInterface {
                public function make(mixed $content, int $status = 200, array $headers = []): mixed { return $content; }
            },
            'view' => new class implements \Rumenx\Feed\FeedViewInterface {
                public function make(string $view, array $data = []): mixed { return ''; }
            },
        ]);
        $feed->setDateFormat('datetime');
        $date = '2024-01-02 03:04:05';
        expect($feed->formatDate($date, 'atom'))->toBe('2024-01-02T03:04:05+00:00');
        expect($feed->formatDate($date, 'rss'))->toBe('Tue, 02 Jan 2024 03:04:05 +0000');
    });
});
