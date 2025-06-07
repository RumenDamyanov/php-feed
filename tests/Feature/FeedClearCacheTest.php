<?php
use Rumenx\Feed\Feed;

test('feed clearCache removes cache', function () {
    $cache = new class implements \Rumenx\Feed\FeedCacheInterface {
        private array $store = [];
        public function has(string $key): bool { return isset($this->store[$key]); }
        public function get(string $key, $default = null): mixed { return $this->store[$key] ?? $default; }
        public function put(string $key, mixed $value, int $ttl): void { $this->store[$key] = $value; }
        public function forget(string $key): void { unset($this->store[$key]); }
    };
    $feed = new Feed([
        'cache' => $cache,
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
    $feed->setCache(10, 'clear-cache-key');
    $cache->put('clear-cache-key', 'value', 10);
    $feed->clearCache();
    expect($feed->isCached())->toBeFalse();
});
