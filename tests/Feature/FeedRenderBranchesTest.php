<?php

use Rumenx\Feed\Feed;

describe('Feed render branches', function () {
    it('returns cached response if cache hit', function () {
        $feed = new Feed([
            'cache' => new class implements \Rumenx\Feed\FeedCacheInterface {
                public function has(string $key): bool { return $key === 'cache-key'; }
                public function get(string $key, mixed $default = null): mixed {
                    if ($key === 'cache-key') return 'CACHED';
                    if ($key === 'cache-key_ctype') return 'application/rss+xml';
                    return null;
                }
                public function put(string $key, mixed $value, int $ttl): void {}
                public function forget(string $key): void {}
            },
            'config' => new class implements \Rumenx\Feed\FeedConfigInterface {
                public function get(string $key, mixed $default = null): mixed { return 'en'; }
            },
            'response' => new class implements \Rumenx\Feed\FeedResponseInterface {
                public function make(mixed $content, int $status = 200, array $headers = []): mixed { return ['content'=>$content,'headers'=>$headers,'status'=>$status]; }
            },
            'view' => new class implements \Rumenx\Feed\FeedViewInterface {
                public function make(string $view, array $data = []): mixed { return $view; }
            },
        ]);
        $feed->setCache(10, 'cache-key');
        $result = $feed->render();
        expect($result['content'])->toBe('CACHED');
        expect($result['headers']['Content-Type'])->toBe('application/rss+xml; charset=utf-8');
        expect($result['status'])->toBe(200);
    });

    it('uses custom view and ctype', function () {
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
                public function make(mixed $content, int $status = 200, array $headers = []): mixed { return ['content'=>$content,'headers'=>$headers,'status'=>$status]; }
            },
            'view' => new class implements \Rumenx\Feed\FeedViewInterface {
                public function make(string $view, array $data = []): mixed { return $view; }
            },
        ]);
        $feed->setCustomView('custom-view');
        $feed->setCtype('text/xml');
        $result = $feed->render();
        expect($result['content'])->toBe('custom-view');
        expect($result['headers']['Content-Type'])->toBe('text/xml; charset=utf-8');
        expect($result['status'])->toBe(200);
    });
    it('sets caching and cacheKey when passed as render arguments', function () {
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
                public function make(mixed $content, int $status = 200, array $headers = []): mixed { return ['content'=>$content,'headers'=>$headers,'status'=>$status]; }
            },
            'view' => new class implements \Rumenx\Feed\FeedViewInterface {
                public function make(string $view, array $data = []): mixed {
                    return new class {
                        public function render() { return 'RENDERED'; }
                    };
                }
            },
        ]);
        // Pass cache and key as arguments
        $feed->render('atom', 42, 'branch-key');
        // Use reflection to check private properties
        $ref = new \ReflectionClass($feed);
        $caching = $ref->getProperty('caching');
        $caching->setAccessible(true);
        $cacheKey = $ref->getProperty('cacheKey');
        $cacheKey->setAccessible(true);
        expect($caching->getValue($feed))->toBe(42);
        expect($cacheKey->getValue($feed))->toBe('branch-key');
    });
});
