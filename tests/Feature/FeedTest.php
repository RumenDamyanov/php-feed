<?php
use Rumenx\Feed\Feed;

test('feed can add and retrieve items', function () {
    $feed = new Feed([
        'cache' => new class implements \Rumenx\Feed\FeedCacheInterface {
            private array $store = [];
            public function has(string $key): bool { return isset($this->store[$key]); }
            public function get(string $key, $default = null): mixed { return $this->store[$key] ?? $default; }
            public function put(string $key, mixed $value, int $ttl): void { $this->store[$key] = $value; }
            public function forget(string $key): void { unset($this->store[$key]); }
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
    $feed->addItem([
        'title' => 'Test',
        'author' => 'Tester',
        'link' => 'https://example.com',
        'pubdate' => date('c'),
        'description' => 'desc',
    ]);
    $items = (new \ReflectionClass($feed))->getProperty('items');
    $items->setAccessible(true);
    $itemsArr = $items->getValue($feed);
    expect($itemsArr)->toHaveCount(1);
    expect($itemsArr[0]['title'])->toBe('Test');
});
