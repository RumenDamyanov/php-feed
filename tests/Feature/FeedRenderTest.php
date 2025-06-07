<?php
use Rumenx\Feed\Feed;

test('feed render returns correct content', function () {
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
            public function make(mixed $content, int $status = 200, array $headers = []): mixed { return ['content' => $content, 'status' => $status, 'headers' => $headers]; }
        },
        'view' => new class implements \Rumenx\Feed\FeedViewInterface {
            public function make(string $view, array $data = []): mixed { return 'view:'.$view; }
        },
    ]);
    $feed->setTitle('RenderTest');
    $feed->addItem([
        'title' => 'RenderItem',
        'author' => 'Tester',
        'link' => 'https://example.com',
        'pubdate' => date('c'),
        'description' => 'desc',
    ]);
    $result = $feed->render('rss');
    expect($result['content'])->toContain('view:feed::rss');
    expect($result['status'])->toBe(200);
    expect($result['headers'])->toBeArray();
});
