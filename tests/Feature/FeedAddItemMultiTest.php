<?php
use Rumenx\Feed\Feed;

test('feed can add multiple items at once', function () {
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
    $feed->addItem([
        [
            'title' => 'A',
            'author' => 'Tester',
            'link' => 'https://example.com/a',
            'pubdate' => date('c'),
            'description' => 'desc',
        ],
        [
            'title' => 'B',
            'author' => 'Tester',
            'link' => 'https://example.com/b',
            'pubdate' => date('c'),
            'description' => 'desc',
        ]
    ]);
    $items = (new \ReflectionClass($feed))->getProperty('items');
    $items->setAccessible(true);
    $itemsArr = $items->getValue($feed);
    expect($itemsArr)->toHaveCount(2);
    expect($itemsArr[0]['title'])->toBe('A');
    expect($itemsArr[1]['title'])->toBe('B');
});
