<?php

use Rumenx\Feed\Feed;

describe('Feed addItem multidimensional', function () {
    it('handles multidimensional array', function () {
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
        $feed->addItem([
            ['title' => 'A'],
            ['title' => 'B'],
        ]);
        $items = (new \ReflectionClass($feed))->getProperty('items');
        $items->setAccessible(true);
        $itemsArr = $items->getValue($feed);
        expect($itemsArr)->toHaveCount(2);
        expect($itemsArr[0]['title'])->toBe('A');
        expect($itemsArr[1]['title'])->toBe('B');
    });
});
