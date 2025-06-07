<?php
// FeedSettersTest.php
// Feature tests for Feed setters, getters, and addItem logic.

use Rumenx\Feed\Feed;

/**
 * @covers \Rumenx\Feed\Feed
 *
 * These tests verify the correct behavior of Feed's setters, getters, and addItem logic,
 * including edge cases for subtitle and constructor dependency validation.
 */
test('feed setters and getters work', function () {
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
    $feed->setTitle('Title');
    $feed->setSubtitle('Subtitle');
    $feed->setDescription('Description');
    $feed->setDomain('domain');
    $feed->setLink('link');
    $feed->setRef('ref');
    $feed->setLogo('logo');
    $feed->setIcon('icon');
    $feed->setCover('cover');
    $feed->setColor('color');
    $feed->setGa('ga');
    $feed->setRelated(true);
    $feed->setCopyright('copyright');
    $feed->setPubdate('pubdate');
    $feed->setLang('lang');
    $feed->setCharset('charset');
    $feed->setCtype('ctype');
    $feed->setDuration('duration');
    expect($feed->getTitle())->toBe('Title');
    expect($feed->getSubtitle())->toBe('Subtitle');
    expect($feed->getDescription())->toBe('Description');
    expect($feed->getDomain())->toBe('domain');
    expect($feed->getLink())->toBe('link');
    expect($feed->getRef())->toBe('ref');
    expect($feed->getLogo())->toBe('logo');
    expect($feed->getIcon())->toBe('icon');
    expect($feed->getCover())->toBe('cover');
    expect($feed->getColor())->toBe('color');
    expect($feed->getGa())->toBe('ga');
    expect($feed->getRelated())->toBeTrue();
    expect($feed->getCopyright())->toBe('copyright');
    expect($feed->getPubdate())->toBe('pubdate');
    expect($feed->getLang())->toBe('lang');
    expect($feed->getCharset())->toBe('charset');
    expect($feed->getCtype())->toBe('ctype');
    expect($feed->getDuration())->toBe('duration');
});

// Uncovered: test default values for all getters
$feed2 = new Feed([
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
// Only test properties that are not set by default in Feed's constructor
expect($feed2->getDomain())->toBeNull();
expect($feed2->getLink())->toBeNull();
expect($feed2->getRef())->toBeNull();
expect($feed2->getLogo())->toBeNull();
expect($feed2->getIcon())->toBeNull();
expect($feed2->getCover())->toBeNull();
expect($feed2->getColor())->toBeNull();
expect($feed2->getGa())->toBeNull();
expect($feed2->getRelated())->toBeFalse();
expect($feed2->getCopyright())->toBeNull();
expect($feed2->getPubdate())->toBeNull();
expect($feed2->getLang())->toBeNull();
expect($feed2->getCtype())->toBeNull();
expect($feed2->getDuration())->toBeNull();

test('feed addItem processes subtitle if feed subtitle is empty string (branch coverage)', function () {
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
    // Set subtitle to empty string
    $feed->setSubtitle('');
    $feed->addItem([
        'title' => 'Test',
        'subtitle' => 'Processed Subtitle',
        'link' => 'https://example.com',
        'pubdate' => date('c'),
        'description' => 'desc',
    ]);
    expect($feed->getSubtitle())->toBe('Processed Subtitle');
});

test('Feed constructor throws if required dependency is missing', function () {
    foreach (["cache", "config", "response", "view"] as $dep) {
        $params = [
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
        ];
        unset($params[$dep]);
        expect(fn() => new \Rumenx\Feed\Feed($params))
            ->toThrow(\InvalidArgumentException::class, "Missing required dependency: $dep");
    }
});
