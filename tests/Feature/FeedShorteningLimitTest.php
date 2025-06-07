<?php
use Rumenx\Feed\Feed;

test('feed shortening limit can be set and retrieved', function () {
    $feed = new Feed([
        'cache' => new class implements \Rumenx\Feed\FeedCacheInterface {
            public function has(string $key): bool { return false; }
            public function get(string $key, $default = null): mixed { return $default; }
            public function put(string $key, mixed $value, int $ttl): void {}
            public function forget(string $key): void {}
        },
        'config' => new class implements \Rumenx\Feed\FeedConfigInterface {
            public function get(string $key, $default = null): mixed { return $default; }
        },
        'response' => new class implements \Rumenx\Feed\FeedResponseInterface {
            public function make(mixed $content, int $status = 200, array $headers = []): mixed { return $content; }
        },
        'view' => new class implements \Rumenx\Feed\FeedViewInterface {
            public function make(string $view, array $data = []): mixed { return 'view:'.$view; }
        },
    ]);
    expect($feed->getTextLimit())->toBe(150);
    $feed->setTextLimit(10);
    expect($feed->getTextLimit())->toBe(10);
});
