<?php
use Rumenx\Feed\Feed;

test('Feed render puts to cache and returns response', function () {
    $calls = [];
    $cache = new class($calls) implements \Rumenx\Feed\FeedCacheInterface {
        private $calls;
        private $store = [];
        public function __construct(&$calls) { $this->calls = &$calls; }
        public function has(string $key): bool { $this->calls[] = "has:$key"; return false; }
        public function get(string $key, mixed $default = null): mixed { $this->calls[] = "get:$key"; return $this->store[$key] ?? ($key === 'key_ctype' ? 'application/rss+xml' : null); }
        public function put(string $key, mixed $value, int $ttl): void { $this->calls[] = "put:$key"; $this->store[$key] = $value; }
        public function forget(string $key): void { $this->calls[] = "forget:$key"; unset($this->store[$key]); }
        public function getCalls() { return $this->calls; }
    };
    $view = new class implements \Rumenx\Feed\FeedViewInterface {
        public function make(string $view, array $data = []): mixed {
            return new class {
                public function render() { return 'RENDERED'; }
            };
        }
    };
    $response = new class implements \Rumenx\Feed\FeedResponseInterface {
        public function make(mixed $content, int $status = 200, array $headers = []): mixed { return [$content, $status, $headers]; }
    };
    $feed = new Feed([
        'cache' => $cache,
        'config' => new class implements \Rumenx\Feed\FeedConfigInterface {
            public function get(string $key, mixed $default = null): mixed { return 'en'; }
        },
        'response' => $response,
        'view' => $view,
    ]);
    $feed->setCache(10, 'key');
    $result = $feed->render('rss');
    expect($result[0])->toBe('RENDERED');
    expect($result[1])->toBe(200);
    expect($result[2]['Content-Type'])->toBe('application/rss+xml; charset=utf-8');
});
