<?php

/**
 * Unit tests for the SimpleViewAdapter class.
 */
test('SimpleViewAdapter can be instantiated', function () {
    $adapter = new \Rumenx\Feed\SimpleViewAdapter();
    expect($adapter)->toBeInstanceOf(\Rumenx\Feed\SimpleViewAdapter::class);
    expect($adapter)->toBeInstanceOf(\Rumenx\Feed\FeedViewInterface::class);
});

test('SimpleViewAdapter generates RSS feed', function () {
    $adapter = new \Rumenx\Feed\SimpleViewAdapter();

    $data = [
        'items' => [
            [
                'title' => 'Test Item',
                'description' => 'Test Description',
                'link' => 'https://example.com/test',
                'author' => 'Test Author',
                'pubdate' => date('r')
            ]
        ],
        'channel' => [
            'title' => 'Test Feed',
            'description' => 'Test Description',
            'link' => 'https://example.com'
        ]
    ];

    $result = $adapter->make('rss', $data);
    $content = $result->render();

    expect($content)->toContain('<?xml version="1.0" encoding="UTF-8"?>');
    expect($content)->toContain('<rss version="2.0"');
    expect($content)->toContain('<channel>');
    expect($content)->toContain('<title>Test Feed</title>');
    expect($content)->toContain('<item>');
    expect($content)->toContain('Test Item');
});

test('SimpleViewAdapter generates Atom feed', function () {
    $adapter = new \Rumenx\Feed\SimpleViewAdapter();

    $data = [
        'items' => [
            [
                'title' => 'Test Item',
                'description' => 'Test Description',
                'link' => 'https://example.com/test',
                'author' => 'Test Author',
                'pubdate' => date('r')
            ]
        ],
        'channel' => [
            'title' => 'Test Feed',
            'description' => 'Test Description',
            'link' => 'https://example.com'
        ]
    ];

    $result = $adapter->make('atom', $data);
    $content = $result->render();

    expect($content)->toContain('<?xml version="1.0" encoding="UTF-8"?>');
    expect($content)->toContain('<feed xmlns="http://www.w3.org/2005/Atom">');
    expect($content)->toContain('<title>Test Feed</title>');
    expect($content)->toContain('<entry>');
    expect($content)->toContain('Test Item');
});

test('SimpleViewAdapter handles empty data gracefully', function () {
    $adapter = new \Rumenx\Feed\SimpleViewAdapter();

    $result = $adapter->make('rss', []);
    $content = $result->render();

    expect($content)->toContain('<?xml version="1.0" encoding="UTF-8"?>');
    expect($content)->toContain('<rss version="2.0"');
    expect($content)->toContain('<channel>');
});

test('SimpleViewAdapter uses default values when data is missing', function () {
    $adapter = new \Rumenx\Feed\SimpleViewAdapter();

    $result = $adapter->make('rss');
    $content = $result->render();

    expect($content)->toContain('<title>Feed</title>');
    expect($content)->toContain('<description><![CDATA[Feed Description]]></description>');
    expect($content)->toContain('<link>http://localhost</link>');
});

test('SimpleViewAdapter result object implements __toString', function () {
    $adapter = new \Rumenx\Feed\SimpleViewAdapter();

    $data = [
        'channel' => ['title' => 'Test']
    ];

    $result = $adapter->make('rss', $data);
    $stringContent = (string)$result;

    expect($stringContent)->toContain('<?xml');
    expect($stringContent)->toContain('Test');
});

test('SimpleViewAdapter escapes HTML entities correctly', function () {
    $adapter = new \Rumenx\Feed\SimpleViewAdapter();

    $data = [
        'items' => [
            [
                'title' => 'Title with & special chars',
                'description' => 'Description with <script>',
                'link' => 'https://example.com/test?param=value&other=true',
                'author' => 'Author & Co',
                'pubdate' => date('r')
            ]
        ],
        'channel' => [
            'title' => 'Feed & More',
            'description' => 'Description with <tags>',
            'link' => 'https://example.com?param=value&other=true'
        ]
    ];

    $result = $adapter->make('rss', $data);
    $content = $result->render();

    // Check that dangerous content is properly escaped or wrapped in CDATA
    expect($content)->toContain('<![CDATA[');
    // URLs and attributes should be properly escaped
    expect($content)->toContain('&amp;other=true');
});
