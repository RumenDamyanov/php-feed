<?php

use Rumenx\Feed\Feed;

describe('Feed setCache edge', function () {
    it('clears cache if duration < 1', function () {
        $feed = new Feed([
            'cache' => new class implements \Rumenx\Feed\FeedCacheInterface {
                private $cleared = false;
                public function has(string $key): bool { return true; }
                public function get(string $key, mixed $default = null): mixed { return null; }
                public function put(string $key, mixed $value, int $ttl): void {}
                public function forget(string $key): void { $this->cleared = true; }
                public function wasCleared(): bool { return $this->cleared; }
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
        $feed->setCache(0, 'test-key');
        expect($feed->getCacheKey())->toBe('test-key');
        expect($feed->getCacheDuration())->toBe(0);
        // The cache->forget should have been called
        $cacheProp = (new \ReflectionClass($feed))->getProperty('cache');
        $cacheProp->setAccessible(true);
        $cacheObj = $cacheProp->getValue($feed);
        expect($cacheObj->wasCleared())->toBeTrue();
    });
});
